基于jenkins和maven的springboot项目持续集成实践
===============================================



### linux上maven环境配置
```
wget http://mirror.bit.edu.cn/apache/maven/maven-3/3.5.2/binaries/apache-maven-3.5.2-bin.tar.gz
tar -zxf apache-maven-3.5.2-bin.tar.gz

#设置到系统的path
export PATH=/opt/apache-maven-3.5.2/bin:$PATH

#测试安装
mvn -v
```



### linux上用maven构建测试项目步骤
```
cd /opt/devops-springboot
mvn clean package

#运行
cd target
java -jar  spring-all-1.0-SNAPSHOT.jar
#或者指定其他端口
#java -jar spring-all-1.0-SNAPSHOT.jar --server.port=8000
```


假如 mvn clean package 失败， 针对 jdk1.7版本的话（java8的话7替换成8），要重新安装完整java 的包，用下面的命令
```
apt-get install openjdk-7-jdk openjdk-7-doc openjdk-7-jre-lib
```













