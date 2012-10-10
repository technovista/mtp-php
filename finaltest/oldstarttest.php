

<?php
//echo "subtestid:".$_POST['subtesttype'];
//exit();
include("connect.php");
session_start();

if(!isset($_SESSION['user']))
{
	header("location:Login.php");
	
} 
$testobjid=$_POST['subtesttype'];
function insertdata($testobjid)
{
	
	$qry=mysql_query("select distinct questionanswerid from questionanswerrelationship qar,questionbank qb where qar.questionid=qb.questionid and qar.testObjectiveid='".$testobjid."'") or die(mysql_error());
	while($rs=mysql_fetch_array($qry,MYSQL_BOTH))
	{   //echo $cnt=mysql_num_rows($qry);
		$ctestname="test".$testobjid;
		$ctestid=$testobjid;
		$ctestqarid=$rs['0'];
		$ctestobjid=$testobjid;
		$insqry=mysql_query("insert into customtest values ('".$ctestid."','".$ctestname."','".$ctestqarid."','".$ctestobjid."')") or die(mysql_error());
	}
}
insertdata($testobjid);
function testque_ans($testobjid)
{
$dis.='<html>
<head>
<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<form action="try.php" method="post">
<table>';

$outqry=mysql_query("select * from questionbank where questionid in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID='".$testobjid."')")or die(mysql_error());
while($outerrs=mysql_fetch_array($outqry,MYSQL_BOTH))
{
	$cnt=mysql_num_rows($outqry);
	$dis.='<tr>
	<td colspan="3" class="topic_heading">4.&nbsp;Test Started:'.$outerrs['3'].'</td></tr>';
	
	$innerqry=mysql_query("select qar.*,qb.*,ab.* from questionanswerrelationship qar, questionbank qb, answerbank ab where  qar.questionid=qb.questionid and qar.answerid=ab.answerid and qar.testObjectiveid='".$testobjid."' and qar.questionid='".$outerrs['0']."'") or (mysql_error());
	$cnt1=mysql_num_rows($innerqry);
	echo "<br>";
	$dis.='<tr><td colspan="3">2.1&nbsp;Question:'.$outerrs['0']."out of".$cnt.'</td></tr>';
	$dis.='<tr><td colspan="3">'.$outerrs['4'].'</td></tr>';
	while($innerrs=mysql_fetch_array($innerqry))
	{
			if(($outerrs['0'])==$innerrs['1'])
			{
				$dis.='<tr><td colspan="3"><input type="radio" name="ans" option='.$innerrs['16'].'>'.$innerrs['17'].'</td></tr>';
			}
	}
	
	} 


$dis.='<tr><td><input type="reset" name="cancel" value="2.3&nbsp;Cancel" class="btn">
			<input type="submit" name="Next" value="2.2&nbsp;Next" class="btn"></td>
</table></form></body></html>';
echo $dis;
}
testque_ans($testobjid);
?>

