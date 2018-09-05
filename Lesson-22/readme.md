Ansible使用Ad-Hoc方式的场景和实践
========================


## 课程目标：

```

1.  了解ansibe执行任务方式的区别
2.  理解Ad-Hoc的方式适合于什么使用场景
3.  学习Ad-Hoc的使用方法，并在课程的相关场景中顺利实践

```

### 1. Ansible执行任务的方式

- **Ad-Hoc**

```
  可以简单的理解为“临时命令",多用于一些临时的，单次的，较简单的执行任务场景。命令是ansible
```


- **Ad-Hoc的使用场景范例**


```
情景1：
电商系统大促活动完成之后， 我们需要关闭已经不需要的临时使用来抗压的服务器， 并且之后需要对系统平台进行一次健康检查

情景2:
临时更新某些中间件的配置文件， 比如mysql，然后同时将修改好后的配置文件分发到其他需要更新配置的服务器上

以上两个场景都有一个特点就是，时间是不固定的， 任务的操作是定制化的， 然后也比较简单，不会太复杂。
```

- **Ad-Hoc命令集用法**

```
Ad-Hoc命令是由/usr/bin/ansible实现的， 也就是由安装好的 ansible的命令路径实现
命令格式为  ansible <host-pattern> [option]

支持的参数如下:
-v,--verbose:输出更详细的执行过程信息,-vvv可得到执行过程所有信息。
-i PATH,--inventory=PATH:指定inventory信息,默认/etc/absible/hosts。
-f NUM,--forks=NUM:并发线程数,默认5个线程。
--private-key=PRIVATE_KEY_FILE:指定密钥文件。
-m NAME,--module-name=NAME:指定执行使用的模块。
-M DIRECTORY,--module-path=DIRECTORY:指定模块存放路径,默认/usr/share/ansible,也可以通过
   ANSIBLE_LIBRARY设定默认路径。
-a 'ARGUMENTS',--args='ARGUMENTS':模块参数。
-k,--ask-pass SSH:认证密码。
-K,--ask-sudo-pass sudo:用户的密码(--sudo时使用)。
-o,--one-line:标准输出至一行。
-s,--sudo:相当于Linux系统下的sudo命令。
-t DIRECTORY,--tree=DIRECTORY:输出信息至DIRECTORY目录下,结果文件以远程主机名命名。
-T SECONDS,--timeout=SECONDS:指定连接远程主机的最大超时,单位是秒。
-B NUM,--background=NUM:后台执行命令,超NUM秒后中止正在执行的任务。
-P NUM,--poll=NUM:定期返回后台任务进度。
-u USERNAME,--user=USERNAME:指定远程主机以USERNAME运行命令。
-U SUDO_USERNAME,--sudo-user=SUDO_USERNAME:使用sudo,相当于Linux下的sudo命令。
-c CONNECTION,--connection=CONNECTION:指定连接方式,可用选项paramiko(SSH)、ssh、local,local
    方式常用于crontab和kickstarts。
-l SUBSET,--limit=SUBSET:指定运行主机。
-l ~REGEX,--limit=~REGEX:指定运行主机(正则)。
--list-hosts:列出符合条件的主机列表,不执行任何命令。

```



- **Ad-Hoc使用实践**

```
管理端主机： 192.168.1.103 

客户端主机1:   192.168.1.229 

客户端主机2:   192.168.1.109


# 配置主机的互相信任


# 配置Inventory文件


# 检查配置文件是否成功， 主机连接情况是否正常
ansible webserver -f 5 -m ping


# 可打印最详细的执行过程， 理解Ad-Hoc执行任务的底层流程, 此指令是返回webservre所有主机的hostname
ansible  webserver -s -m command -a 'hostname' -vvv



```




- **Ansible命令执行流程**

```

1. ansible发起指令， 即是要执行的任务的命令

2. 管理端主机连接客户端主机

3. 生成执行任务的临时目录， 目录包含有要执行的命令的脚本

4. 再客户端OS执行命令， 运行执行命令的脚本

5. 执行完毕之后， 返回执行结果给管理端主机

6. 结束命令执行流程

```



- **Ad-Hoc具体使用场景**

```

情景1:  批量查看webserver组所有主机的磁盘容量， 这个需要使用command模块

执行命令,command模块默认就带,不用-m参数来指定：    
ansible webserver -a "df -lh"

执行结果解释：
SUCCESS表示命令执行成功,rc=0表示ResultCode=0,即命令返回结果,返回码为0,表示命令执行成功
>>后面跟的内容就是执行的命令实际返回的信息，相当于在本地执行df -lh后的结果返回。

----------------------------------------------------------------------------------



情景2:  批量查看webserver主机组的全部主机的内存使用情况， 这个使用shell模块

执行命令：    
ansible webserver -m shell -a "free -m"

执行结果解释：
SUCCESS表示命令执行成功,rc=0表示ResultCode=0,即命令返回结果,返回码为0,表示命令执行成功
>>后面跟的内容就是执行的命令实际返回的信息，相当于在本地执行df -lh后的结果返回。

----------------------------------------------------------------------------------



```


- **思考的问题**

```
1.  假如一个主机组包含10台服务器， ansible针对主机组执行任务的时候， 主机组内的主机是否是按照顺利来执行任务的？我们
    尝试往webserver主机组添加添加多个主机来调试下。
    输出结果不会完全按照webserver的主机顺序来返回的， 原因是ansible推送多个主机命令的时候，是多线程的方式来推送的，
    执行过程没有进程线程锁去严格按照主机的顺序来执行，所以返回的结果是无序的。但是假如执行的对象少于3个主机的话，顺序
    会正常， ansible默认是开3个线程同时去处理执行任务， 假如超过3个， 会存在各个线程抢任务执行的情况， 因为抢任务有
    快慢之分， 所以超过3个有可能会乱序返回结果。

```











