使用Webmin通过web高效管理服务器
--------------------------------


## 课程目标

```
1. 了解Webmin的作用，有什么功能，能做什么事。
2. 在debian下（ubuntu一样）学习安装，配置，使用Webmin
3. 在自己的真实运维服务器中使用Webmin，进行真正的日常管理
```



### 什么是Webmin

Webmin是一个针对主机的web控制面板，就是你可以通过web方式去管理你的主机，不用再像传统的方式那样的登陆操作，它
提供简单直观的界面，配合常用的运维服务器的功能，可达成高效管理服务器的作用，目前只支持linux主机。



### 安装Webmin
webmin包含很多组件和运行环境，推荐使用apt-get的方式来安装，这样可以跟系统的兼容性更好，也比较方便安装配置

```shell

# 加入webmin的源
/etc/apt/sources.list
# 加入最后一行
deb http://download.webmin.com/download/repository sarge contrib


```









