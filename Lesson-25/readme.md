真实场景使用ansible-playbook日常运维nginx
========================


## 课程目标：

```

1.  了解nginx，以及其实际场景中的部署、更新等操作 
2.  学习安装部署、配置nginx 
3.  实践利用ansible-playbook下发nginx及远程维护 
4.  实践到自身实际的服务器中，学以致用
```



### 1. NGINX介绍及通过ansible-playbook安装

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
# 下载nginx源码包
wget http://nginx.org/download/nginx-1.4.4.tar.gz

# 进行配置检查
./configure --prefix=/usr/local/nginx --with-http_realip_module --with-http_sub_module   
                                      --with-http_gzip_static_module --with-http_stub_status_module  
                                      --with-pcre

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
        start-stop-daemon --stop --quiet --exec $DAEMON
        echo "$NAME."
        ;;
  restart|force-reload)
        echo -n "Restarting $DESC: "
        start-stop-daemon --stop --quiet  --exec $DAEMON
        sleep 1
        start-stop-daemon --start --quiet  --exec $DAEMON -- $DAEMON_OPTS
        echo "$NAME."
        ;;
  reload)
      echo -n "Reloading $DESC configuration: "
      start-stop-daemon --stop --signal HUP --quiet --exec $DAEMON
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




* 打包nginx并生成压缩包拷贝 （在管理端执行）
```shell

cd /usr/local/

tar czvf nginx.tar.gz nginx

cp nginx.tar.gz /etc/ansible/nginx_install/roles/install/files/

cp /etc/init.d/nginx /etc/ansible/nginx_install/roles/install/templates/

# 说明：把安装文件放于 install/files/ 目录下，把启动脚本放于install/templates/ 目录下。



```



* 定义相关的yaml文件 （在管理端执行）
```shell

# 1. 定义common的tasks（这里主要是定义nginx需要安装的一些依赖包）
# -------------------------------------------------------

cd /etc/ansible/nginx_install/roles/

vim common/tasks/main.yml 

---

- name: Install initializtion require software

  yum: name={{ item }} state=installed

  with_items:
    - gcc
    - libpcre3 
    - libpcre3-dev 
    - openssl 
    - zlib1g 
    - zlib1g-dev


# --------------------------------------------------------



# 2. 定义install的vars
# ---------------------------------------------------------

vim install/vars/main.yml


nginx_user: nobody

nginx_basedir: /usr/local/nginx

# 注意： 这个 nginx_user 要和 nginx.conf 配置文件中定义的用户一样。
# 还可以定义一些其他的变量比如
# nginx_port: 80
# nginx_web_dir: /data/www
# nginx_version: 1.4.4

# ---------------------------------------------------------



# 3. 定义install的tasks
# ---------------------------------------------------------

# 拷贝分发文件到客户端
 vim install/tasks/copy.yml
 
 - name: Copy Nginx Files
   copy: src=nginx.tar.gz dest=/tmp/nginx.tar.gz owner=root group=root
  
 - name: Uncompress Nginx Files
   shell: tar zxf /tmp/nginx.tar.gz -C /usr/local/

 - name: Copy Nginx Scripts
   template: src=nginx dest=/etc/init.d/nginx owner=root group=root mode=0755
  
  
  
# 在客户端安装nginx
vim install/tasks/install.yml

- name: Create Nginx User
  user: name={{ nginx_user }} state=present createhome=no shell=/sbin/nologin

- name: Start Nginx Service
  # service: name=nginx state=started
  shell: /etc/init.d/nginx start

- name: Add Boot Start Nginx Service
  shell: insserv /etc/init.d/nginx

- name: Delete Nginx compression files
  shell: rm -rf /tmp/nginx.tar.gz
  
   
# task汇总文件的写法， 先执行 copy， 再执行install，通过定义文件的顺序执行task
vim install/tasks/main.yml

- include: copy.yml

- include: install.yml

# ---------------------------------------------------------




# 4. 定义整个 ansible-playbook 的总入口文件
cd /etc/ansible/nginx_install/

vim install.yml

---

- hosts: nginxhosts
  remote_user: root
  gather_facts: True
  
  roles:
    - common
    - install
```




* 执行下发（在管理端执行）
```shell

# 验证Iventory文件是否正确

cat /etc/ansible/hosts
修改之前的group名称为我们这次实践的名称

# 测试一下主机的状态
ansible nginxhosts -m ping

# 执行下发命令
ansible-playbook install.yml


# 在客户端验证下发结果（在客户端执行）

ls /usr/local/nginx/

ps -ef |grep nginx

cat /etc/init.d/nginx

netstat -anp | grep nginx

  
```



### 2. 日常运维更新nginx

* 新建拷贝配件文件（在管理端执行）
```shell

cat /etc/ansible

# 建立配置更新nginx的相关role文件
mkdir -p nginx_config/roles

cd nginx_config/roles/
mkdir -p new/{vars,files,tasks,handlers}

# 配置相关文件， new是表示更新nginx的时候用到的文件
cp /usr/local/nginx/conf/nginx.conf new/files

# 为了实践效果，我们定义一个vhosts文件夹
# 当nginx需要配置多个域名服务的时候，一般需要用到vhosts文件夹里面的多个配置文件做配置
mkdir -p /usr/local/nginx/conf/vhosts
cp -r /usr/local/nginx/conf/vhosts new/files

  
```



* 定义一些变量（在管理端执行）
```shell

vim new/vars/main.yml

nginx_basedir: /usr/local/nginx

  
```



* 更新nginx之后，重新启动nginx服务（在管理端执行）
```shell

vim new/handlers/main.yml

- name: restart nginx
  shell: /etc/init.d/nginx reload

  
```




* 汇总所有tasks任务文件（在管理端执行）
```shell

vim new/tasks/main.yml


- name: copy conf file
  copy: src={{ item.src }} dest={{ nginx_basedir }}/{{ item.dest }} backup=yes owner=root group=root mode=0644
  with_items:
    - { src: nginx.conf, dest: conf/nginx.conf }
    - { src: vhosts, dest: conf/ }
  notify: restart nginx

  
```




* 定义入口文件 （在管理端执行）
```shell

cd ..

vim update.yml

---
- hosts: nginxhosts
  user: root
  roles:
  - new

  
```




* 测试更新nginx配置和重启服务 （在管理端执行）
```shell

vim roles/new/files/vhosts/test_domain.conf

# 1. 写上示例的文本内容，模拟配置nginx虚拟主机范例， 详细的nginx vhosts文件配置可在之后详细了解
#testdomain.devops.com


# 2. 发布更新nginx 
ansible-playbook update.yml 
  
  
# 3. 在客户端查看是否已经更新了配置
cat /usr/local/nginx/conf/vhosts/test_domain.conf


```



###  3. 课程总结

通过今天的课程实践，可知正确的使用 ansible-playbook可以轻松远程部署，管理nginx的日常运维，多个

nginx节点只需要一套配置文件，执行脚本即可，方便实用，按照ansible-playbook官方推荐的文件结构形式

，条理分明，运维耦合度比较低，非常实用，熟练之后，可一人轻松管理N台nginx，其他的运维工作借鉴这

种方式即可。













