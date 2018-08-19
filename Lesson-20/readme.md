Ansible的安装配置和使用
========================


课程目标：
--------------------
```

1.  学习安装配置Ansible, 了解Ansible的各种安装方式
2.  理解Ansible自动化运维的原理， 理解linux系统配置互相信任的配置
3.  动手安装，配置，使用Ansible


```


### 1. 安装Ansible 
```
#若你希望使用Ansible的最新版本, 并且使用linux系统，建议用系统软件包管理器来安装

#若想追求最新的特性，跟进开发版本，可以源码安装

#Ansible管理主机的方式
管理主机的方式为  管理端 + 客户端
管理端:   需要安装Ansible, python版本需要2.6以上
客户端:   需要支持ssh登陆，python版本需要2.5以上

#对安装主机(管理端主机)的要求
只要机器上安装了 Python 2.6 或 Python 2.7 (windows系统不可以做控制主机),都可以运行Ansible,主机的系统可以是 Red Hat, Debian, CentOS, OS X, BSD的各种版本等等

自2.0版本开始,ansible使用了更多的句柄来管理它的子进程,所以OSX系统需要打开更多的句柄，输入命令 sudo launchctl limit maxfiles 1024 2048,否则你可能会看见”Too many open file”的错误提示.


#从源码安装
--------------------------------------------------------------------------------

首先安装pip
sudo easy_install pip

再安装相应的python模块
sudo pip install paramiko PyYAML Jinja2 httplib2 six

关键的python模块为 paramiko， 此模块的用途主要是可远程通过SSH协议来登陆不同的操作系统，并且推送操作系统执行的命令

演示运行python的 paramiko 模块的sample代码， 这个是ansile远程执行管理的机器命令的本质

$git clone git://github.com/ansible/ansible.git --recursive
$cd ./ansible
$source ./hacking/env-setup

-------------------------------------------------------------------------------



#通过软件包安装， 以debian 8.x位例子， 软件包方式安装
--------------------------------------------------------------------------------

$ sudo apt-get install software-properties-common
$ sudo apt-add-repository ppa:ansible/ansible
$ sudo apt-get update
$ sudo apt-get install ansible

--------------------------------------------------------------------------------




#通过pip来安装
--------------------------------------------------------------------------------

$ sudo pip install ansible
注：pip方式安装不会在/etc/ansible目录下生成默认的相关配置文件


--------------------------------------------------------------------------------


```



### 2. 开始使用Ansible
```
管理端主机（控制主机）：     192.168.0.103

客户端主机（被控制主机）：     192.168.0.229


#配置管理主机
$vim /etc/ansible/hosts ， 在最末尾添加进客户端IP   192.168.0.229


#Ansible需要管理端主机 和 客户端主机之间是互相信任的， 所以要配置控制主机的SSH公钥， 把控制主机的公钥拷贝到客户端主机
在控制端主机

#生成公钥
ssh-keygen -t rsa

#拷贝公钥到客户端主机
ssh-copy-id -i root/.ssh/id_rsa.pub root@ 192.168.0.229

注：ssh-copy-id命令会自动将id_rsa.pub文件的内容追加到远程主机root用户下.ssh/authorized_keys文件中。


#检查管理端机器的  /root/.ssh/id_rsa.pub， 这个是 管理端机器的公钥

#检查客户端机器的  /root/.ssh/authorized_keys


#Ansible的优化配置,  vim /etc/ansible/ansible.cfg
------------------------------------------------------------------------------

1> 禁用每次执行ansbile命令检查ssh key host
host_key_checking = False

2> 开启日志记录
log_path = /var/log/ansible.log

------------------------------------------------------------------------------


#开始测试Ansible的配置， 由管理端主机发起一个ping命令到客户端主机
在管理端
ansible all -m ping
返回 SUCCESS 即表示我们的配置成功， Ansible正常使用。

```





