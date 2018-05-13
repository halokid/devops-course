Docker安装以及使用
========================


### 版本的选择， docker CE（社区版）， docker EE（企业版），  CE免费， EE收费。


### 安装 docker CE 的步骤
```


#删除旧的版本
sudo apt-get remove docker docker-engine docker.io


#注意内核要3.10以上， 查看内核
uname -r

#添加源
sudo apt-get update

#安装必要的软件
 sudo apt-get install \
     apt-transport-https \
     ca-certificates \
     curl \
     gnupg2 \
     software-properties-common


# 添加 GPG key
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -


# 添加源
sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/debian \
   $(lsb_release -cs) \
   stable"


# 更新源信息
sudo apt-get update

#安装
sudo apt-get install docker-ce


# 下载我们的image

```





