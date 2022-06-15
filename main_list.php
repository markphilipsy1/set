<?php  
	include 'connect.php'; 
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}

	$college = $_GET['college'];
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
	  	<form method="POST" class="form-group">
			<div class="row">
				<label for="dept">College Department</label>
				    <div class="col-sm-2">
						<select id="dept" name="dept" class="custom-select">
							<option value="all">ALL</option>
								   	<?php 
								   		$sql = mysqli_query($connect, "SELECT DISTINCT(prof_dept) FROM tbl_prof WHERE prof_col = '$college'");
								   		while ($dept = mysqli_fetch_array($sql)) {
								   			echo "<option value='".$dept['prof_dept']."'>".$dept['prof_dept']."</option>";
								   		}

								   	 ?>
					    </select>
				    </div>
				<button type="submit" class="btn btn-light" name="changedept" style="height: 30pt;">Review  <span class="fas fa-search"></span></button>
		  	</div>
		</form>
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
	  		if (!isset($_POST['changedept']) || $_POST['dept'] == 'all') {
	  			$sql = "SELECT a.prof_id, CONCAT(a.prof_lname , ', ', a.prof_fname) AS prof_name, a.prof_col, a.prof_dept FROM tbl_prof a INNER JOIN enrollscheduletbl b ON a.prof_id = b.instructor WHERE prof_col = '$college' AND b.schoolyear = (SELECT year FROM tbl_period WHERE active = 1) AND b.semester = (SELECT semester FROM tbl_period WHERE active = 1) GROUP BY prof_id ORDER BY prof_lname";
	  			$res = mysqli_query($connect, $sql);	
	  		}
			else {
				$dept = $_POST['dept'];

				$sql = "SELECT a.prof_id, CONCAT(a.prof_lname , ', ', a.prof_fname) AS prof_name, a.prof_col, a.prof_dept FROM tbl_prof a INNER JOIN enrollscheduletbl b ON a.prof_id = b.instructor WHERE prof_col = '$college' AND prof_dept = '$dept' AND b.schoolyear = (SELECT year FROM tbl_period WHERE active = 1) AND b.semester = (SELECT semester FROM tbl_period WHERE active = 1) GROUP BY prof_id ORDER BY prof_lname";
			  	$res = mysqli_query($connect, $sql);
			}
	  		while ($prof = mysqli_fetch_assoc($res)) {
	  			echo "<div class='row'>";
	  			echo "<div class='col-sm-5'>" .$prof['prof_name']. "</div><div class='col-sm-3'>" .$prof['prof_col']."-" .$prof['prof_dept']. "</div>";
	  			?>
	  			<div class='col-sm-4'>
	  				<a href="view.php?prof_id=<?php echo $prof['prof_id']; ?>"><span class="fa fa-eye"></span></a>
	  				<a href="edit.php?prof_id=<?php echo $prof['prof_id']; ?>"><span class="fa fa-edit"></span></a>
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
  	</div> 
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>