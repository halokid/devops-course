Docker安装以及使用
========================


### 版本的选择， docker CE（社区版）， docker EE（企业版），  CE免费， EE收费。


### 安装 docker CE 的步骤
```


#删除旧的版本
sudo apt-get remove docker docker-engine docker.io


#注意内核要3.10以上， 查看内核
uname -r

#添加源
sudo apt-get update

#安装必要的软件
 sudo apt-get install \
     apt-transport-https \
     ca-certificates \
     curl \
     gnupg2 \
     software-properties-common


# 添加 GPG key
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -


# 添加源
sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/debian \
   $(lsb_release -cs) \
   stable"


# 更新源信息
sudo apt-get update

#安装
sudo apt-get install docker-ce


# 下载实践的image
docker pull hub.c.163.com/r00txx/debian:latest

# 查看下载的 image 的文件结构
/var/lib/docker


# 使用容器运行Hello World
docker run hub.c.163.com/r00txx/debian /bin/echo 'hello world'

# 与容器进行交互
docker run -t -i hub.c.163.com/r00txx/debian /bin/bash


# 容器中运行持续任务展示管理容器生命周期， 终止就马上停止容器的生命周期
docker run   hub.c.163.com/r00txx/debian /bin/sh -c "while true; do echo hello world; sleep 1; done"


# 容器中运行持续任务展示管理容器生命周期， 容器的后台运行模式， 容器的生命周期在后台持续
docker run  -d  hub.c.163.com/r00txx/debian /bin/sh -c "while true; do echo hello world; sleep 1; done"

# 可以用 docker logs 容器id 来查看
docker logs  {id}


# 查看证明运行的容器
docker ps

# 查看所有的容器， 包括没有在运行的
docker ps -a


# 停止容器
docker stop


# 删除已经停止的容器
docker rm {id}


# 通过容器运行web应用并通过浏览器访问
# 先下载 lamp 的镜像

hub.c.163.com/r00txx/lamp

# 运行
docker run -itd -p 8090:80  hub.c.163.com/r00txx/lamp

# 查看系统的端口
docker ps

# 进入容器修改下网页内容，查看效果
docker exec -it {id} /bin/bash

# 可以在容器内查看 HTTP 访问的日志记录
tail -f /var/log/apache2/access.log

# 查看容器内的进程列表
docker top {id}

```












