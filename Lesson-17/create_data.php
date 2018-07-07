<?php
//$con = mysql_connect("127.17.0.2:3306","root","123456");
$con = mysql_connect("192.168.1.109:33061","root","123456");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

/**
//创建数据库
//如果还没有 my-db 的数据库， 那么这段注释掉，先创建数据库
if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }
**/


///**
//创建数据表
//如果 my-db 的数据库没有数据表 Persons， 那么这段注释掉，先创建数据表
mysql_select_db("my_db", $con);
$sql = "CREATE TABLE Persons
(
FirstName varchar(15),
LastName varchar(15),
Age int
)";
//**/


//添加数据
mysql_select_db("my_db", $con);
$sql = "insert into Persons set Firstname='test', Lastname='abc', Age=18";
mysql_query($sql,$con);


//查询数据
$sql = "select * from Persons";
$rows = mysql_fetch_rows($sql);
print_r($rows)

mysql_close($con);



mysql_close($con);

?>
