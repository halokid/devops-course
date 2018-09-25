使用Ansible管理部署web应用实例
========================


## 课程目标：

```

1.  熟悉常用的ansible远程管理服务器的模块, apt, yum等
2.  了解ansible管理远程服务器的用户， 用户组的使用方式
2.  了解实际场景中用ansible远程部署服务器的方式
3.  实践到自身实际的管理服务器中，学以致用

```

### 1. 显示Ansible的可用模块，以及帮助文档

- **显示所有可用模块**

```
ansbile-doc -l
  
    1、ping模块 
    2、raw模块 
    3、yum模块 
    4、apt模块 
    5、pip模块 
    6、synchronize模块 
    7、template模块 
    8、copy模块 
    9、user 模块与group模块 
    10、service 模块 
    11、get_url 模块 
    12、fetch模块 
    13、file模块 
    14、unarchive模块 
    15、command 模块和shell
  
```


- **以apt模块为例，获取该模块的HELP说明**

```
  ansbile-doc apt
  
  关于ansible模块帮助文档的一些讲解， 根据apt显示的HELP信息，解读帮忙信息的意思
  
```




### 2. 实践部署的web应用的拓扑架构

- **inventory文件的定义**

```
www.example.com
 
[webserver]
192.168.1.229 

 
[dbserver]
192.168.1.109
  
```





### 3. 配置webserver 和 dbserver 的软件环境

- **安装webserver所需的软件并配置**

```
# 安装 php， apache， php-mysql 等环境

假如我们是在webserver的OS上， 我们应该直接运行 
apt-get install apache2


通过 ansible 远程执行此任务， 命令为
ansible webserver -m apt -a "pkg=apache2 state=latest"


# 安装PHP相关软件
apt-get install php5   
apt-get install php5-mysql    
apt-get install php5-gd    
apt-get install libapache2-mod-php5  



```


### 4. 配置webserver服务的用户和用户组

- **安装webserver所需的软件并配置**

```
# 配置ansible启动apache的用户和用户组
# 使用 user 模块
ansible webserver -m user -a "name=appuser shell=/bin/bash groups=admins, appgroup 
append=yes, home=/home/appuser/ state=present"


# 看实际情况修改用户属组， 演示修改的命令为
ansible webserver -m user -a "name=appuser groups=appgroup append=no"


# 修改用户属性
1. 为安全考虑，需要修改用户的过期时间，以便定时核查用户属性
linux计算UNIX时间戳命令为 date +%s
ansible webserver -m user -a "name=appuser  expire=UNIX时间戳


# 删除用户属性， 删除appuser用户， 并删除其用户目录
ansible webserver -m user -a "name=appuser state=absent remove=yes"


# 改变用户密码
# 设置appuser用户密码为 abcd12345

# 首先要将要修改的密码进行加密
openssl passwd -1 -salt 'abcd12345'


# 修改用户密码指令
ansible webserver -m user -a "name=appuser shell=/bin/bash password= update_password=always"

# 验证用户是否生效
ansible webserver -m raw -a 'tail /etc/passwd|grep appuser'

```



# 5. 启动webserver环境并验证环境是否成功

```
# 启动webserver上的apache



# 验证启动的服务

```




# 6. 完成dbserver的配置，实践一次上面的流程



















