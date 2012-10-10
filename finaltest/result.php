<?php
include("connect.php");
session_start();
if(!isset($_SESSION['user']))
{
	header('location:Login.php');
}

$dis.='<html><head><link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>';
$resqry=mysql_query("select * from customtest_log where customtest_id='".$_SESSION['ctestid']."' group by question_answer_id")or die(mysql_error());
$totalque=mysql_num_rows($resqry);
$totskipp='0';
$totcorrect='0';
$totwrong='0';
//$skipque=" ";
//$wrongque="";

while($rltrs=mysql_fetch_assoc($resqry))
{
	$f=0;
	$q=mysql_query("select questionType from questionbank where questionid in(select questionId from questionanswerrelationship where questionAnswerid='".$rltrs['question_answer_id']."')") or die(mysql_error());
	$qrs=mysql_fetch_row($q);
	
		if($qrs['0']=='MULTIPLE_SOLUTIONS')
		{
			
			$totalrow=mysql_query("select count(question_answer_id) from customtest_log where question_answer_id='".$rltrs['question_answer_id']."'")or die(mysql_error());
			$total=mysql_fetch_row($totalrow);
			$innqry=mysql_query("select * from customtest_log where question_answer_id='".$rltrs['question_answer_id']."'")or die(mysql_error());
			
			while($innrs=mysql_fetch_assoc($innqry))
			{
				if($innrs['IsCorrect']=='1')
				{
					$f=$f+1;
					
				}
				
			}
			
			if($f==$total['0'])
			{
				$totcorrect=$totcorrect+1;
					
			}
			elseif ($rltrs['Isskip']=='1')
			{
				$totskipp=$totskipp+1;
				$skipque=$skipque.",".$rltrs['question_answer_id'];
			}
			else
			{
				$totwrong=$totwrong+1;
				$wrongque=$wrongque.",".$rltrs['question_answer_id'];
				
				
			}
		}
		else 
		{
			if($rltrs['Isskip']=='1')
			{
				$totskipp=$totskipp+1;
				$skipque=$skipque.",".$rltrs['question_answer_id'];
			}
			elseif ($rltrs['IsCorrect'] =='1')
			{
				$totcorrect=$totcorrect+1;
			}
			else
			{
				$totwrong=$totwrong+1;
				
				$wrongque=$wrongque.",".$rltrs['question_answer_id'];
				
			}

		}	
		//$totwrong=(totalque-($totcorrect+$totskipp));
}
//echo "total is skipped:-".$skipque;
$_SESSION['totalwrong']=$wrongque;
$_SESSION['totalskip']=$skipque;
$dis.='<table class="result"><tr><td colspan="2" class="topic_heading">Test Result</td></tr><tr><td>Total No Of Questions:</td><td>'.$totalque.'</td></tr>
<tr><td>Total Right Answer:</td><td>'.$totcorrect.'</td></tr>
<tr><td>Total Skipped:</td><td><a href="skipped_que.php">'.$totskipp.'</a></td></tr>
<tr><td>Total Wrong:</td><td><a href="wrong_que.php">'.$totwrong.'</a></td></tr></table></body></html>';
echo $dis;
//echo "insert into testresult (user_id,customtest_id,CntCorrectAnswers,CntIncorrectAnswers) values('".$_SESSION['testtakerid']."','".$_SESSION['ctestid']."','$totcorrect','$totwrong')";
$intqry=mysql_query("insert into testresult (user_id,customtest_id,CntCorrectAnswers,CntIncorrectAnswers) values('".$_SESSION['testtakerid']."','".$_SESSION['ctestid']."','$totcorrect','$totwrong')")or die (mysql_error());
?>