ansible-playbook介绍和使用方式
========================


## 课程目标：

```

1.  熟悉YAML文件的语法
2.  什么是ansible-playbook, 回顾跟之前了解的Ad-hoc的方式的区别 
2.  了解实际场景中用ansible远程部署服务器的方式
3.  实践到自身实际的管理服务器中，学以致用

```

### 1. YAML语法介绍 

* YAML语法可以简单表达清单、散列表、标量等主要的数据结构
* YAML文件扩展名通常为.yaml或者.yml
* 一定要对齐， 只能使用空格, 不能使用tab

* 范例
```
- hosts: webserver              //定义的主机组，即应用的主机
  vars:                        //定义变量
    http_port: 80              //此处自定义变量中变量写在yaml文件，也可写在invetory文件中
    max_clients: 200
  remote_user: root      //远程用户为root
  tasks:                               //执行的任务
  - name: install apache use latest version   //下面即将执行的步骤名称
    apt: pkg=apache2 state=latest
  
```

* 验证YAML文件语法
```python

# 用python验证
python -c 'import yaml, sys; print yaml.load(sys.stdin)' < myyaml.yml


# ansible原生功能验证
ansible-playbook  test.yaml --syntax-check  #检查语法
ansible-playbook test.yaml --list-task       #检查tasks任务
ansible-playbook test.yaml --list-hosts      #检查生效的主机
ansible-playbook test.yaml --start-at-task='Copy http.conf'     #指定从某个task开始运行

```




### 2. 什么是Ansible-playbook

- **主要用于较为复杂、定时执行的任务，用特定的方式将task进行统一的管理，其中管理方式由YAML文件定义**

* playbook由如下部分组成
    1、tasks：任务，即调用模块完成的操作
    2、variables：变量
    3、templates：模板
    4、handlers：处理器，当条件满足时执行操作，通常前面使用notify声明。
    5、roles：角色，分门别类管理playbook的一种方式。




### 3. Ansible-playbook配置过程中一些要点的介绍 

- **hosts和users介绍**

```

---                             #表示该文件是YAML文件，非必须
- hosts: webserver               #指定主机组，可以是一个或多个组。
  remote_user: root                #指定远程主机执行的用户名


# 可以为每个任务定义远程执行用户：
---
- hosts: mysql
  remote_user: root             
  tasks:
    - name: test connection
      ping:
      remote_user: mysql          #指定远程主机执行tasks的运行用户为mysql

- hosts: mysql
  remote_user: root            
  become: yes                  #2.6版本以后的参数，之前是sudo，意思为切换用户运行
  become_user: mysql          #指定sudo用户为mysql


# 制定远程主机sudo切换用户
- hosts: mysql
  remote_user: root            
  become: yes                  #2.6版本以后的参数，之前是sudo，意思为切换用户运行
  become_user: mysql          #指定sudo用户为mysql

```




















