真实场景使用ansible-playbook日常运维nginx
========================


## 课程目标：

```

1.  了解nginx，以及其实际场景中的部署、更新等操作 
2.  学习安装部署、配置nginx 
3.  实践利用ansible-playbook下发nginx及远程维护 
4.  实践到自身实际的服务器中，学以致用
```



### 1. NGINX介绍及其使用

* NGINX是一个高性能的 HTTP 和 反向代理 服务器 

* 轻量级， 比apache占用更少的内存及资源 

* 网络模型是异步非阻塞的，apache是阻塞型的， 所以在高并发下nginx更能保持低消耗而高性能

* 由于架构设计等原因，apache比nginx更加稳定, nginx相对来说bug会多一些, apache更加稳定

* apache的各种模块功能上要比nginx强大， 比如URL重写，连接池， SSL等模块功能

* 总的来说，倾向于性能，优先选择nginx， 倾向于稳定， 优先选择apache

  ​

* 首先在管理端编译安装nginx （在管理端执行）
```shell
# 直接用apt-get, yum 的方式虽然方便， 但是编译安装我们是devops系统运维一定要掌握的安装方式

# 1. 首先安装编译nginx需要的依赖包， 依赖包建议用 apt-get install安装，这些都是跟系统发行版号配置的软件包，比较稳定
apt-get install libpcre3 libpcre3-dev openssl zlib1g zlib1g-dev 

# 2. 先针对编译安装nginx进行配置检查
./configure --prefix=/usr/local/nginx --with-http_realip_module --with-http_sub_module --with-http_gzip_static_module --with-http_stub_status_module  --with-pcre

# ./configue 的作用就是配置我们即将要安装的nginx的一些选项
# --prefix 表要要安装在哪个目录
# --with-xx 的写法表示我们在编译nginx过程中会用到什么软件库，常见的软件库有zlib（压缩算法用到），
# pcre(正则库，URL重写会用到), ssl(加密，HTTPS会用到)

# 3. make 编译

# 4. make install 安装

# 编译安装是不会生成管理脚本的，要自己编写， 类似  /etc/init.d/apache2 这样的管理脚本
# 编写nginx的管理脚本

# ------------------------------------------------------------------

#! /bin/sh

### BEGIN INIT INFO
# Provides:          nginx
# Required-Start:    $all
# Required-Stop:     $all
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: starts the nginx web server
# Description:       starts nginx using start-stop-daemon
### END INIT INFO


PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
DAEMON=/usr/local/nginx/sbin/nginx
NAME=nginx
DESC=nginx

PIDFILE=/var/run/$NAME.pid

test -x $DAEMON || exit 0

# Include nginx defaults if available
if [ -f /etc/default/nginx ] ; then
        . /etc/default/nginx
fi

set -e

case "$1" in
  start)
        echo -n "Starting $DESC: "
        start-stop-daemon --start --quiet --pidfile $PIDFILE --exec $DAEMON -- $DAEMON_OPTS
        echo "$NAME."
        ;;
  stop)
        echo -n "Stopping $DESC: "
        start-stop-daemon --stop --quiet --pidfile $PIDFILE --exec $DAEMON
        echo "$NAME."
        ;;
  restart|force-reload)
        echo -n "Restarting $DESC: "
        start-stop-daemon --stop --quiet --pidfile $PIDFILE --exec $DAEMON
        sleep 1
        start-stop-daemon --start --quiet --pidfile $PIDFILE --exec $DAEMON -- $DAEMON_OPTS
        echo "$NAME."
        ;;
  reload)
      echo -n "Reloading $DESC configuration: "
      start-stop-daemon --stop --signal HUP --quiet --pidfile $PIDFILE --exec $DAEMON
      echo "$NAME."
      ;;
  *)
        N=/etc/init.d/$NAME
        echo "Usage: $N {start|stop|restart|force-reload}" >&2
        exit 1
        ;;
esac

exit 0

# ------------------------------------------------------------------------
# 上面的脚本保存为  /etc/init.d/nginx


# 启动nginx
/etc/init.d/nginx start


  
```



* 按照官方建议生成目录结构 （在管理端执行）
```shell

cd /etc/ansible

mkdir -p nginx_install/roles/{common,delete,install}/{handlers,files,meta,tasks,templates,vars}


# 文件夹作用说明
# common  	为安装nginx做一些准备配置操作
# delete  	删除nginx的操作
# install  	安装nginx的操作

# 以上的每个目录下 又有几个目录
# handlers    标识当发生改变时要执行的操作
# files 	  安装nginx时要用到的一些文件
# meta        存放一些说明信息的文件
# tasks       核心的配置文件， 具体的任务操作文件
# template    通常存一些配置文件, 启动脚本等模板文件
# vars        通常为定义的变量文件

```




* 按照官方建议生成目录结构 （在管理端执行）
```shell

cd /etc/ansible

mkdir -p nginx_install/roles/{common,delete,install}/{handlers,files,meta,tasks,templates,vars}


# 文件夹作用说明
# common  	为安装nginx做一些准备配置操作
# delete  	删除nginx的操作
# install  	安装nginx的操作

# 以上的每个目录下 又有几个目录
# handlers    标识当发生改变时要执行的操作
# files 	  安装nginx时要用到的一些文件
# meta        存放一些说明信息的文件
# tasks       核心的配置文件， 具体的任务操作文件
# template    通常存一些配置文件, 启动脚本等模板文件
# vars        通常为定义的变量文件

```

