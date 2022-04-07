<?php  
	include 'connect.php'; 
	session_start();

	if ($_SESSION['access'] != 'student') {
		header('location:index.php');
		session_destroy();
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
    .fa {
    	font-size: 20pt;
    	margin: 2px;
    }
    .row {
    	padding: 10pt;
    }
    .btn {
    	height: 80pt;
    }
  </style>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<!-- <li class="nav-item"><a class="nav-link" href="evalForm_up.php">Eval form</a></li> -->
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<p class="h4">Select Campus</p>
  		<div class="row">
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=BACOOR"><button class="btn btn-success form-control">Bacoor</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=CARMONA"><button class="btn btn-success form-control">Carmona</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=CAVITE CITY"><button class="btn btn-success form-control">Cavite City</button></a></div>
	  	</div>
	  	<div class="row">
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=GENTRI"><button class="btn btn-success form-control">General Trias</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=IMUS"><button class="btn btn-success form-control">Imus</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=MARAGONDON"><button class="btn btn-success form-control">Maragondon</button></a></div>
	  	</div>
	  	<div class="row">
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=NAIC"><button class="btn btn-success form-control">Naic</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=ROSARIO"><button class="btn btn-success form-control">Rosario</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=SILANG"><button class="btn btn-success form-control">Silang</button></a></div>
	  	</div>
	  	<div class="row justify-content-center">
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=TANZA"><button class="btn btn-success form-control">Tanza</button></a></div>
	  		<div class="col-sm-4"><a href="evalForm_campus.php?campus=TMC"><button class="btn btn-success form-control">Trece Martires City</button></a></div>
	  	</div>  		
  	</div>

	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>