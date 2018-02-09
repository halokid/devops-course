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
```


