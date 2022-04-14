<?php
session_start();
include('../connect.php');
if(!isset($_POST['email'])){
	header("Location:googlelogin.html");

}
function logme($content){//log action to log file
	date_default_timezone_set('Asia/Manila');
	$myfile = fopen("logs.txt", "a") or die("Unable to open file!");

	fwrite($myfile, date("Y-m-d h:i:sa")."-".$content."\n");
	fclose($myfile);
}
$objConnection=openConn();
$email =  $_POST['email'];
$sql = "SELECT * FROM enrollstudentgsuite WHERE email = '$email'";//select email from yourDatabase where registered=1 or authorized =1
logme($sql);
$query = mysqli_query($objConnection,$sql) ;
$count = mysqli_num_rows($query);

if($count>0){// if account is authorized, you can retrieve the email / details of the user
		$row = mysqli_fetch_array($query);
		$role = 1;
		echo $role; // you may have roles or level of access of the user. Use in diverting the page
		//Create the sessions that can be used all through out the system
		$_SESSION['email'] = $email;
		$_SESSION['access'] ='student';
		$_SESSION['studentnumber'] = $row['studentnumber'];
}
else
	echo '0';//no account found

mysqli_close($objConnection);


?>
