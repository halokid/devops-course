Ansible的安装配置和使用
========================


课程目标：
--------------------
```

1.  学习安装配置Ansible, 了解Ansible的各种安装方式


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
首先安装pip
sudo easy_install pip

再安装相应的python模块
sudo pip install paramiko PyYAML Jinja2 httplib2 six

$git clone git://github.com/ansible/ansible.git --recursive
$cd ./ansible
$source ./hacking/env-setup        


```





