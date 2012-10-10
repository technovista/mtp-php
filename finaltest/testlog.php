<?php

include("connect.php");
session_start();
if(!isset($_SESSION['user']))
{
	//echo "not set";
	header('location:Login.php');
	
}
$postData = $_POST;
$questionid=$postData['questionid'];
$que_ans_id=$postData['que_ansid'];
$answerid=$postData['Answerid'];
$ans=trim($answerid);
$ansid1=str_replace(" ","|",$ans);
$ans1=intval($ansid);
$ansid=explode("|",$ansid1);
//print_r($ansid);

$l=sizeof($ansid);

$testobjid=$postData['testobjid'];
$customtestid=$postData['customtid'];
$_SESSION['ctestid']=$postData['customtid'];

$q=mysql_query("select questionType from questionbank where questionid='".$questionid."'")or die(mysql_error());
$qt=mysql_fetch_row($q);
$qtype=$qt['0'];

for($i=0;$i<$l;$i++)
{
	// $ansid[$i]."<br>";
	$_SESSION['answergiven']=$ansid[$i];
	$qry=mysql_query("select distinct(answerId) from questionanswerrelationship where questionid='".$questionid."' and testObjectiveid='".$testobjid."' and questionanswerid='".$que_ans_id."' and isCorrectAnswer like 'yes' and answerId='".$ansid[$i]."' group by questionid")or die(mysql_error());
	mysql_num_rows($qry);
	
	if(mysql_num_rows($qry)>0)
	{
		while($cans=mysql_fetch_array($qry))
		{
		
			$cans['0'];
			if($qtype=='MULTIPLE_SOLUTION')
			{				
				if((intval($ansid[$i]))==$cans['0'])
				{
					//echo "value matched for maultiple solution<br>";
					$f=1;
				}
				if(($f=='1') and ($qtype=='MULTIPLE_SOLUTION'))
				{
					$iscorrect='1';
					$isskipped='0';
				}
				else
				{
					echo "wrong answer<br>";
				}
							
			}
			else
			{
				$iscorrect='1';
				$isskipped='0';
			}
		
		
			
		}
	}else
	{
		//echo "else excecuted";
		if($ansid[$i]=="skipped")
		{
				$isskipped='1';	
				$iscorrect='0';
		}
		else
		{
				$iscorrect='0';
				$isskipped='0';		
		}
	}


$iqry=mysql_query("insert into customtest_log(customtest_id,question_answer_id,answer_given,IsCorrect,Isskip) values('".$customtestid."','".$que_ans_id."','".$ansid[$i]."','".$iscorrect."','".$isskipped."')") or die(mysql_error());
}

?>