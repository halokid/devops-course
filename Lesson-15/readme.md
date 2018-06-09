自定义docker镜像文件挂载
========================


课程目标：
--------------------
```

了解如何为应用需求自定义docker镜像的属性， 了解docker的Volume的工作原理，进行相关实践操作加深了解

```



### 1.  继续上一节课没完成的事，因此引出docker Volume
```
#启动一个mysql
docker run -itd -e MYSQL_ROOT_PASSWORD=xx -e MYSQL_DATABASE=xx -e MYSQL_USER=xx -e MYSQL_PASSWORD=xx --name mysql my-webapp:v1


#启动一个apache-php
docker run -itd --link db -p 8090:80  --name apache-php my-webapp:v1


#查看镜像启动的log，发现启动的问题


```



### 2. Docker Volume 的工作原理
       首先要知道Docker的文件系统是如何工作的。Docker镜像是由多个文件系统（只读层）叠加而成。当我们启动一个容器的时候，Docker会加载只读镜像层并在其上添加一个读写层。如果运行中的容器修改了现有的一个已经存在的文件，那该文件将会从读写层下面的只读层复制到读写层，该文件的只读版本仍然存在，只是已经被读写层中该文件的副本所隐藏。当删除Docker容器，并通过该镜像重新启动时，之前的更改将会丢失。在Docker中，只读层及在顶部的读写层的组合被称为Union File System（联合文件系统）。
       也就是说当docker启动一个容器的时候， 在容器里面进行的任何文件的修改都是临时的，当关闭容器之后， 都会丢失。为了解决数据持久化的问题， 所有有了Volume的概念，简单来说，Volume就是目录或者文件，它可以绕过默认的联合文件系统，而以正常的文件或者目录的形式存在于宿主机上。
       Volume要注意下面几点:
        一、容器目录不可以为相对路径
        二、宿主机目录如果不存在，则会自动生成
        三、即便容器销毁了，新建的挂载目录不会消失

      课后实践， 可以尝试下，假如不指定 -v 的第二个参数会发生什么情况？




### 3.解决我们目前的问题吗， 先成功挂载mysql的数据目录
```

1.  查看 image 的 volume属性，因为启动的时候， volume是以root身份来挂载的，所以不建议直接用image的 volume，建议在容器启动的时候， 用 -v参数来指定容器运行时 volume， 首先修改image 的 mysql 的datadir目录，指向非image挂载的  volume 目录，授予目录mysql权限

# vim /etc/mysql/my.cnf
# 修改  datadir 目录为   /var/lib/mysql-files

2.  学习两个参数  -v(指定容器运行时volume),   --privileged(特权)

3.  启动容器观察是否正常  docker run -itd -e MYSQL_ROOT_PASSWORD=xx -e MYSQL_DATABASE=xx -e MYSQL_USER=xx -e MYSQL_PASSWORD=xx --name mysql --privileged=true  -v /opt/mysql-files:/var/lib/mysql-files  my-webapp:v1

4. 把容器commit为新的image， 作为mysql的镜像

5. 更完善的一步，  修改 docker 的 ENTRYPOINT 属性

```





