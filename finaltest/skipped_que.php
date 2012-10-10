<html><head><link href="stylesheet.css" rel="stylesheet" type="text/css"/></head>
<?php
include("connect.php");
session_start();
if(!isset($_SESSION['user']))
{
	header('location:Login.php');
}
$_SESSION['totalskip'];

$que=$_SESSION['totalskip'];

$q1=(substr_replace($que," ",0,1));

$cnt=1;
if(isset($_GET['page']))
{
	$page=$_GET['page'];
}
else
{
	$page=1;
}
$qry=mysql_query("select distinct qb.* from questionbank qb,questionanswerrelationship qar,customtest_log cl where qb.questionId=qar.questionId and qar.questionAnswerId in ($q1)  and cl.customtest_id =(select max(customtest_id) from customtest_log)")or die(mysql_error());
$totrows=mysql_num_rows($qry);
$per_pages=1;
$total_pages=ceil($totrows/$per_pages);
$x=($page-1)*$per_pages;

$qry1=mysql_query("select distinct qb.* from questionbank qb,questionanswerrelationship qar,customtest_log cl where qb.questionId=qar.questionId and qar.questionAnswerId in ($q1)  and cl.customtest_id =(select max(customtest_id) from customtest_log) limit $x,$per_pages")or die(mysql_error());
$dis.='<body><table width="80%" border="1">';

while($rs=mysql_fetch_assoc($qry1,3))
{
	$dis.='<tr>
	<td colspan="3" ><p>Que:--&nbsp;'.$page."&nbsp;".nl2br($rs['questionDescripton']).'</p></td></tr>';
	$cnt=$cnt+1;
	
	$inqry=mysql_query("select distinct ab1.answerId,ab1.choice from answerbank ab1,questionanswerrelationship qar1,customtest_log cl  where ab1.answerId=qar1.answerId and cl.answer_given like 'skipped' and qar1.questionId='".$rs['questionId']."'") or die(mysql_error());
	
	//$inqry1=mysql_query("select distinct cl1.answer_given from questionanswerrelationship qar1,customtest_log cl1 where qar1.questionId='".$rs['questionId']."' and cl1.question_answer_id=qar1.questionAnswerId and cl1.customtest_id=(select max(customtest_id) from customtest_log)")or die(mysql_error());
	
	
	while($inerrs=mysql_fetch_assoc($inqry,3))
	{	
		
			
			if($rs['questionType']=='MULTIPLE_CHOICE')
			{
				$dis.='<tr><td colspan="3"><input type="radio" name="" value="'.$inerrs['answerId'].'" >'.$inerrs['choice'].'</td></tr>';
			}  
			
			else
			{
				$dis.='<tr><td colspan="3"><input type="checkbox" name="" value="'.$inerrs['answerId'].'" >'.$inerrs['choice'].'</td></tr>';
			}
	}
	
	$rqry=mysql_query("select distinct choice from answerbank ab,questionanswerrelationship qar,questionbank qb where ab.answerId=qar.answerID and qar.isCorrectAnswer like 'yes' and qar.questionId='".$rs['questionId']."'");
	while($right_ans=mysql_fetch_assoc($rqry))
	{
		$dis.='<tr><td colspan="3">Right Ans:--<b>'.$right_ans['choice'].'<b></td></tr>';
	}
}
/********************************************* BOF PREVIOUS,NEXT,FINISH LINK*****************************************************/
if($page!=1)
{
	$previous=$page-1;
	$dis.="<tr><td><a href='skipped_que.php?page=$previous'>Previous</a></td>";
}
if($page!=$total_pages)
{
	$next=$page+1;
	$dis.="<td><a href='skipped_que.php?page=$next'>Next</a></td></tr>";
	
}

/********************************************* EOF PREVIOUS,NEXT,FINISH LINK*****************************************************/
$dis.='</table></body></html>';
echo $dis;
?>