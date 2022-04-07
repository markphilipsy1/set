<?php 
	include 'connect.php';
	session_start();
	
	
	$qid = $_GET['qid'];
	$question = $_GET['question'];

	$sql = "UPDATE tbl_ques SET questions = '".$question."' WHERE ques_id =".$qid;
	
	if (mysqli_query($connect, $sql)) {
		echo "<script>alert('Question successfully changed!')</script>";
	} else {
		echo "<script>alert('Error!')</script>";
	}

	header('location:evalForm_main.php');
?>