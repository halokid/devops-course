docker-compose和docker之间应用的访问
========================


课程目标：
--------------------
```

1. 了解docker 镜像的ENTRYPOINT、CMD、RUN的属性， 了解它们的区别，掌握如何修改这两个属性的方法。
2. 进一步完善上一节课的实践目标， 成功正确裁剪mysql， apache-php容器， 并且成功启动，编写通信验证程序。

```




使用方式：

RUN在Dockerfile构建镜像的过程(Build)中运行，在buid镜像的时候被放到镜像去。所以RUN都是出现在 Dcokerfile里面
ENTRYPOINT和CMD在容器运行(run、start)时运行。

简略要点：
1. ENTRYPOINT和CMD指令都可以设置容器启动时要执行的命令，但用途略有不同的。对于一个Docker镜像来说，ENTRYPOINT指令或CMD指令，至少必有其一。
2. RUN 的原理是执行命令并创建新的镜像文件层，RUN 经常用于安装软件包。
3. CMD 设置容器启动后默认执行的命令及其参数，但 CMD 能够被 docker run 后面跟的命令行参数替换。
4. ENTRYPOINT 配置容器启动时运行的命令。


###1.  它们的定义形式
```
# 总得来说他们的定义方式都大同小异，有shell形式 和 exec形式两种

RUN：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]



ENTRYPOINT：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]



CMD：

	shell形式
	RUN command param1 param2

	exec形式
	RUN ["executable", "param1", "param2"]

    参数形式
    CMD ["param1","param2"]

```














