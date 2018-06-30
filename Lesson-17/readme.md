整合docker容器为统一的应用
========================


课程目标：
--------------------
```

1.  编写程序联合不同容器的应用，作为一个统一的程序，部署交付
2.  编写前面提到的 docker-composer 的用法， 作为交付的统一管理方式
3.  目前的容器都是在 同一台主机上， 引出假如在不同主机上的容器，怎么通信？怎么联合部署？
4.  docker部署应用之后， 传统的运维问题如何解决？ 比如日志监控这个首要问题

```


### 1.  继续上节课留下的问题
```
# 首先运行我们上节课已经成功配置好的mysql镜像

#启动mysql
docker run -itd --privileged=true  -v /opt/mysql-files:/var/lib/mysql-files -p 33061:3306  --name my-mysql my-mysql:v1


#制作正确的 apache-php镜像

#Dockerfile
-------------------------------------------

FROM my-webapp:v1

ENTRYPOINT ["/bin/sh", "-c", "/etc/init.d/apache2 start && /usr/sbin/sshd -D"]

-------------------------------------------


docker build -t my-apache-php:v1 -f Dockerfile_my-apache-php .

#查看新制作的apache-php镜像


#启动apache-php镜像
docker run -itd   -p 8089:80  --name my-apache-php my-apache-php:v1


#至此，两个镜像配置成功， 正确执行

```

### 2.进入容器内编写程序， 进行通信
```
****
# 先进入apache-php， 检查跟mysql 容器的通信
ping mymysql    #直接ping mysql容器的名字

ifconfig   #查看IP地址

telnet 172.17.0.2 3306

telnet 192.168.1.109 33061

总结来看， 同一个主机的容器怎么通信呢？？
容器不同的应用，因为容器的不同应用映射到本机的不同端口, 所以可以用本机的IP来通信


# 进入apache-php 的容器，检查mysql连接扩展是否正常
访问 http://192.168.1.109:8089/info.php ， 检查mysql扩展


# 编写程序， 为mysql添加数据库， 数据表， 编写查询数据



# 把我们写的程序文件 与 容器分离， 程序源码不应该放在容器里面，应该在容器运行的时候，传入容器内



# 编写常规的 docker-composer 部署文件， 使程序源码， 容器进程，整体应用都一并部署成功



# 回顾课程开头提到的思考问题



# 容器成功部署应用之后， 基本的日志监控这种运行怎么做？



```





























