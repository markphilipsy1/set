<?php  
	include 'connect.php'; 
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}

	if (isset($_POST['add'])) {
		$sectionname = $_POST['section'];
		$questions = array($_POST['q1'], $_POST['q2'], $_POST['q3'], $_POST['q4'], $_POST['q5']);

		$last = "SELECT max(section) as last FROM questionsection";
		$reslast = mysqli_query($connect, $last);
		$row = mysqli_fetch_array($reslast);
		$section = $row['last'];

		$sql = mysqli_query($connect, "SELECT * FROM tbl_ques");
		$quesnum = mysqli_num_rows($sql);
		
		$sql = mysqli_query($connect, "INSERT INTO questionsection values (NULL, '$sectionname')");
		
		for ($i=$quesnum; $i < $quesnum+5; $i++) { 
			$sql1 = mysqli_query($connect, "ALTER TABLE tbl_eval ADD score$i int(11) DEFAULT 0");
		}

		for ($i=0; $i < 5; $i++) { 
			$sql2 = mysqli_query($connect, "INSERT INTO tbl_ques values (NULL, '$questions[$i]', $section)");
		}

		if ($sql1 && $sql2) {
			echo "<script>alert('Added new section and questions successfully!')
					window.location.href = 'evalForm_main.php'</script>";
			// header('location:evalForm_main.php');
		} else {
			echo "<script>alert('Adding new section and question not complete!')</script>";
			header('Refresh:0');

		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" type="image/x-icon" href="img/cvsu.png">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/icon/css/all.css">

	<title>Cavite State University | SET</title>
	<style>
    body {
    	padding-top: 5em;
    }
  </style>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<li class="nav-item"><a class="nav-link" href="evalForm_main.php">Eval form</a></li>
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<form method="POST">
			<label for="section"><strong>New Section Name</strong></label>
			<input type="text" class="form-control" id="section" name="section" placeholder="Section Name">
			<hr>
			<label for="questions"><strong>Questions</strong></label>
			<input type="text" class="form-control" id="q1" name="q1" placeholder="Question 1"><br>
			<input type="text" class="form-control" id="q2" name="q2" placeholder="Question 2"><br>
			<input type="text" class="form-control" id="q3" name="q3" placeholder="Question 3"><br>
			<input type="text" class="form-control" id="q4" name="q4" placeholder="Question 4"><br>
			<input type="text" class="form-control" id="q5" name="q5" placeholder="Question 5"><br>
			
			<button type="submit" name="add" class="btn btn-success form-control">ADD</button>
		</form>
  	</div>

	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
