<?php
 include("connect.php");
 session_start();
 
 if(!isset($_SESSION['user']))
 {
 	//echo "not set";
 	header('location:Login.php');
 }

$dis.='<html>
<head>
<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<form action="subtestlist.php" method="post">
<table>
 		
<tr>
	<td colspan="2" class="topic_heading">2.&nbsp;Test List</td>
</tr>
<tr>
	<td colspan="2">2.1&nbsp;Select one of the test below</td>
</tr>';
$qry=mysql_query("select * from test t, test_user tu,user u where t.testId=tu.testID and tu.userID=u.testTakerId and u.testTakerId='".$_SESSION['testtakerid']."'") or die(mysql_error());
while($rs=mysql_fetch_array($qry,MYSQL_BOTH))
{
 $dis.='<tr><td><input type="radio" name="testtype" value="'.$rs['0'].'">'.$rs['testName'].'</td></tr>';
 
}

$dis.='<tr><td><input type="reset" name="cancel" value="2.3&nbsp;Cancel" class="btn">
			<input type="submit" name="Next" value="2.2&nbsp;Next" class="btn"></td>
</table></form></body></html>';
echo $dis;

?>