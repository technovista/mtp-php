<html>
<head>
<link href="stylesheet.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript">
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
function checkAll(field)
{
         
	for (i = 0; i < field.length; i++)
	{
		field[i].checked = true ;
		
}
} 

function checklist(testlist)
{
	ajaxreq=new GetXmlHttpObject();
	list=testlist.length;
	alert(list);
    slist="";
    for(i=0;i<testlist.length;i++)
    {
		if(testlist[i].checked==true)
		{
			slist=slist+" "+testlist[i].value;
		}
    }
	var perameters="subtestlist="+slist+"&totaltest="+list;
	//alert(slist);
	//alert("perameters:"+perameters);

	ajaxreq.open("POST","starttest.php",true);
	ajaxreq.setRequestHeader("content-type","application/x-www-form-urlencoded");
	ajaxreq.send(perameters);
	ajaxreq.onreadystatechange=function()
	{
		if(ajaxreq.readyState==4)
		{
			
			if( ajaxreq.status==200)
			{
				document.getElementById("result").innerHTML=ajaxreq.responseText;
				
				//document.getElementById("myform").style.display = "none";
				//document.myform.action="starttest.php?subtestlist"+slist;
				//document.myform.submit();
				//alert("sent:value"+v);
				//document.getElementById("myform").submit();
			}
			else
			{
				document.getElementById("myform").innerHTML="An error has occured making the request";
			}  
		}
					
	} 
	
}

</script>
</head>
<?php
 include("connect.php");
 session_start();
 if(!isset($_SESSION['user']))
 {
 	header('location:Login.php');
 }
 
$dis.='
<body><div id="result"></div>
<form name="myform" action="starttest.php" method="post">
<table border="0">
<tr>
	<td colspan="3" class="topic_heading">3.1&nbsp;Subtopic Test List</td>
</tr>
<tr>
	<td colspan="3">3.1&nbsp;Select the subtopic test</td>
</tr><tr><td colspan="3"><input type="checkbox" name="checkall" onclick="checkAll(document.myform.subtesttype);"/><b>3.2&nbsp;select all subtopic test</b></td></tr>';

$qry=mysql_query("select * from testobjective where testid ='".$_POST['testtype']."'") or die(mysql_error());

while($rs=mysql_fetch_assoc($qry,MYSQL_BOTH))
{
	//echo "testobjective id".$rs['0'];
	$sql1=mysql_query("select count(questionid) from questionbank where questionid in ( select questionid from questionanswerrelationship where testObjectiveid ='".$rs['0']."')") or die(mysql_error());
	
	while($rs1=mysql_fetch_array($sql1))
	{
		
		$dis.='<tr><td colspan="2"><input type="checkbox" name="subtesttype[]" id="subtesttype" value="'.$rs['testObjectiveId'].'">'.$rs['testObjectiveName']."</td><td>".$rs1['count(questionid)']."&nbsp;"."Questions".'</td></tr>';
	   //$_SESSION['testobjid']=$rs['0'];
	}
	
}

$dis.='<tr><td><input type="submit" name="Next" value="3.4&nbsp;Start" class="btn"  ></td>
</table></form></body></html>';



echo $dis;

?>