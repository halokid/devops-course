
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

# jenkins持久化要在宿主机上储存数据， 所以要使用容器的 Volume 属性， 先建立储存数据的文件夹
mkdir -p /opt/jenkins_home

# 设置Volume挂载的文件夹的权限， 因为jenkins容器本身是以 uid 为 1000 的用户来启动的
# 所以要在宿主机上赋予文件夹， uid 为 1000 的权限
chown 1000:1000 /opt/jenkins_home

# 开始启动jenkins容器
# jenkins为何要映射两个端口？
docker run -p 8080:8080 -p 50000:50000 -v /opt/jenkins_home:/var/jenkins_home hub.c.163.com/r00txx/jenkins:jimmyset  


```




### 2. 配置Jenkins容器的自动化构建环境
```
# 配置java构建环境


# 关于容器内的权限问题， 容器默认启动的用户和指定用户去启动容器


# 新建Jenkins Job， 使用maven构建springboot项目， Lesson-7课程的源码
 
```








