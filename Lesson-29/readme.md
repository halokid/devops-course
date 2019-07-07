使用docker安装zabbix并配置监控
--------------------------------


## 课程目标

```
1. 复习docker的使用
2. 使用docker安装zabbix
3. 配置使用zabbix，监控主机信息
```



### 复习docker的部署方式

- docker最大的特点是打包应用以及所有运行时依赖包到一个轻量级、可移植的容器中，方便部署和移植，不依赖于环境
- 容器是完全使用沙箱机制，计算机资源相互之间是隔离的，可以跟OS层友好分离
- 性能好，系统开销很低，本质上是系统进程


### 有兴趣的同学可以先尝试常规安装zabbix
https://www.zabbix.com/documentation/4.2/manual/installation/install_from_packages/debian_ubuntu


### 安装zabbix
首先保证好我们已正确安装好docker， 可以查看之前的docker的教程。

```shell

# zabbix需要mysql，先运行mysql服务实例


# 保存myql的数据文件
mkdir -p /data/zabbix/mysql
chown -R mysql.mysql  /data/zabbix/mysql


# 运行mysql容器
docker run --name mysql-server -t \
      -e MYSQL_DATABASE="zabbix" \
      -e MYSQL_USER="zabbix" \
      -e MYSQL_PASSWORD="zabbix_pwd" \
      -e MYSQL_ROOT_PASSWORD="root_pwd" \
      -v /data/zabbix/mysql:/var/lib/mysql \
      -d mysql:5.7

      
      
# 运行zabbix server容器
docker run --name zabbix-server-mysql -t \   
      -e DB_SERVER_HOST="mysql-server" \
      -e MYSQL_DATABASE="zabbix" \
      -e MYSQL_USER="zabbix" \
      -e MYSQL_PASSWORD="zabbix_pwd" \
      -e MYSQL_ROOT_PASSWORD="root_pwd" \
      --link mysql-server:mysql \
      -p 10051:10051 \                          
      -d zabbix/zabbix-server-mysql:latest
      
      

# 运维zabbix web管理端容器
docker run --name zabbix-web-nginx-mysql -t \
      -e DB_SERVER_HOST="mysql-server" \
      -e MYSQL_DATABASE="zabbix" \
      -e MYSQL_USER="zabbix" \
      -e MYSQL_PASSWORD="zabbix_pwd" \
      -e MYSQL_ROOT_PASSWORD="root_pwd" \
      --link mysql-server:mysql \
      --link zabbix-server-mysql:zabbix-server \
      -p 80:80 \
      -d zabbix/zabbix-web-nginx-mysql:lates
      
      

# 在192.168.1.129上面运行zabbix agent
docker run --name zabbix-agent \
    -e ZBX_HOSTNAME="devops.test.com" \
    -e ZBX_SERVER_HOST="192.168.1.103" \
    -e ZBX_METADATA="devops" \
    -p 10050:10050 \
    --privileged \
    zabbix/zabbix-agent:latest


      
# 检查各个容器运行情况是否正常
ps -ef | grep docker

ps -ef | grep zabbix

ps -ef | grep nginx


# 访问zabbix的web端
http://192.168.1.103:8080


# 实际上如果安装传统的方式来安装zabbix，是需要安装较多的中间件，并且进行配置的，现在使用docker来
# 部署安装，大大简化了安装流程，而且比传统的安装方式还多很多好处，传统的安装zabbix的方式




docker run --name zabbix-agent -p 10050:10050  --network-alias zabbix-agent -e ZBX_HOSTNAME="zabbix-server-mysql" -e ZBX_SERVER_HOST="zabbix-sever-mysql" -e ZBX_SERVER_PORT=10051 -d zabbix/zabbix-agent





```



### 配置zabbix

```shell

# 更改界面的显示语言为中文
点击 User， 选择用户配置选项


# 默认的Admin用户是超级管理员，日常使用需要添加一些其他用户并且分配权限


# 添加要监控的主机 192.168.1.129


# 配置监控主机的监控项， 触发器， 图形等


# 配置监控仪表盘


```




### 总结

- zabbix配置较为复杂， 而且菜单选项很多，不是那么的直观，作为传统的监控方式比较实用
- 在云计算的时代， zabbix略显繁琐，而且运维成本比较大，如果是主机众多，维护配置要付出不少人力时间
- 尝试新的监控方式，更适合云计算时代
















