Ansible的Iventory配置和执行任务的方式
========================


## 课程目标：

```

1.  了解ansibe管理主机的方式，管理多台主机的时候，应该如何配置
2.  熟悉ansible的Iventory的概念，思考有需要用到Iventor的一些管理多主机的配置手段

```

### 1. Ansible管理主机的方式 

- **给主机分组和一些配置**

可以通过下面的配置给主机分组， 可以方便地归类我们的主机， 配置/etc/ansible/hosts 文件， 这个文件也叫inventory文件，意思就是这个文件是存放ansible要管理的计算机资源清单的

```
  www.abc.com

  [webservers]
  xxx.abc.com
  yyy.abc.com
  kkk.abc.com

  [dbservers]
  one.abc.com
  two.abc.com
  three.abc.com
  a.abc.com


  # 方括号[]中是组名,用于对系统进行分类,便于对不同系统进行个别的管理.
  # 下面的都是属于组内的主机，配置文件的格式与ini配置文件格式类似。
  # 一个OS可以同时属于不同的组，比如有一台主机  a.example.com, 可以同时放在webserver组， 也可以放在dbserver组。


  假如OS的ssh不是22端口， 则可以这样写，   a.example.com:7380， 这样写表现主机  a.example.com 的ssh端口是 7380
  
  
  一组相似的 hostname , 可简写如下:
  [webservers]
  www[01:50].example.com                  # 用数字也可以用字母[a:z]


  也可以通过下面的方式去定义主机的别名:
  test_server ansible_ssh_port=5555 ansible_ssh_host=192.168.1.50
  
  
  对于每一个host，都可以单独定义连接的类型和连接的用户名:
  [targets]
  localhost              ansible_connection=local
  other1.example.com     ansible_connection=ssh        ansible_ssh_user=admin1
  other2.example.com     ansible_connection=ssh        ansible_ssh_user=admin2
  
 

```



- **主机变量**

```
有时候为了方便管理主机， 我们会给一些主机定义某些变量

[host_group]
host1 http_port=80 max_requests=1000
host2 http_port=8080 max_requests=900


```




- **组的变量**

```

也可以定义属于整个组的变量:

[host_group]
host1
host2

[host_group:vars]
appname=mobile_abc
max_connect=800

```



- **把一个组作为另一个组的子成员**

```
[group1]
host1
host2

[group2]
host3
host4

[group3]
group1
group2

```


- **常用的Iventory参数说明**

```
# 将要连接的远程主机名.与你想要设定的主机的别名不同的话,可通过此变量设置.
ansible_ssh_host


# ssh端口号.如果不是默认的端口号,通过此变量设置.
ansible_ssh_port


# 默认的 ssh 用户名
ansible_ssh_user


# ssh 密码(这种方式并不安全,我们强烈建议使用 --ask-pass 或 SSH 密钥)
ansible_ssh_pass


# sudo 密码(这种方式并不安全,我们强烈建议使用 --ask-sudo-pass)
ansible_sudo_pass


# sudo 命令路径(适用于1.8及以上版本)
ansible_sudo_exe (new in version 1.8)


# 与主机的连接类型.比如:local, ssh 或者 paramiko.
ansible_connection


# ssh 使用的私钥文件.适用于有多个密钥,而你不想使用 SSH 代理的情况.
ansible_ssh_private_key_file


# 目标系统的shell类型.默认情况下,命令的执行使用 'sh' 语法,可设置为 'csh' 或 'fish'.
ansible_shell_type


# 目标主机的 python 路径.适用于的情况: 系统中有多个 Python, 或者命令路径不是"/usr/bin/python"
# 比如个别Unix系统, 与 ansible_python_interpreter 的工作方式相同,可设定如 ruby 或 perl 的路径
ansible_python_interpreter


# 一个主机的例子
host1           ansible_ssh_port=9999     ansible_ssh_user=testadmin
host2           ansible_ssh_private_key_file=/home/.ssh/aws.pem
host3           ansible_python_interpreter=/usr/local/bin/python
host4           ansible_ruby_interpreter=/usr/bin/ruby.1.9.3

```


- **可先入门使用ansible的使用， 有兴趣再去了解Inventory的一些高级特性， 包括远程装载，API形式提供等**



- **ansible远程执行任务的方式**

```
两种方式
1. Ad-Hoc， 一般用于一些比较简单的任务， 临时操作指令等

2. playbook， 一般用于一些比较复杂的任务， 一些定时， 批量执行的任务


# 简单日常的一般运维， 可以先学习Ad-Hoc的使用


```



















