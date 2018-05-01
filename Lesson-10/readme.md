代码审查和代码测试覆盖率
===============================================



### Pylint
```
安装
pip install pylint   或    apt-get install pylint   （Ubuntu，debian等系统）

检查安装
pylint --version


指定分析某个文件：
pylint --rcfile=pylint.conf Person.py


指定分析某个项目文件夹：
cd  project_path;
pylint -r n project_path



分析报告的解读：
C表示convention，规范；
W表示warning，告警；

pylint结果总共有四个级别：
error，warning，refactor，convention
可以根据首字母确定相应的级别。1, 0表示告警所在文件中的行号和列号。



```





###nosetests测试代码覆盖率

```
安装 nose 请查看课程9的github地址


使用：
nosetests --with-coverage --cover-package=project1


修改Person 类的测试用例，查看输出报告的变化， 加强对测试代码覆盖率的理解


报告范例如下：

Name                    Stmts   Miss  Cover   Missing
-----------------------------------------------------
devops_project              0      0   100%   
devops_project.Person      10      3    70%   11, 14, 17
-----------------------------------------------------
TOTAL                      10      3    70%   
----------------------------------------------------------------------
Ran 1 test in 0.001s

OK




```












