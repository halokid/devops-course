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




使用方式：

RUN在Dockerfile构建镜像的过程(Build)中运行，在buid镜像的时候被放到镜像去。所以RUN都是出现在 Dcokerfile里面
ENTRYPOINT和CMD在容器运行(run、start)时运行。

简略要点：
1. ENTRYPOINT和CMD指令都可以设置容器启动时要执行的命令，但用途略有不同的。对于一个Docker镜像来说，ENTRYPOINT指令或CMD指令，至少必有其一。
2. RUN 的原理是执行命令并创建新的镜像文件层，RUN 经常用于安装软件包。
3. CMD 设置容器启动后默认执行的命令及其参数，但 CMD 能够被 docker run 后面跟的命令行参数替换。
4. ENTRYPOINT 配置容器启动时运行的命令。


### 1.  它们的定义形式
```
# 总得来说他们的定义方式都大同小异，有shell形式 和 exec形式两种

RUN：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]



ENTRYPOINT：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]



CMD：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]

    参数形式
    CMD ["param1","param2"]

```

### 2.shell形式和exec的形式的区别
```
shell形式提供了默认的指令/bin/sh -c，所以其指定的command将在shell的环境下运行，而exec却不一定

是在用sh来运行的， 可以选择其他的linux shell来运行，比如bash， zsh等。shell形式还有一个严重的问

题：由于其默认使用/bin/sh来运行命令，如果镜像中不包含/bin/sh，容器会无法启动。


```



### 3.重载机制
```

Dockerfile中只有最后一个CMD指令会生效，其他会被重载。

Dockerfile中只有最后一个ENTRYPOINT指令会生效，其他会被重载。

CMD指定的命令可以被docker run传递的命令覆盖。

如CMD ["echo"]会被docker run --rm binss/test echo test中的echo覆盖，最终输出test。

ENTRYPOINT指定的命令不会被docker run传递的命令覆盖。容器名后面的所有内容都当成参数传递给其指定的命令。

如ENTRYPOINT ["echo"]最终输出echo test。echo test都被当做是ENTRYPOINT指定的指令——echo的参数。

当然，ENTRYPOINT指定的命令并不是不能重载的，只需指定--entrypoint来重载即可

```

### 4. 正确的使用方法
```
应该根据运行时机选择RUN还是ENTRYPOINT和CMD，根据实际需要选择使用shell形式还是exec形式，并尝试组合

ENTRYPOINT和CMD来达到组合两者的效果

```


### 5. 回到我们原来的问题上

```
查看我们原来镜像的配置， 修改我们原来的配置， 以使镜像更加正确

# docker inspect image

两种方式去更改image的ENTRYPOINT， CMD 等属性
1.  直接修改image的属性，生成新的image
2.  image运行时指定这两个属性

建议采取第一种方式， 这样便于管理我们的镜像， 让镜像一目了然。

开始创建两个 Dockerfile， 分别创建一个mysql， 一个apache-php 的镜像

```


my-apache-php Dockerfile

```

FROM my-webapp:v1
ENTRYPOINT ["/bin/sh", "-c", "/etc/init.d/apache2 start && /usr/bin/sshd -D"]

```


my-mysql Dockerfile

```

FROM my-webapp:v1
ENTRYPOINT ["/bin/sh", "-c", "/etc/init.d/mysql start && /usr/bin/sshd -D"]

```

生成镜像
docker build -t image_name -f Dockfile_name .



### 6. 运行已经配置好的镜像，测试通信交互

```

#启动mysql
docker run -itd --privileged=true  -v /opt/mysql-files:/var/lib/mysql-files -p 33061:3306  --name my-mysql my-mysql:v1


#启动apache-php
docker run -itd --link db -p 8090:80  --name  my-apache-php my-apache-php:v1


```




### 7. 检查容器是否启动正常， 开始进入容器进行通信测试

```
apt-get update

apt-get install telnet

telnet my-mysql 3306

```














































