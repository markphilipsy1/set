<?php
	include 'connect.php';
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}
	
	$prof_id = $_GET['prof_id'];

	$sections = mysqli_query($connect, "SELECT * FROM questionsection");
	$seccnt = mysqli_num_rows($sections);

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
        	<li class="nav-item"><a class="nav-link" href="index.php?feedback=logout"><span class="fas fa-sign-out-alt"></span> Logout</a></li>
      	</ul>
  	</nav>
  	<div class="container">
  		<?php
  			$sql = "SELECT CONCAT(prof_fname, ' ', prof_lname) AS prof_name, prof_col FROM tbl_prof WHERE prof_id=$prof_id";
  			$name = mysqli_query($connect, $sql);
  			$prof = mysqli_fetch_assoc($name);

  			echo "<div class='h3'><strong>".$prof['prof_name']."</strong><small>(".$prof['prof_col'].")</small></div>";
  			?>
  			<table class="table table-bordered text-center">
  				<thead>
  					<tr>
  						<th rowspan="2" class="align-middle">#</th>
  						<?php 
  							while ($row = mysqli_fetch_assoc($sections)) {
  								echo "<th colspan='5'>".$row['sectionname']."</th>";
  								
  							}
  							echo "</tr>";
  							echo "<tr>";

  							for ($i=0; $i < $seccnt; $i++) { 
  								for ($j=1; $j <= 5; $j++) { 
  									echo "<th>".$j."</th>";
  								}
  							}
  						 ?>
  					</tr>
  				</thead>
  				<tbody>
  					<?php
			  			$sql1 = "SELECT * FROM ".$_SESSION['semyr']." WHERE prof_id=$prof_id";
			  			$res = mysqli_query($connect, $sql1);
			  			$row_cnt = mysqli_num_rows($res);
				  		$number = 1;
			  			$seca = 0;
			  			$secb = 0;
			  			$secc = 0;
			  			$secd = 0;

			  			if ($row_cnt != 0) {
				  			while ($score = mysqli_fetch_assoc($res)) {
				  				echo "<tr>";
				  				echo "<td><strong>".$number."</strong></td>";
				  				for ($i=0; $i < 5 ; $i++) { 
				  					$seca += $score['score'.$i.''];
				  					echo "<td>".$score['score'.$i.'']."</td>";
				  				}
				  				for ($j=5; $j < 10 ; $j++) { 
				  					$secb += $score['score'.$j.''];
				  					echo "<td>".$score['score'.$j.'']."</td>";
				  				}
				  				for ($k=10; $k < 15 ; $k++) { 
				  					$secc += $score['score'.$k.''];
				  					echo "<td>".$score['score'.$k.'']."</td>";
				  				}
				  				for ($l=15; $l < 20 ; $l++) { 
				  					$secd += $score['score'.$l.''];
				  					echo "<td>".$score['score'.$l.'']."</td>";
				  				}
				  				echo "</tr>";
				  				$number++;
				  			}
			  			}
			  		?>
  				</tbody>
  			</table>
  			<?php 
  				if ($row_cnt == 0) {
  					echo "<center><small>Nothing to show here.</small></center>";
  				}
  			 ?>
  			
  		<!-- <center><small>Nothing to show here.</small></center> -->
  		<br>
  		<div><button class="btn btn-default" onclick="window.history.back();"><span class="fas fa-chevron-left"></span> Back to List</button></div>
  	</div>
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>