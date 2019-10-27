# 33课-promethues监控告警的配置使用






### 课程目标

```
1. 了解promethues的监控告警的技术原理
2. 了解promethues的告警配置
3. 明白告警在监控平台的重要性是不可或缺的
4. 基于课程的实践基础，根据自身的情况丰富更多的监控告警方式
```



### 没有告警的监控平台都是没有理想的咸鱼

- 告警是监控平台必不可少的一环，是重中之重，天天盯着屏幕看数据展示是没有灵魂的
- 告警策略可以理解为数据的**阀值**，那什么是阀值呢？比如你一天最多只能吃10碗饭，超过10碗你就要“涨肚子难受”了，这个10就是一个阀值，“涨肚子难受”就是告警的一个现象，记住是**现象**。**所以监控告警的本质是数据触发，而不是现象触发。**
- 日常运维中的告警手段通常有电话，邮件，短信，微信（其他的即时通讯工具，例如钉钉）
- 告警可触发更进一步的对象自动治愈功能，不一定需要人工参与，全自动化




### promethues的监控技术原理和架构？
- 告警触发（服务端），服务端统一储存各监控对象的数据，根据告警策略配置触发
- 告警发送（服务端），服务端根据告警配置项发送告警信息到指定的远程服务（altermanager)，这个远程服务我们称它为 告警管理服务
- 告警处理（告警管理端），告警管理端也就是 altermanager， 根据配置项处理发送过来的告警信息， 目前比较流行的处理方式是**webhook**



### 什么是webhook？
- 顾名思义， 翻译过来就是 web钩子， 是一种通用的处理方式，意思就是 给某一个配置项勾住（绑定）一个基于HTTP的请求，一旦该配置项触发，则请求定义好的HTTP链接
- webhook的具体数据就是一个URL，也就是HTTP地址
- 很多开源的产品都支持webhook，比如jenkins， gitlab， ansible等等




### 开始运行alertmanager（告警管理端）容器
首先保证好我们已正确安装好docker， 可以查看之前的docker的教程。
修改原来的docker-compose.yml 文件，增加我们的 altermanager 容器启动配置

```shell

vim docker-compose.yml
# ---------------------------
 alertman:
    image: prom/alertmanager:latest
    volumes:
      - ./alertmanager.yml:/etc/alertmanager/alertmanager.yml
    ports:
      - '9093:9093'
      
# docker-compose up -d 更新我们的配置
          
```


> 提示: docker-compose内容器的通信原理是怎样的？？

> 思考一下，如果我们不是用docker-compose统一在一个系统里部署，那么这些组件的通信地址会有什么变化吗？

> 对于我们实战的这种场景， alertman 就相当于 alertmanager 的通信地址，所以如果分开部署的话，很明白我们的其他配置项假如要配置 altermanager 的地址，那么就得用真实的通信地址去代替 alertman 



- 告警管理端的配置文件解释

```shell
# 文件名 alertmanager.yml， 这个文件名可以自己定义

alertmanager.yml内容
# ---------------------------------------
global:
  resolve_timeout: 5m   		
    
receivers: 
- name: my-test-alert
  webhook_configs:
  - url: http://:172.17.0.1:8089/proms/alert
    send_resolved: false

route:
  receiver: my-test-alert
  group_by: ['nodeos']
  group_wait: 10s
  group_interval: 10s
  repeat_interval: 1m
  routes:
  - receiver: my-test-alert
    match_re:
      severity: warnging 
      
inhibit_rules:
  - source_match:
      alertname: 'node_time:sum:error'
    target_match:
      alertname: 'node_netstat_Icmp_InMsgs:error'
    equal: ['nodeos','severity']


# global 表示全局设置
# resolve_timeout 当告警的状态从刚“修复”好开始计算，要经过多长时间，才宣布告警解除。因为有一些监控指标会有波动，比如cpu， 内存， 网络等有波动都是正常的， 这个配置可以理解为 “病好了多久才算你真的已经健康了”这么个意思


#  ----------------------- 关键点：接收告警策略配置（策略应该怎么做） ----------------------------
# receivers 定义由“谁”来接收告警。至于接收告警之后，“谁”做什么，那就是“谁”自己的事了啊，跟我告警管理端没关系
# name  就是定义一个名称，方便使用（一会你就明白了）
# webhook_configs 定义了“谁”的类型是什么，  webhook_config就表示“谁”的类型是webhook，也就是一个http的url地址， 当然这个url是能访问得到的才能正常收到告警数据
# 这个“谁”还支持其他的方式，比如常见的email， IM通讯工具（比如微信）等等，具体可以参考官网查阅：https://prometheus.io/docs/alerting/configuration/
# send_resolved 表示当告警解除了，也就是说问题解决了，是否需要通知，因为当我们收到一个告警的时候，通常都会跟踪下去的，所以这个通常没必要


# ---------------------- 关键点：接收告警处理路由配置（选择什么策略） ------------------------------
# route 告警触发之后，告警处理方式的入口总配置（路由的概念类似于我们做web开发MVC模式路由的概念）

# receiver 定义选择什么策略来处理告警（用到了上面定义的策略名称）

# group_by 告警按照什么来作为标签分组，比如我们定义一个叫nodeos

# group_wait 假如有一组新的告警触发了，延时多长时间才发现（也就是说出了事之后，多久才发告警出去通知“谁”我出事了），比如第一次是nodeos的CPU出事了，出事多久才发发送告警

# group_interval 假如有一组的初始告警已经发送过了，延时多长时间才允许再发送该组新产生的其他报警，比如第二次事nodeos的内存出事了，要等发CPU出事的告警经过多久之后，才允许发内存出事的告警

# repeat_interval 如果警报已经成功发送，间隔多长时间才允许重复发送，这个要设置，不然接收告警的“谁”会吃不消的，因为处理不过来

# routes 子节点的配置， 如果父节点找不到对应配置的receiver, 那么会在子节点找是否有匹配的，
# 注意： 父， 子节点都有一个continue选项， 如果能在当前层找到匹配的receiver，并且为false时不会就不会在往下一层找，否则就会不断的找下一层，如果你定义了下一层的话（这个比较绕，只能在实际应用中加深理解了）

# match_re 用于匹配label，通常为warnging，不用过多解读


# ---------------------- 知道即可的配置 --------------------------
# inhibit_rules 抑制告警项配置，因为我们本次测试的是整个node挂掉的告警，所以配置抑制选项是为了告诉proms，当我们这个告警选项触发的话，node上的其他告警没必要再告诉我拉，比如node的网络不通，node上的数据库挂掉了，因为node（节点）本身都挂了，网络肯定是不通的， node上面的数据库肯定是挂掉的了，没必要再废话一遍告诉我拉

# source_match 配置根据label匹配的源数据，就是说当这个值不对劲的时候就触发告警
# target_match 配置根据label匹配目的告警，就是说上面的告警的，这些配置的告警类型出事了就不用再告诉我了
# equal 就是告诉抑制的告警类型的label名称（就是组名）必须一样，然后级别都是 “严重”级的


```



### 编写处理的webhook

```python
# webhook就是一个能提供http服务的url地址


# python基于flask编写一个http请求服务端
# flask是一个轻量级的web开发框架，能快速编写web服务，快手快速简单

# -*-  coding: utf8 -*-

import time
from flask import Flask

app = Flask(__name__)

@app.route("/proms/alert")				# flask路由的装饰器
def promsAlert():
  t = time.time()
  localTime = time.localtime(t) 
  st = time.strftime("%Y-%m-%d %H:%M:%S", localTime)
  return "[收到告警] -------- 时间为" + st

if __name__ == "__main__":
  # app.run()
  app.run(port=5000, debug=True)


```



### 验证整个的告警流程

```shell

# 停掉node监控节点， 直接停止node的容器即可，这样proms就收不到node的监控信息了

# 检查 prom服务端 的alert页面是否有提示，刷新页面即可见到提示信息

# 查看我们的webhook链接是否收到 告警服务端 发送来的告警信息


```


### 完善我们的webhook程序

#### 收到告警之后， webhook应该做下面的动作

1. 把告警信息通知需要的对象，通知方式通常是email，IM工具等
2. 如果告警信息是一些比较标准化， 常规的告警， webhook可以调用一些检查程序，进行详细检查，比如收到cpu告警，就启动检查cpu占比信息收集的程序，并把收集到的信息，反馈给需要通知的对象
3. 假如再刚一点，我们甚至可以直接用webhook调用自动告警处理的程序，但是这个不推荐，毕竟危险系数有点大，系统有很多我们意料不到的情况



















