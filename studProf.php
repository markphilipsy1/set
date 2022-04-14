<?php  
	include 'connect.php';
	session_start();

	if ($_SESSION['access'] != 'student') {
		header('location:index.php');
		session_destroy();
	}

	$sql = "SELECT studentNumber, course, firstName, lastName, middleName, suffix, dateOfBirth, concat(municipality, ', ' ,province) as address, mobilePhone FROM enrollstudentinformation WHERE studentNumber = ". $_SESSION['studentnumber'];
	// $sql = "SELECT * FROM studenttbl WHERE studentnumber = ".$_SESSION['studentnumber'];
	$res = mysqli_query($connect, $sql);
	$stud = mysqli_fetch_assoc($res);

  	$sql1 = "SELECT a.studentNumber, b.schedcode, c.subjectCode, c.instructor, d.subjectTitle, concat(e.prof_fname,' ', e.prof_lname) as prof_name from enrollstudentinformation a inner join enrollsubjectenrolled b on a.studentnumber = b.studentnumber inner join enrollscheduletbl c on b.schedcode = c.schedcode inner join enrollsubjectstbl d on c.subjectCode = d.subjectcode left join tbl_prof e on c.instructor = e.prof_id WHERE studentnumber = ".$_SESSION['studentnumber'];
  	$res1 = mysqli_query($connect, $sql1);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" type="image/x-icon" href="img/cvsu.png">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/icon/css/all.css">
	<style type="text/css">
		body {
			padding-top: 6em;
			padding-bottom: 5em;
		}
	</style>
	
	<title>Cavite State University | SET</title>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<div class="row align-items-center">
  			<div class="col-sm-4 text-center">
  				<div><span class="far fa-user" style="font-size: 8em;"></span></div>
  				<?php 

  					if ($stud) {
  						echo "<div>".$stud['studentNumber']."</div>";
  						echo "<div>".$stud['course']."</div>";
  				?>
		  	</div>
			<div class="col-sm-8">
				<div class="display-4"><?php echo $stud['lastName'].", ".$stud['firstName']." ".$stud['middleName']." ".$stud['suffix'] ?></div><br>
  				<?php
  						echo "<div>".$stud['address']."</div>";
  						echo "<div>".$stud['dateOfBirth']."</div>";
  						echo "<div>".$stud['mobilePhone']."</div>";
  					}
  				?>
			</div>
  		</div>
  		<br>
  		<hr>
  		<br>
  		<div class="row">
  			<div><strong class="h4">Enrolled Subject</strong></div>
  			<table class="table table-sm">
  				<thead>
  					<tr>
	  					<th><strong>Course Code</strong></th>
	  					<th><strong>Course Description</strong></th>
	  					<th><strong>Instructor's Name</strong></th>
	  					<th><strong>Evaluate</strong></th>
  					</tr>
  				</thead>
  				<tbody>
  					<?php 
			  			while ($sub = mysqli_fetch_array($res1)) {
			  				if ($sub['instructor'] != '-') {
				  				$sql_check = "SELECT * FROM tbl_eval a INNER JOIN tbl_period b ON a.period = b.id WHERE studentnumber = ".$_SESSION['studentnumber']." AND prof_id = ".$sub['instructor']." AND b.id = (SELECT id FROM tbl_period WHERE active = 1)";
				  				$res_check = mysqli_query($connect, $sql_check);
				  				$row_check = mysqli_fetch_array($res_check);  					
			  				}


			  				echo "<tr>";
			  				echo "<td class='text-center'>".$sub['subjectCode']."</td>";
			  				echo "<td>".$sub['subjectTitle']."</td>";
			  				echo "<td>".$sub['prof_name']."</td>";

			  				?>
			  				<td class="text-center"><a href="evalForm_main.php?prof_id=<?php echo $sub['instructor'];?>"><button class="btn btn-light" id="<?php echo $sub['instructor'] ?>" name="<?php echo $sub['subjectCode'] ?>"><span class="fa fa-edit" id="<?php echo $sub['instructor'] ?>"></span></button></a></td>
			  		<?php
				  			if ($row_check) {
				  				echo "<script>
				  					document.getElementById(".$sub['instructor'].").setAttribute('disabled','');
				  					document.getElementById(".$sub['instructor'].").innerHTML = 'Evaluated';
				  					document.getElementById(".$sub['instructor'].").setAttribute('class','btn btn-light text-success');
				  					</script>";
				  			}
			  				echo "</tr>";
			  			}
			  		?>
  				</tbody>
  			</table>
  		</div>
  	<script type="text/javascript">
  		document.getElementById('-').setAttribute('disabled', '');
  	</script>		
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>