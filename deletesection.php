<?php 
	include 'connect.php';

	$section = $_GET['section'];

	$sql = mysqli_query($connect, "SELECT MAX(section) as todo FROM questionsection");
	$row = mysqli_fetch_array($sql);
	$last = $row['todo'];

	$delsec = mysqli_query($connect, "DELETE FROM questionsection WHERE section = $section");

	$delques = mysqli_query($connect, "DELETE FROM tbl_ques WHERE section = $section");

	$lastsql = "ALTER TABLE questionsection AUTO_INCREMENT = $last";
	$lastres = mysqli_query($connect, $lastsql);

	$sql = mysqli_query($connect, "SELECT * FROM tbl_ques");
	$quesnum = mysqli_num_rows($sql);

	for ($i=$quesnum; $i < $quesnum+5; $i++) { 
		$sql = mysqli_query($connect, "ALTER TABLE ".$_SESSION['cursemyr']." DROP COLUMN score$i");
	}

	echo "<script>alert('Deleted')</script>";

	header('location:evalForm_main.php');
 ?>