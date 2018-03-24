Jenkins使用的实际场景和高阶应用
===============================================



### Jenkins自定义邮件发送内容
```

Jenkins自动构建发送邮件<br/><hr/>

项目名称：$PROJECT_NAME<br/><hr/>

构建编号：$BUILD_NUMBER<br/><hr/>

项目源码版本：${SVN_REVISION}<br/><hr/>

构建状态：$BUILD_STATUS<br/><hr/>

触发原因：${CAUSE}<br/><hr/>

构建日志地址：<a href="${BUILD_URL}console">${BUILD_URL}console</a><br/><hr/>

```



### 访问 Jenkins 的全局变量的地址
```
http://jenkins服务器地址:8080/env-vars.html

```



### 运行 Jenkins 的 agent 来启动节点的方式

```
jenkins 主节点     A机器：    192.168.1.109  （java环境）
jenkins分节点1：   B机器      192.168.1.103  （lamp环境）

1. 要添加A和B之间的信任，首先保证 B机器要安装 java 的环境
2. 在界面配置 B 机器， 添加进 node 节点
3. 实践配置分发构建



注意： 
1. 2.8 版本之后的 jenkins 都需要 java 1.8 版本以上才能运行， 增加了一定的配置复杂性， 无论是 主节点还是从节点， java的环境都需要1.8以上

2. 如果不想太过折腾， 目前推荐使用 jenkins 2.49 版本
```













