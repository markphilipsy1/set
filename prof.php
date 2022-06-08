<?php  
	include 'connect.php'; 
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}

	$sql = mysqli_query($connect, "SELECT * FROM tbl_period WHERE active = 1");
	$period = mysqli_fetch_array($sql);

	if (isset($_POST['review'])) {
		$sem = $_POST['sem'];
		$schyear = $_POST['schyear'];

		$sql = mysqli_query($connect, "UPDATE tbl_period SET active = 0 WHERE active = 1");
		$sql = mysqli_query($connect, "UPDATE tbl_period SET active = 1 WHERE semester = '$sem' AND year = '$schyear'");
		header("Refresh:0");
		// $_SESSION['semyr'] = $sem.$schyear;
	}

	$num_eval = "SELECT * FROM tbl_eval a INNER JOIN tbl_period b ON a.period = b.id WHERE b.id = (SELECT id FROM tbl_period WHERE active =1) GROUP BY prof_id";
	$res = mysqli_query($connect, $num_eval);

	if ($res) {
		$eval_cnt = mysqli_num_rows($res);
	} else {
		$eval_cnt = 0;
	}

	$num_prof = "SELECT * FROM tbl_prof a INNER JOIN enrollscheduletbl b ON a.prof_id = b.instructor WHERE b.schoolyear = (SELECT year FROM tbl_period WHERE active = 1)";
	$res1 = mysqli_query($connect, $num_prof);
	// $prof_cnt = mysqli_num_rows($res1);

	if ($res1) {
		$prof_cnt = mysqli_num_rows($res1);
	} else {
		$prof_cnt = 0;
	}

	$coll = array('CAFENR', 'CAS', 'CCJ', 'CED', 'CEIT', 'CEMDS', 'CON', 'CSPEAR', 'CVMBS');
	for ($i=0; $i < count($coll); $i++) { 
		$sql = "SELECT * FROM tbl_prof a INNER JOIN tbl_eval b on a.prof_id = b.prof_id INNER JOIN tbl_period c ON b.period = c.id WHERE prof_col = '$coll[$i]' AND c.id = (SELECT id FROM tbl_period WHERE active = 1) GROUP BY b.prof_id";
		$res = mysqli_query($connect, $sql);

		if ($res) {
			$mainrows[$i] = mysqli_num_rows($res);
			// $eval_cnt = mysqli_num_rows($res);
		} else {
			$mainrows[$i] = 0;
			// $eval_cnt = 0;
		} 
	}

	$camp = array('BACOOR', 'CARMONA', 'CAVITE CITY', 'GENTRI', 'IMUS', 'MARAGONDON', 'NAIC', 'ROSARIO', 'SILANG', 'TANZA', 'TMC');
	for ($j=0; $j < count($camp); $j++) { 
		$sql1 = "SELECT * FROM tbl_prof a INNER JOIN tbl_eval b on a.prof_id = b.prof_id INNER JOIN tbl_period c ON b.period = c.id WHERE prof_campus = '$camp[$j]' AND c.id = (SELECT id FROM tbl_period WHERE active = 1) GROUP BY b.prof_id";
		$res1 = mysqli_query($connect, $sql1);

		if ($res1) {
			$camprows[$j] = mysqli_num_rows($res1);
			// $eval_cnt = mysqli_num_rows($res);
		} else {
			$camprows[$j] = 0;
			// $eval_cnt = 0;
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
    	padding-bottom: 3em;
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
        	<li class="nav-item"><a class="nav-link" href="evalForm_main.php">Evaluation Form</a></li>
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<?php
  			echo "<p class='h5 text-right'>Current Semester and Year: <span class='text-uppercase'>".$period['semester']."</span> ".$period['year']. "</p>";
  			echo "<p class='h5 text-right'>Teachers Evaluated: ".$eval_cnt."/".$prof_cnt. "</p>";
  			// echo "<br>";
  			// echo "<p class='h5 text-left' id='review'>Reviewing: <span class='text-uppercase'>".$sem."</span> SEM ".$schyear. "</p>";
  		 ?>
  		 <form method="POST" class="form-group">
		  		<div class="row">
		  			<label for="sem">SEMESTER</label>
		  			<div class="col-sm-2">
						    <select id="sem" name="sem" class="custom-select">
						    	<option value="FIRST">1st SEM</option>
						    	<option value="SECOND">2nd SEM</option>
					      	</select>
					    </div>
		  			<label for="schyear">School Year</label>
					    <div class="col-sm-2">
							<select id="schyear" name="schyear" class="custom-select">
							   	<?php 
							   		$sql = mysqli_query($connect, "SELECT DISTINCT(year) FROM tbl_period");
							   		while ($year = mysqli_fetch_array($sql)) {
							   			echo "<option value='".$year['year']."'>".$year['year']."</option>";
							   		}

							   	 ?>
						    </select>
					    </div>
					    	<button type="submit" class="btn btn-light" name="review" style="height: 30pt;">Review  <span class="fas fa-search"></span></button>
		  		</div>
			</form>
  		 <br>
  		<p class="h4">Main Campus</p><hr>
  		<div class="row">
  			<div class="col-sm-4"><a href="main_list.php?college=CAFENR"><button class="btn btn-success form-control">CAFENR <span class="badge badge-light"><?php echo $mainrows[0]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CAS"><button class="btn btn-success form-control">CAS <span class="badge badge-light"><?php echo $mainrows[1]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CCJ"><button class="btn btn-success form-control">CCJ <span class="badge badge-light"><?php echo $mainrows[2]; ?></span></button></a></div>
  		</div>
  		<div class="row">
  			<div class="col-sm-4"><a href="main_list.php?college=CED"><button class="btn btn-success form-control">CED <span class="badge badge-light"><?php echo $mainrows[3]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CEIT"><button class="btn btn-success form-control">CEIT <span class="badge badge-light"><?php echo $mainrows[4]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CEMDS"><button class="btn btn-success form-control">CEMDS <span class="badge badge-light"><?php echo $mainrows[5]; ?></span></button></a></div>
  		</div>
  		<div class="row">
  			<div class="col-sm-4"><a href="main_list.php?college=CON"><button class="btn btn-success form-control">CON <span class="badge badge-light"><?php echo $mainrows[6]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CSPEAR"><button class="btn btn-success form-control">CSPEAR <span class="badge badge-light"><?php echo $mainrows[7]; ?></span></button></a></div>
  			<div class="col-sm-4"><a href="main_list.php?college=CVMBS"><button class="btn btn-success form-control">CVMBS <span class="badge badge-light"><?php echo $mainrows[8]; ?></span></button></a></div>
  		</div>
  		<br>
  		<br>
	  	<p class="h4">Branch Campus</p><hr>
	  	<div class="row">
	  		<div class="col-sm-4"><a href="campus_list.php?campus=BACOOR"><button class="btn btn-success form-control">Bacoor <span class="badge badge-light"><?php echo $camprows[0]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=CARMONA"><button class="btn btn-success form-control">Carmona <span class="badge badge-light"><?php echo $camprows[1]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=CAVITE CITY"><button class="btn btn-success form-control">Cavite City <span class="badge badge-light"><?php echo $camprows[2]; ?></span></button></a></div>
	  	</div>
	  	<div class="row">
	  		<div class="col-sm-4"><a href="campus_list.php?campus=GENTRI"><button class="btn btn-success form-control">General Trias <span class="badge badge-light"><?php echo $camprows[3]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=IMUS"><button class="btn btn-success form-control">Imus <span class="badge badge-light"><?php echo $camprows[4]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=MARAGONDON"><button class="btn btn-success form-control">Maragondon <span class="badge badge-light"><?php echo $camprows[5]; ?></span></button></a></div>
	  	</div>
	  	<div class="row">
	  		<div class="col-sm-4"><a href="campus_list.php?campus=NAIC"><button class="btn btn-success form-control">Naic <span class="badge badge-light"><?php echo $camprows[6]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=ROSARIO"><button class="btn btn-success form-control">Rosario <span class="badge badge-light"><?php echo $camprows[7]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=SILANG"><button class="btn btn-success form-control">Silang <span class="badge badge-light"><?php echo $camprows[8]; ?></span></button></a></div>
	  	</div>
	  	<div class="row justify-content-center">
	  		<div class="col-sm-4"><a href="campus_list.php?campus=TANZA"><button class="btn btn-success form-control">Tanza <span class="badge badge-light"><?php echo $camprows[9]; ?></span></button></a></div>
	  		<div class="col-sm-4"><a href="campus_list.php?campus=TMC"><button class="btn btn-success form-control">Trece Martires City <span class="badge badge-light"><?php echo $camprows[10]; ?></span></button></a></div>
	  	</div>
	  	<br>
	  	<hr>
	  	<button class="btn btn-dark text-center form-control" style="height: 3em;" onclick="complete()">Complete Evaluation</button>
  	</div>
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function complete() {
			if (confirm('Are you sure to complete the evaluations?')) {
				window.location.href = 'newsem.php';
			} else {
				window.location.href = 'prof.php';
			}
		}
	</script>
</body>
</html>