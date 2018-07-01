<?php
//$con = mysql_connect("127.17.0.2:3306","root","123456");
$con = mysql_connect("192.168.1.109:33061","root","123456");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

/**
if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }
**/


/**
mysql_select_db("my_db", $con);
$sql = "CREATE TABLE Persons
(
FirstName varchar(15),
LastName varchar(15),
Age int
)";
**/


mysql_select_db("my_db", $con);
$sql = "insert into Persons set Firstname='test', Lastname='abc', Age=18";

mysql_query($sql,$con);

mysql_close($con);



mysql_close($con);

?>