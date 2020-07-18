ELK部署配置实战一
==========================


### 介绍另外的一个采集工具fluentd
回顾一下logstash、beats（filebeat）
- logstash， java实现，要跑在jvm上， 资源消耗比较大，用docker来部署不太合适，因为docker里面也需要跑一个jvm，也耗资源
- beats（filebeat），golang实现，资源消耗比较小，更轻量，适合用docker来部署，但是还是需要把数据传到logstash，数据才能到Elasticsearch
- fluentd， CRuby实现， 关键性能组件用C语言实现，资源消耗小，整体性能较好，适合用docker来部署，可直接跟Elasticsearch数据通讯，不需要部署logstash
我们用docker来部署ELK， 所以用fluentd来做日志收集的工具最最合适的




### ELK收集web server日志的实战
- 准备如下的docker镜像
  - Apache HTTP server
  - Fluentd
  - Elasticsearch
  - Kibana

  
  
- 下载配置docker images

Apache HTTP server
```shell
# Apache http server
docker pull httpd:latest
```



Fluentd

```shell
# 首先编辑fluented的配置文件
# fluentd/conf/fluent.conf
<source>
  @type forward
  port 24224
  bind 0.0.0.0
</source>
<match *.**>
  @type copy
  <store>
    # 把收集的数据储存在es
    @type elasticsearch
    # 配置es的地址
    host elasticsearch
    port 9200
    logstash_format true
    logstash_prefix fluentd
    logstash_dateformat %Y%m%d
    include_tag_key true
    type_name access_log
    tag_key @log_name
    flush_interval 1s
  </store>
  <store>
    @type stdout
  </store>
</match>


# Fluentd
docker pull fluent/fluentd:latest
# 需要配置fluentd 的elasticsearch， 数据才可以直接通信到elasticsearch


# 编辑Dockerfile， 重新生成安装好插件的 fluented镜像
# ------------------------------------

FROM fluent/fluentd:latest
RUN ["gem", "install", "fluent-plugin-elasticsearch", "--no-rdoc", "--no-ri", "--version", "1.9.2"]

# ------------------------------------

# 重新build docker image, 并打上elk的标签
cd fluentd     # Dockerfile的当前目录
docker build -t fluent/fluentd:elk . 

```



Elasticsearch

```shell

docker pull elasticsearch:latest

```


Kibana

```shell

docker pull kibana:latest

```



- pull好images之后，聊聊ELK运行的架构

  - 我们的实战架构，为了方便教学部署， 所有的容器都是运行在同一台OS上，所以ELK的组件都跑在同一个OS上
  - 同一台OS跑docker，我们回顾下以前的课程，知道可以用docker-compose文件来处理最方便
  
  


- 编辑docker-compose.yaml

```shell

version: '2'
services:
  web:
    image: httpd:latest
    ports:
      - "80:80"
    links:
      - fluentd
    logging:
      driver: "fluentd"
      options:
        fluentd-address: localhost:24224
        tag: httpd.access

  fluentd:
    image: fluent/fluentd:elk
    volumes:
      - ./fluentd/conf:/fluentd/etc
    links:
      - "elasticsearch"
    ports:
      - "24224:24224"
      - "24224:24224/udp"

  elasticsearch:
    image: elasticsearch:latest
    expose:
      - 9200
    ports:
      - "9200:9200"

  kibana:
    image: kibana:latest
    links:
      - "elasticsearch"
    ports:
      - "5601:5601"
      
      
 # ---------- 备注 -----------------
 web容器的logging driver选项是表示 web容器的日志配适器，表示web容器的日志应该发给谁的意思。
 web容器的options tag的内容，我们可以认为这个就是一个储存在es里面的索引值，我们可以根据这个索引值去获取日志内容。
 
 # 运行
 docker-compose up -d
      
```



- 访问http server，生成http access日志
```shell

# 测试 200 返回日志
repeat 2 curl http://localhost:80


# 测试 404 返回日志
repeat 2 curl http://localhost:80/hello

#  200, 404 都是http的状态码， 200表示HTTP状态正常， 404表示HTTP找不到相关的资源（可以认为没有相关的页面）
#  推荐看一本书， 图解HTTP协议
#  课后建议好好了解一下json的格式， json这种数据格式呢现在比较流行，是需要了解的

```




- 配置Kibana
```shell
# 访问 http://192.168.1.113:5601/

# 配置索引匹配模式，Index name or pattern， 这里输入*，我们匹配所有日志

# 查看日志详细内容，理解日志的属性数据内容

# 进行日志数据检索

# 利用Dev Tools 进行操作
https://www.elastic.co/guide/en/kibana/current/console-kibana.html

# 范例：
GET _search
{
  "query": {
    "match": {
      "container_name": "/elk_web_1"
    }
  }
}
```












