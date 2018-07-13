基于Docker容器部署Jenkins
========================


课程目标：
--------------------
```

1.  回顾学习过的容器网络， 文件挂在Volume， 启动命令， 端口指定等知识点
2.  学习用docker来不是一个真实的应用系统
3.  在部署Jenkins的过程中，加深巩固容器特性知识点
4.  回顾Jenkins的使用配置，加深自动化CICD方面的知识点

```


### 1. 拉取Jenkins镜像，查看镜像配置属性
```
#拉取镜像
docker pull hub.c.163.com/r00txx/jenkins:jimmyset

#有时候因为网络原因会拉取失败， 这个时候我们可以下载镜像，然后导入镜像


#导出镜像命令
docker save -o jenkins.tar hub.c.163.com/r00txx/jenkins:jimmyset


#导入镜像命令
sudo docker load --input jenkins.tar

#可百度网盘下载Jenkins的镜像
链接： https://pan.baidu.com/s/1Bwmr79-MIrwURCYDnRXIZg 密码：
密码： 8ixu


#jenkins持久化要在宿主机上储存数据， 所以要使用容器的 Volume 属性， 先建立储存数据的文件夹
mkdir -p /opt/jenkins_home

#设置Volume挂载的文件夹的权限， 因为jenkins容器本身是以 uid 为 1000 的用户来启动的
#所以要在宿主机上赋予文件夹， uid 为 1000 的权限
chown 1000:1000 /opt/jenkins_home

#开始启动jenkins容器
#jenkins的容器会用到8080和50000端口， 其中50000端口是用来与其他系统进行通信用的
docker run -p 8080:8080 -p 50000:50000 -v /opt/jenkins_home:/var/jenkins_home hub.c.163.com/r00txx/jenkins:jimmyset


#先略过一些插件的安装， 加快向导的完成速度


#进入jenkins容器，查看目前的用户身份
whoami

#原因是本身镜像的设置，默认启动的用户就是 jenkins， 查看命令
docker inspect hub.c.163.com/r00txx/jenkins:jimmyset


#关于容器启动的用户问题， 默认权限设置， 最好是提权用root身份来启动，避免一些操作权限问题
docker run -u root -p 8080:8080 -p 50000:50000 -v /opt/jenkins_home:/var/jenkins_home hub.c.163.com/r00txx/jenkins:jimmyset


```




### 2. 配置Jenkins容器的自动化构建环境
```
#配置java构建环境

#检测是否有java环境
java -version

#安装maven
cd /opt
wget http://mirror.bit.edu.cn/apache/maven/maven-3/3.5.2/binaries/apache-maven-3.5.2-bin.tar.gz
tar -zxf apache-maven-3.5.2-bin.tar.gz

#设置到系统的path
export PATH=/opt/apache-maven-3.5.2/bin:$PATH

#测试安装
mvn -v


# 关于容器内的权限问题， 容器默认启动的用户和指定用户去启动容器


# 新建Jenkins Job， 使用maven构建springboot项目， 回顾 Lesson-7课程的实践过程


```








