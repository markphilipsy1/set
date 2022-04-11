<?php 
	define('host', '192.168.10.41');
	define('username', 'cvsuset.user');
	define('password', 'cvsuset2021');
	define('dbname', 'cvsudatabase123qwe-new');

	// define('host', 'localhost');
	// define('username', 'root');
	// define('password', '');
	// define('dbname', 'cvsudatabase123qwe');


	$connect = mysqli_connect(host, username, password, dbname);
	
function openConn(){
// 	$servername = "119.92.116.149";
// $username = "supply.user";
// $password = "qhDTYX7r3=RmbyTz*Fk8RVxWQ!MRAvE4";
// $dbname = "suppyoffice";
	$servername = host;
	$username = username;
	$password = password;
	$dbname = dbname;
	$connect =mysqli_connect($servername, $username, $password,$dbname);
	return $connect;
}
 

 ?>