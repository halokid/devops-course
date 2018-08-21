Ansible
========================


课程目标：
--------------------
```

1.  了解ansibe管理主机的方式，管理多台主机的时候，应该如何配置

```

### 1. Ansible管理主机的方式 

- **给主机分组**

  可以通过下面的配置给主机分组， 这样就可以方便地归类我们的主机， /etc/ansible/hosts 文件

  ```
  mail.example.com

  [webservers]
  foo.example.com
  bar.example.com
  a.example.com

  [dbservers]
  one.example.com
  two.example.com
  three.example.com
  a.example.com



  # 方括号[]中是组名,用于对系统进行分类,便于对不同系统进行个别的管理.
  # 下面的都是属于组内的主机，配置文件的格式与ini配置文件格式类似。
  # 一个OS可以同时属于不同的组，比如有一台主机  a.example.com, 可以同时放在webserver组， 也可以放在dbserver组。
  ```


​       假如OS的ssh不是22端口， 则可以这样写，   a.example.com:7380， 这样写表现主机  a.example.com 的ssh端口是 7380

​      一组相似的 hostname , 可简写如下:

​       [webservers]
​       www[01:50].example.com                  # 用数字也可以用字母[a:z]



​     也可以通过下面的方式去定义主机的别名:

​       test_server ansible_ssh_port=5555 ansible_ssh_host=192.168.1.50



- **主机变量**

  ​

- **组的变量**



