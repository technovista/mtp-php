<?php
include("connect.php");
session_start();

if(!isset($_SESSION['user']))
{
	header("location:Login.php");
	
} 



//function testque_ans($testobjid)
//{
$dis.='<html>
<head>
<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
// function create GetXmlHttpObject
		var myAjaxPostrequest;
		var testObjId;
		var next;
		var queid;
		var que_ans_id;
		var tobjid;
		var ctid
		var ansid;
function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
	
function submitFormWithAjax(testObjId,next,queid,que_ans_id,ctid){
	//alert("submitFormWithAjax()" +testObjId + "::" + next+"::"+queid+"::"+que_ans_id+"::"+tobjid+"::"+ctid);
	myAjaxPostrequest=GetXmlHttpObject();
		
	//alert("ansid" + document.myform.ansid.value);
	for (var i = 0; i < myform.ansid.length; i++) 
	{   
   		//var v1=document.myform.ansid[i].value;
		//alert(v1);
		if(document.myform.ansid[i].checked==true)
  		{
     		fansid = document.myform.ansid[i].value;
			//alert("fansid"+fansid);
      		break;
   		}
		else
		{
			fansid="skipped";
		}
}
	var parameters="questionid="+queid+"&Answerid="+fansid+"&que_ansid="+que_ans_id+"&testobjid="+testObjId+"&customtid="+ctid;
	
	alert("parameters--:" + parameters);
	myAjaxPostrequest.open("POST", "thankyou.php", true);
	myAjaxPostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	myAjaxPostrequest.send(parameters);
	myAjaxPostrequest.onreadystatechange=function(){
   	if(myAjaxPostrequest.readyState==4)
   	{
		if(myAjaxPostrequest.status==200)
		{
			
			document.getElementById("result").innerHTML=myAjaxPostrequest.responseText;  
  			//document.getElementById("myform").style.display = "none";
		   // alert("ready()" +testObjId + "::" + next);
			document.myform.method = "post";
			//alert("before action");	
			if (next != "finish") {
				document.myform.action = "newfile.php?testobjid=" + testObjId + "&page=" + next +"&queid=" +queid + "&que_ans_id=" + que_ans_id +"&customtid="+ctid;
				alert(document.myform.action);
				document.myform.submit();
			}
			else {
				alert("finish");
				document.myform.action="result.php";
				document.myform.submit();
			}
		}
  		else{
			//alert("thererror");
   			document.getElementById("myform").innerHTML="An error has occured making the request";
		}
	}
}
		}
</script>
</head>
<body>
<div id="result"></div>
<form id="myform" name="myform" action="newfile.php" method="post">
<table border="0">';
/************************BOF Paging **************************/

if(isset($_GET['page'])) 
{
	
	$testobjid=$_GET['testobjid'];
	$oqry=mysql_query("select * from questionbank where questionid in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID='".$testobjid."')")or die(mysql_error());
	
	$page=$_GET['page'];
	
	//echo $_GET['testobjid'];
}
else
{
	
	$q1=mysql_query("select  testObjectiveid from testobjective where testObjectiveid='".$_POST['subtesttype']."'") or die(mysql_error());
	$r1=mysql_fetch_array($q1);
	$testobjid=$r1['0'];
	$oqry=mysql_query("select * from questionbank where questionid in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID='".$testobjid."')")or die(mysql_error());
	//$tot=mysql_num_rows($oqry);
	//$per_pages=1;
	//$total_pages=ceil($tot/$per_pages); 
	$page=1;
	
	/*************************BOF function insert data in custom test table *******************************************/
	function insertdata($testobjid)
	{
		$cqry=mysql_query("select max(customTestID) as ctestid from customtest where testobjectiveID='".$_POST['subtesttype']."'")or die(mysql_error());
		$c=mysql_fetch_row($cqry);
		$qry=mysql_query("select distinct questionanswerid from questionanswerrelationship qar,questionbank qb where qar.questionid=qb.questionid and qar.testObjectiveid='".$testobjid."'") or die(mysql_error());
		while($rs=mysql_fetch_array($qry,MYSQL_BOTH))
		{   
			$ctestname="test".$testobjid;
			$ctestid=$c['0']+1;
			$ctestqarid=$rs['0'];
			$ctestobjid=$testobjid;
			$insqry=mysql_query("insert into customtest values ('".$ctestid."','".$ctestname."','".$ctestqarid."','".$ctestobjid."')") or die(mysql_error());
		}
	}
	
	/*************************EOF function insert data in custom test table *******************************************/
	
	insertdata($testobjid);
}
$tot=mysql_num_rows($oqry);
$per_pages=1;
$total_pages=ceil($tot/$per_pages);
// limit x,y x- where to start and y- how many to display
$x=($page-1)*$per_pages;

$outqry=mysql_query("select * from questionbank where questionid in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID='".$testobjid."')limit $x,$per_pages")or die(mysql_error());

while($outerrs=mysql_fetch_array($outqry,MYSQL_BOTH))
{

	//$cnt=mysql_num_rows($outqry);
	//echo "qcnt".$qcnt=$cnt+1;
	
	$dis.='<tr>
	<td colspan="3" class="topic_heading">4.&nbsp;Test Started:'.$outerrs['3'].'</td></tr>';
	
	$innerqry=mysql_query("select qar.*,qb.*,ab.*,c.* from questionanswerrelationship qar, questionbank qb, answerbank ab,customtest c where  qar.questionid=qb.questionid and qar.questionAnswerid=c.questionanswerID and qar.answerid=ab.answerid and qar.testObjectiveid='".$testobjid."' and qar.questionid='".$outerrs['0']."' and c.customtestID =(select max(customtestID) from customtest where testobjectiveID='".$testobjid."')") or (mysql_error());
	//query for fetching custom test id from customtest table
	$cq=mysql_query("select max(customtestID) from customtest where testobjectiveID='".$testobjid."'") or die(mysql_error());
	$cnt1=mysql_fetch_row($cq);
	//query for fetching question id and questionanswerid
	$qqry=mysql_query("select distinct questionid,questionAnswerid from questionanswerrelationship where questionid='".$outerrs['0']."' and testObjectiveid='".$testobjid."'")or die (mysql_error());
	$qcnt=mysql_fetch_row($qqry);
	
		
		$dis.='<tr><td colspan="3">4.1&nbsp;Question:'.$qcnt['0'].'&nbsp;out of&nbsp;'.$tot.'</td></tr>';
		$dis.='<tr><td colspan="3"><pre>'.$qcnt['0'].'&nbsp;'.$outerrs['4'].'</pre></td></tr>';
		
	$queid=$outerrs['0'];
	$que_ans_id=$qcnt['1'];
	$tobjid=$testobjid;
	$ct=$cnt1['0'];
	
	
		
	while($innerrs=mysql_fetch_array($innerqry))
	{
		
	     	if(($outerrs['0'])==$innerrs['1'])
			{
				$dis.='<tr><td colspan="3"><input id="ansid" type="radio" name="ansid" value='.$innerrs['16'].'>'.$innerrs['17'].'</td></tr>';
							
			}
			
	}
	
} 

/**************************BOF OF PREVIOUS ,NEXT & FINISH LINK ***********************************/
if($page!=1)
{
	//$dis.= "<a href='newfile.php?testobjid=$testobjid&page=1'> First </a>"."";
	$previous=$page-1;
	$dis.="<tr><td><a href='newfile.php?testobjid=$testobjid&page=$previous'> Previous </a></td>";
	
}
if(($page!=1) && ($page!=$total_pages))
{
 	$dis.= "|";
}
if($page!=$total_pages)
{
	
	$next=$page+1;
	if ($next == null) {
		$dis.= "<td><a href='#' onclick='submitFormWithAjax($testobjid,'',$queid,$que_ans_id,$ct);' class='testbtn'> Next </a>"."</td>";
	} else {
		$dis.= "<td><a href='#' onclick='submitFormWithAjax($testobjid,$next,$queid,$que_ans_id,$ct);' class='testbtn'> Next </a>"."</td>";
	}
	
	//$dis.= "<a href='#' onclick='submitFormWithAjax($testobjid,$total_pages,$queid,$que_ans_id,$tobjid);' class='testbtn'> Last> </a></td></tr>";
}
if($page==$total_pages)
{
	$page=$total_page;
	$dummy="finish";
	$dis.= "<td><a href='#'  onclick=\"submitFormWithAjax($testobjid,'finish',$queid,$que_ans_id,$ct);\" class='testbtn'> Finish </a>"."</td>";
}
/*******************************EOF OF PREVIOUS ,NEXT & FINISH LINK ******************************/

$dis.='</table></form></body></html>';
echo $dis; 
//}
//testque_ans($testobjid);
?>