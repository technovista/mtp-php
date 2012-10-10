<?php 
include("connect.php");
session_start();
if($_GET['action']=='chk')
{
	$s="select * from user where emailid='".$_POST['emailid']."' and password= '".$_POST['txtpwd']."'";
	$sql=mysql_query($s) or die(mysql_error());
	
	
	
	if(mysql_num_rows($sql)>0)
	{
		
		
		 while($rs=mysql_fetch_assoc($sql,3)) 
		{
			$_SESSION['testtakerid']=$rs['testTakerId']; 
			$_SESSION['user']=$rs['emailId'];
			 $_SESSION['pwd']=$rs['password'];
			//exit();
			header("location:testtype.php");
		}	
	} 	
	else
	{
		
		$msg='1.4&nbsp;*Error Message:Incorrect UserName'; 
		$msg1='1.4a&nbsp;*Error Message:Incorrect Password';
		
	}
	
}

?>
<html>
<head>
 <link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<form name="frm1" method="post" action="Login.php?action=chk">
<table border="0">
	<tr>
		<td class="topic_heading" colspan="2">1&nbsp;Login Screen</td>
		
	</tr>
	<tr>
		<td colspan="2">1.1&nbsp;User Name</td>
	</tr>
	<tr>
		<td colspan="2"><input type="text" name="emailid"></td>
	</tr>
	<tr>
	<td colspan="2" class="err_msg"><?php echo $msg; ?> </td>
	</tr>
	<tr>
		<td colspan="2">1.1b&nbsp;Password</td>
	</tr>
	
	<tr>
		<td colspan="2"><input type="password" name="txtpwd"></td>
	</tr>
	<tr>
	<td colspan="2" class="err_msg"><?php echo $msg1; ?> </td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="1.5&nbsp;Login" class="btn" >
		<input type="reset" name="cancel" value="1.6&nbsp;Cancel" class="btn"></td>
	</tr>
	
	<tr>
		<td colspan="2"><a href="ragi.php">Register for Account</a></td>
	</tr>
	<tr>
		<td colspan="2"><a href="forgetpwd.php">Forget Password</a></td>
	</tr>
	
</table>

</form>
</body>
</html>