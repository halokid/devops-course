用Docker封装、运行和修改应用
========================


###1.  准备容器的开发环境
```

mkdir devops_docker
cd devops_docker
mkdir webapp
cd webapp


```



### 2. 创建项目代码
index.php
```

<html>
<head>
    <title>Hello world!</title>
</head>
<body>
    <?php if($_ENV["HOSTNAME"]) {?><h3>我的主机名称是 <?php echo $_ENV["HOSTNAME"]; ?></h3><?php } ?>
</body>
</html>


```


### 3, 编写Dockerfile文件
```

FROM hub.c.163.com/r00txx/lamp
COPY index.php /var/www/html/


```



### 4. 构建容器镜像
```

cd webapp
docker build -t my-webapp:v1 .

# 查看构建好的镜像
docker images


# 运行构建好的容器镜像
docker run -itd -p 8090:80 my-webapp:v1

# 访问运行的容器应用并查看效果
http://localhost:8090

```



### 5. 修改代码并重新把应用打包进容器并运行
```


重复迭代上面的流程， 没一个应用的版本就相当于 docker  image 镜像的一个 tag， 我们来尝试制作应用的V2版本



```









