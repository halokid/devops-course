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

# 信任webmin的安装，添加PGP密钥，先下载再添加
wget http://www.webmin.com/jcameron-key.asc   
apt-key add jcameron-key.asc

# 安装
sudo apt install webmin 

# 如果系统开启了防火墙，要开放10000端口，添加iptables规则即可
iptables -A INPUT -p tcp --dport 10000 -j ACCEPT
iptables -A OUTPUT -p tcp --sport 10000 -j ACCEPT

# 访问，这里注意webmin系统默认只允许使用HTTPS（可配置不使用，但是不推荐），使用的是签名，所以对于浏览器
来是不受信任的证书，这个可以略过
# 一般如果没有强制的要求，可以不用需要支持有效证书
https://192.168.1.103:10000

# 服务的开启停止
service webmin start/stop



```

### 配置Webmin

```

# 更改主机名，查看主机名是否正确
# -- 默认首页点击相关信息即可进行修改


# 更新和查看系统时间
# -- 默认首页点击相关信息即可进行修改


# 更改webmin的主题，语言，时区等
# 语言
Webmin --> Webmin Configuration ---> Language and Locale

# 主题
Webmin --> Webmin Configuration ---> Webmin Themes


# 访问控制
Webmin --> Webmin Configuration ---> IP Access Control

# 更改端口
Webmin --> Webmin Configuration ---> Ports and Addresses

```


### 使用Webmin

```

# 查看系统信息，资源状态， 进程等

# 查看系统日志

# 更新配置软件包

# 管理系统用户和用户组

# 运行系统命令

# 管理系统文件



```











