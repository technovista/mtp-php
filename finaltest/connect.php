<?php

error_reporting(E_ALL ^(E_NOTICE | E_WARNING));
$server="localhost";
$user="root";
$pwd="";
$db="jrrohit_testprep1";
$con=mysql_connect($server,$user,$pwd) or die(mysql_error());
mysql_select_db($db,$con);


/*error_reporting(E_ALL ^(E_NOTICE | E_WARNING));
$server="dbmysql200.my-hosting-panel.com:3306";
$user="jrroh_testprep1";
$pwd="Password123";
$db="jrrohit_testprep2";
$con=mysql_connect($server,$user,$pwd) or die(mysql_error());
mysql_select_db($db,$con); */

/*
error_reporting(E_ALL ^(E_NOTICE | E_WARNING));
$server="localhost";
$user="root";
$pwd="";
$db="jrrohit_testprep1";
$con=mysql_connect($server,$user,$pwd) or die(mysql_error());
mysql_select_db($db,$con);
--> */

?>