<?php
include("connect.php");
session_start();
if(!isset($_SESSION['user']))
{
	header('location:Login.php');
}

$dis.='<html>
<head>
<script type="text/javascript" src="timer.js"></script>
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
	
function submitFormWithAjax(testObjId,next,queid,que_ans_id,ctid)
{
	
		myAjaxPostrequest=GetXmlHttpObject();
		
		
		var chk_arry=document.getElementsByName("ansid");
		var l=chk_arry.length;
		var fansid="";
		var flag=1;
		
		for(var i=0;i<l;i++)
		{
				
			if(document.myform.ansid[i].checked)
			{
				fansid=fansid+" "+document.myform.ansid[i].value;
				flag=0;
				
			}
	
		}
		if(flag==1)
		{
					fansid="skipped";
					alert("skipped");
		}
	var parameters="questionid="+queid+"&Answerid="+fansid+"&que_ansid="+que_ans_id+"&testobjid="+testObjId+"&customtid="+ctid;
	
	
	myAjaxPostrequest.open("POST", "testlog.php", true);
	myAjaxPostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	myAjaxPostrequest.send(parameters);
	myAjaxPostrequest.onreadystatechange=function(){
   	if(myAjaxPostrequest.readyState==4)
   	{
		if(myAjaxPostrequest.status==200)
		{
			
			document.getElementById("result").innerHTML=myAjaxPostrequest.responseText;  
  			//document.getElementById("myform").style.display = "none";
		    //alert("ready()" +testObjId + "::" + next);
			document.myform.method = "post";
				
			if (next != "finish") {
			//document.myform.action = "starttest.php?testobjid=" + testObjId + "&page=" + next +"&queid=" +queid + "&que_ans_id=" + que_ans_id +"&customtid="+ctid;
				 //alert(document.myform.action);
				//document.myform.submit();
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
</head>';

$dis.='<body"><form name="cd">
<input id="txt" readonly="true" type="text"  name="disp">
</form>'; 

$data=$_POST['subtesttype'];
$_SESSION['testtakerid'];
	/*************************BOF function insert data in custom test table *******************************************/
	function insertdata($v1)
	{
		
		$cqry=mysql_query("select max(customTestID) as ctestid from customtest where testobjectiveID='".$v1."'")or die(mysql_error());
		$c=mysql_fetch_row($cqry);
		$qry=mysql_query("select distinct questionanswerid from questionanswerrelationship qar,questionbank qb where qar.questionid=qb.questionid and qar.testObjectiveid='".$v1."'") or die(mysql_error());
		while($rs=mysql_fetch_array($qry,MYSQL_BOTH))
		{
			$ctestname="test".$v1;
			$ctestid=$c['0']+1;
			$ctestqarid=$rs['0'];
			$ctestobjid=$v1;
			$insqry=mysql_query("insert into customtest values ('".$ctestid."','".$ctestname."','".$ctestqarid."','".$ctestobjid."')") or die(mysql_error());
		}
	}
	
	/*************************EOF function insert data in custom test table *******************************************/
	
	$qry=mysql_query("select max(select_test_id) from selected_test where test_user_id='".$_SESSION['testtakerid']."'");
	$sid=mysql_fetch_row($qry);
	foreach($data as $key => $value)
	{
		$v1=$value;
		$t_objid=$v1;
		$s_test_id=$sid['0']+1;
		$test_user_id=$_SESSION['testtakerid'];
	$intqry=mysql_query("insert into selected_test(select_test_id,testobjectiveID,test_user_id)values($s_test_id,$t_objid,$test_user_id)") or die(mysql_error());
	
	
	insertdata($v1);
	}

 $dis.='<body>
<div id="result"></div>

<form id="myform" name="myform" action="starttest.php" method="post">
<table border="1">'; 
if(isset($_GET['page']))
{
	$page=$_GET['page'];
	
}
else
{
	$page=1;
	
}
 
$qry=mysql_query("select * from questionbank where questionId in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID in(select testobjectiveID from selected_test where test_user_id='".$_SESSION['testtakerid']."' and select_test_id in (select max(select_test_id) from selected_test group by test_user_id)))")or die(mysql_error());
$tot=mysql_num_rows($qry);

$per_pages=1;
$total_pages=ceil($tot/$per_pages);
$x=($page-1)*$per_pages;
$outqry=mysql_query("select * from questionbank where questionId in ( select qar.questionid from questionanswerrelationship qar,customtest c where c.questionanswerID=qar.questionanswerid and c.testobjectiveID in(select testobjectiveID from selected_test where test_user_id='".$_SESSION['testtakerid']."' and select_test_id in (select max(select_test_id) from selected_test group by test_user_id))) limit $x,$per_pages")or die(mysql_error());
while($outerrs=mysql_fetch_assoc($outqry))
	{
		$dis.='<tr>
			<td colspan="3" class="topic_heading">4.&nbsp;Test Started:'.$outerrs['questionType'].'</td></tr>
		<td colsapn="3">Total No Of Question:&nbsp;'.$tot.'</td>';
		$dis.='<tr><td colspan="3"><pre>'.$page.'&nbsp;'.nl2br($outerrs['questionDescripton']).'</pre></td></tr>';
		$q1=mysql_query("select * from selected_test where test_user_id='".$_SESSION['testtakerid']."' and select_test_id in (select max(select_test_id) from selected_test)");
		while($qrs=mysql_fetch_assoc($q1))
		{
			$testobjid=$qrs['testobjectiveID'];
			$innerqry=mysql_query("select qar.*,qb.*,ab.*,c.* from questionanswerrelationship qar, questionbank qb, answerbank ab,customtest c where  qar.questionId=qb.questionId and qar.questionAnswerId=c.questionanswerID and qar.answerId=ab.answerId and qar.testObjectiveId='".$testobjid."' and qar.questionId='".$outerrs['questionId']."' and c.customTestID =(select max(customTestID) from customtest where testobjectiveID='".$testobjid."')")or die(mysql_error());
		
		
		
			$cq=mysql_query("select max(customTestID) from customtest where testobjectiveID='".$testobjid."'") or die(mysql_error());
			$cnt1=mysql_fetch_row($cq);
			$queid=$outerrs['questionId'];
			$ct=$cnt1['0'];
			while($innerrs=mysql_fetch_assoc($innerqry))
			{
				$que_ans_id=$innerrs['questionAnswerId'];
				if($outerrs['questionType']=='MULTIPLE_CHOICE')
				{
					$dis.='<tr><td colspan="3"><input id="ansid" type="radio" name="ansid" value='.$innerrs['answerId'].'>'.$innerrs['choice'].'</td></tr>';
				}
				elseif($outerrs['questionType']=='MULTIPLE_SOLUTIONS')
				{
					$dis.='<tr><td colspan="3"><input id="ansid" type="checkbox" name="ansid" value='.$innerrs['answerId'].'>'.$innerrs['choice'].'</td></tr>';
				} 
				elseif($outerrs['questionType']=='Drag_Drop') 
				{
					$dis.='<input type="textarea" name="ansid">';
				}
				/*if(($outerrs['questionId'])==$innerrs['questionId'])
				{
					$que_ans_id=$innerrs['questionAnswerId'];
					$dis.='<tr><td colspan="3"><input id="ansid" type="checkbox" name="ansid" value='.$innerrs['answerId'].'>'.$innerrs['choice'].'</td></tr>';
				
				} */
					
			} 
		}
	}
	/**************************BOF OF PREVIOUS ,NEXT & FINISH LINK ***********************************/
	if($page!=1)
	{
		$previous=$page-1;
		$dis.="<tr><td><a href='starttest.php?page=$previous'> Previous </a></td>";
	
	}
	if(($page!=1) && ($page!=$total_pages))
	{
		$dis.= "|";
	}
	if($page!=$total_pages)
	{
	
		$next=$page+1;
		if ($next == null) {
			$dis.= "<td><a href='starttest.php?page=$next'  class='testbtn' onclick='submitFormWithAjax($testobjid,'',$queid,$que_ans_id,$ct);'> Next </a>"."</td>";
		} else {
			$dis.= "<td><a href='starttest.php?page=$next'  class='testbtn' onclick='submitFormWithAjax($testobjid,$next,$queid,$que_ans_id,$ct);'> Next </a>"."</td>";
		}
	
		
	}
	if($page==$total_pages)
	{
		$page=$total_page;
		$dummy="finish";
		$dis.= "<td><a href='#'   class='testbtn' onclick=\"submitFormWithAjax($testobjid,'finish',$queid,$que_ans_id,$ct);\"> Finish </a>"."</td>";
	}
	/*******************************EOF OF PREVIOUS ,NEXT & FINISH LINK ******************************/
	
	$dis.='<table></body>';

echo $dis;
?>