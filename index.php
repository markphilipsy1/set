<?php
	include 'connect.php';
	session_start();


	if (isset($_POST['login'])) {
		$sql = "SELECT * FROM tbl_admin WHERE ad_uname = '$_POST[uname]' AND ad_pass = '$_POST[pass]'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);
		if ($row) {
			header('location: prof.php');
			$_SESSION['access'] = 'admin';
		} else {
			echo "<script>alert('Invalid')</script>";
			header('index.php');
		}
	}

	if (isset($_POST['cvsu'])) {
		// $_SESSION['studentnumber'] = 201810921;
		// header('location: studProf.php');
		
		header('location: google/googlelogin.html');
		$_SESSION['access'] = 'student';
	}

	if (isset($_GET['logout'])) {
		session_destroy();
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" type="image/x-icon" href="img/cvsu.png">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<title>Cavite State University | SET</title>
</head>
<body style="background-color: forestgreen; padding-top: 20pt;">
	<form method="POST">
		<div class="card mx-auto" style="width: 40em; height: 39em;">
		  <div class="card-body">
			<center><img src="img/cvsu.png" class="img-responsive"></center>
		    <h5 class="card-title text-center">Cavite State University | Student Evaluation for Teachers</h5><br><br>
		    <div class="form-group card-text">
				<label for="uname">Username</label><input type="text" name="uname" placeholder="Username" class="form-control" autofocus><br>
				<label for="pass">Password</label><input type="password" name="pass" placeholder="Password" class="form-control"><br>
				<button type="submit" name="login" class="btn btn-light form-inline" style="float: right; width: 100px;">LOGIN</button>
				<button name="cvsu" class="btn btn-light mb-2 form-inline" style="width: 180px;"><img src="img/google.png" width="16%"> Student Login</button>
			</div>
		  </div>
		</div>
	</form>
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>