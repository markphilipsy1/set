<?php 
	include 'connect.php';
	$prof_id = $_GET['prof_id'];

	if (isset($_POST['update'])) {
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$college = $_POST['college'];
		$department = $_POST['department'];
		$campus = $_POST['campus'];

		$sql = "UPDATE tbl_prof SET prof_fname = '".$fname."', prof_lname = '".$lname."', prof_email = '".$email."', prof_col = '".$college."', prof_dept = '".$department."', prof_campus = '".$campus."' WHERE prof_id = ".$prof_id;
		$res = mysqli_query($connect, $sql);

		if ($res) {
			echo "<script>alert('Successfully updated!')
				window.location.href='main_list.php?college=$college';</script>";
		}
	}
 ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" type="image/x-icon" href="img/cvsu.png">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/icon/css/all.css">
	<title>Cavite State University | SET</title>
	<style type="text/css">
		body {
			padding-top: 5em;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<li class="nav-item"><a class="nav-link" href="#"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<?php 
  			$sql = "SELECT * FROM tbl_prof WHERE prof_id = $prof_id";
  			$res = mysqli_query($connect, $sql);
  			$prof = mysqli_fetch_assoc($res);
  		 ?>
  		 <form method="POST">
  		 	<h4><strong>Update Profile</strong></h4>
		  	<div class="input-group">
			 	<div class="input-group-prepend">
			    	<span class="input-group-text bg-transparent" id="fname"><strong>First Name</strong></span>
			  	</div>
			  	<input type="text" class="form-control" name="fname" value="<?php echo $prof['prof_fname'] ?>">
			</div>
			<br>
			<div class="input-group">
			 	<div class="input-group-prepend">
			    	<span class="input-group-text bg-transparent" id="lname"><strong>Last Name</strong></span>
			  	</div>
			  	<input type="text" class="form-control" name="lname" value="<?php echo $prof['prof_lname'] ?>">
			</div>
			<br>
			<div class="input-group">
			 	<div class="input-group-prepend">
			    	<span class="input-group-text bg-transparent" id="email"><strong>Email</strong></span>
			  	</div>
			  	<input type="email" class="form-control" name="email" value="<?php echo $prof['prof_email'] ?>">
			</div>
			<br>
			<div class="input-group mb-3">
			  	<div class="input-group-prepend">
			    	<label class="input-group-text bg-transparent" for="college"><strong>College</strong></label>
			  	</div>
			  	<select class="custom-select" id="college" name="college">
				    <option selected value="<?php echo $prof['prof_col'] ?>"><?php echo $prof['prof_col'] ?></option>
				    <option value="CAFENR">CAFENR</option>
				    <option value="CAS">CAS</option>
				    <option value="CCJ">CCJ</option>
				    <option value="CED">CED</option>
				    <option value="CEMDS">CEMDS</option>
				    <option value="CEIT">CEIT</option>
				    <option value="CON">CON</option>
				    <option value="CSPEAR">CSPEAR</option>
				    <option value="CVMBS">CVMBS</option>
			  	</select>
			</div>
			<div class="input-group mb-3">
			  	<div class="input-group-prepend">
			    	<label class="input-group-text bg-transparent" for="department"><strong>College Department</strong></label>
			  	</div>
			  	<select class="custom-select" id="department" name="department">
				    <option selected value="<?php echo $prof['prof_dept'] ?>"><?php echo $prof['prof_dept'] ?></option>
				    <option value="DAFE">DAFE</option>
				    <option value="DCE">DCE</option>
				    <option value="DCEE">DCEE</option>
				    <option value="DIET">DIET</option>
				    <option value="DIT">DIT</option>
			  	</select>
			</div>
			<div class="input-group mb-3">
			  	<div class="input-group-prepend">
			    	<label class="input-group-text bg-transparent" for="campus"><strong>Campus</strong></label>
			  	</div>
			  	<select id="campus" name="campus" class="custom-select">
				    <option selected value="<?php echo $prof['prof_campus'] ?>"><?php echo $prof['prof_campus'] ?></option>
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
			<hr>
			<button class="btn btn-success form-control" type="submit" name="update">Update</button>
	  	</form>
	  	<br>
	  	<div><button class="btn btn-default" onclick="window.history.back();"><span class="fas fa-chevron-left"></span> Back to List</button></div>
  	</div>
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>