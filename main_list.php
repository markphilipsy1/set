<?php  
	include 'connect.php'; 
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}

	$college = $_GET['college'];

	if (isset($_POST['add'])) {
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$college = $_POST['college'];
		$campus = $_POST['campus'];

		$sql1 = "INSERT INTO tbl_prof values (NULL, '".$fname."', '".$lname."', '".$email."', '".$college."', '".$campus."')";
		$res1 = mysqli_query($connect, $sql1);

		if ($res1) {
			echo "<script>alert('Successfully added new teacher.')
				window.history.back();</script>";
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
    	padding-bottom: 5em;
    }
    .fa {
    	font-size: 20pt;
    	margin: 2px;
    }
  </style>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  	<p class="h5">
  		<?php 
  			$evalnumsql = mysqli_query($connect, "SELECT COUNT(*) as evalnum FROM tbl_eval a INNER JOIN tbl_prof b ON a.prof_id = b.prof_id INNER JOIN tbl_period c ON a.period = c.id WHERE b.prof_col = '$college' AND c.id = (SELECT id FROM tbl_period WHERE active = 1)");
  			$evalnum = mysqli_fetch_assoc($evalnumsql);

  			echo $college."<small style='float: right;'> Number of Evaluations Submitted: ".$evalnum['evalnum']."</small>"; ?></p>
  	<hr>
  		<div class='text-center'>
  			<div class='row'>
  				<div class='col-sm-5'>
  					<strong>Faculty Staff</strong>
  				</div>
  				<div class='col-sm-3'>
  					<strong>College</strong>
  				</div>
  				<div class='col-sm-4'>
  					<strong>Actions</strong>
  				</div>
  			</div>
  		<?php 
	  		$sql = "SELECT a.prof_id, CONCAT(a.prof_fname , ' ', a.prof_lname) AS prof_name, a.prof_col FROM tbl_prof a INNER JOIN enrollscheduletbl b ON a.prof_id = b.instructor WHERE prof_col = '$college' AND b.schoolyear = (SELECT year FROM tbl_period WHERE active = 1) AND b.semester = (SELECT semester FROM tbl_period WHERE active = 1) ORDER BY prof_fname";
	  		$res = mysqli_query($connect, $sql);
	  		while ($prof = mysqli_fetch_assoc($res)) {
	  			echo "<div class='row'>";
	  			echo "<div class='col-sm-5'>" .$prof['prof_name']. "</div><div class='col-sm-3'>" .$prof['prof_col']."</div>";
	  			?>
	  			<div class='col-sm-4'>
	  				<a href="view.php?prof_id=<?php echo $prof['prof_id']; ?>"><span class="fa fa-eye"></span></a>
	  				<a href="edit.php?prof_id=<?php echo $prof['prof_id']; ?>"><span class="fa fa-edit"></span></a>
	  				<a href="delete.php?prof_id=<?php echo $prof['prof_id']; ?>"><span class="fa fa-archive"></span></a>
	  			</div>
	  		</div>
	  		<?php 
	  		}
	  		 ?>
	  		 <br>
	  		 <a href="report_main.php?prof_col=<?php echo $college ?>"><button class="btn btn-light" style="float: left;"><span class="fas fa-file-alt"></span> Summary of Reports</button></a>
	  		 <br>
	  		 <br>
  	</div>
  	<div class="container">
  	 	<hr>
	  	<a href="prof.php"><button class="btn btn-default"><span class="fas fa-chevron-left"></span> Back to Colleges</button></a>
	  	<br>
	  	<br>
	  	<p class="h6">Add Faculty Staff?</p>
	  	<form method="POST">
		  	<div class="form-row">
			    <div class="form-group col-sm-6">
			      	<label for="fname">First Name</label>
			    	<input type="text" class="form-control" id="fname" name="fname" placeholder="First Name">
			    </div>
			    <div class="form-group col-sm-6">
			      	<label for="lname">Last Name</label>
			      	<input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name">
			    </div>
			</div>
			<div class="form-group">
				<label for="email">CvSU Email</label>
			    <input type="email" class="form-control" id="email" name="email" placeholder="CvSU Email">
			</div>
			<div class="form-row">
			    <div class="form-group col-sm-6">
			      	<label for="college">College</label>
				    <select id="college" name="college" class="form-control">
				    	<option value="-" disabled selected>Select College...</option>
				    	<option value="CAFENR">CAFENR</option>
				    	<option value="CAS">CAS</option>
				    	<option value="CCJ">CCJ</option>
				    	<option value="CED">CED</option>
				    	<option value="CEIT">CEIT</option>
				    	<option value="CEMDS">CEMDS</option>
				    	<option value="CON">CON</option>
				    	<option value="CSPEAR">CSPEAR</option>
				    	<option value="CVMBS">CVMBS</option>
			      	</select>
			    </div>
			    <div class="form-group col-sm-6">
			      	<label for="campus">Campus</label>
				    <select id="campus" name="campus" class="form-control">
				    	<option value="-" disabled selected>Select Campus...</option>
				        <option value="MAIN">MAIN</option>
				        <option value="BACOOR">BACOOR</option>
				        <option value="CARMONA">CARMONA</option>
				        <option value="CAVITE CITY">CAVITE CITY</option>
				        <option value="GENTRI">GENERAL TRIAS</option>
				        <option value="IMUS">IMUS</option>
				        <option value="MARAGONDON">MARAGONDON</option>
				        <option value="NAIC">NAIC</option>
				        <option value="ROSARIO">ROSARIO</option>
				        <option value="SILANG">SILANG</option>
				        <option value="TANZA">TANZA</option>
				        <option value="TMC">TRECE MARTIRES CITY</option>
			      	</select>
			    </div>
			</div>
			<button type="submit" name="add" class="btn btn-success">ADD</button>
		</form>
  	</div>

	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>