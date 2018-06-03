docker-compose和docker之间应用的访问
========================


课程目标：
--------------------
```

了解docker集成部署的原理， 了解docker之间通信的底层用法，为后面学习容器集群调度平台打下良好的底层基础，

```



###1.  准备开发环境
```
mkdir my-webapp
cd my-webapp

```



###3. 对镜像进行一些修改， 让里面的PHP支持mysql
```
启动容器

进入容器

安装 php 的 mysql 扩展

重启 apache

最重要的一步， 把新的修改过的 container commit 成一个新的镜像


```



###2. 创建docker-compese文件
```
version: '2'
services:
webapp-db:
    image: my-webapp:v3
    environment:
      MYSQL_ROOT_PASSWORD: xx
      MYSQL_DATABASE: xx
      MYSQL_USER: xx
      MYSQL_PASSWORD: xx

webapp-php:
    depends_on:
    - webapp-db
    image: my-webapp:v3
    links:
    - webapp-db
    ports:
    - "8090:80"
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_PASSWORD: xx

```


###3. 启动容器的部署
```
docker-compose up -d

```


###4. docker-compose集成部署docker的命令相应的 docker run 命令如下
```

docker run -itd -e MYSQL_ROOT_PASSWORD=xx -e MYSQL_DATABASE=xx -e MYSQL_USER=wordpress -e MYSQL_PASSWORD=xx --name db my-webapp:v3

docker run -itd --link db:mysql -p 8090:80 -e WORDPRESS_DB_PASSWORD=xx my-webapp:v3

```


###5. docker-compose管理docker集成部署的一些命令
```

#启动当前部署
docker-comopse start

#停止当前部署
docker-compose stop

#删除当前部署
docker-compose down

#获取当前部署的运行情况
docker-compose ps


```











