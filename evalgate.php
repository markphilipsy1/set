<?php 
	include 'connect.php';
	session_start();

	$studentnumber = $_SESSION['studentnumber'];
	$prof_id = $_GET['prof_id'];

	$sql = "SELECT * FROM tbl_eval WHERE studentnumber = $studentnumber AND prof_id = $prof_id";
	$res = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($res);

	if ($row) {
		echo "<script>alert('Already Evaluated!')
				window.location.href = 'studProf.php'</script>";
		// header('location: studProf.php');
	} else {
		header('location: evalForm_main.php?prof_id='.$prof_id);
	}
 ?>