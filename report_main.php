<?php  
	include 'connect.php';
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}
	
	$college = $_GET['prof_col'];

	$sql = mysqli_query($connect, "SELECT * FROM questionsection");

	
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
			padding-top: 5em;
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
  		<table class="table table-sm text-center">
  			<tr class="text-center">
  				<td rowspan="2" scope="col" class="align-middle"><strong>Name of Faculty</strong></td>
  				<td rowspan="2" scope="col" class="align-middle"><strong>Sample Size</strong></td>
  				<?php 
  					while ($row = mysqli_fetch_array($sql)) {
  						echo "<td rowspan='2' class='align-middle'><strong>".$row['sectionname']."</td>";
  					}
  				 ?>
  				<td colspan="2" scope="col"><strong>Rating</strong></td>
  			</tr>
  			<tr class="text-center">
  				<td>Numerical</td>
  				<td>Adjective</td>
  			</tr>
  		<?php 
			$sql = mysqli_query($connect, "SELECT a.prof_id, COUNT(a.prof_id) as samp_size, CONCAT(b.prof_fname,' ',b.prof_lname) as prof_name FROM tbl_eval a INNER JOIN tbl_prof b ON a.prof_id = b.prof_id INNER JOIN tbl_period c ON a.period = c.id WHERE b.prof_col = '$college' AND c.id = (SELECT id FROM tbl_period WHERE active = 1) GROUP BY a.prof_id");
			$row_cnt = mysqli_num_rows($sql);
			while ($row = mysqli_fetch_assoc($sql)) {
 				echo "<tr><td>".$row['prof_name']."</td>";
 				echo "<td>".$row['samp_size']."</td>";
 				thescore($row['prof_id']);
			}
			
			function thescore($prof){
				global $connect;
				global $college;
				$sql = mysqli_query($connect, "SELECT * FROM questionsection");
				$numrows = mysqli_num_rows($sql);

				$question = mysqli_query($connect, "SELECT * FROM tbl_ques");
				$qcount = mysqli_num_rows($question);

				$sum = array();
				$fieldname = array();
				$score = array();
				$sectionscores = array();
				$range = range(0, $qcount, 5);

				for ($i=0; $i < count($range)-1; $i++) {
					$ctr = $range[$i]+5;
					for ($j=$range[$i]; $j < $ctr; $j++) {
						array_push($fieldname, "a.score".$j);
					}
				}
				// the sql
				for ($l=0; $l < count($range)-1; $l++) {
					$score = array();
					$ctr = $range[$l]+5;
					// the fieldname
					for ($m=$range[$l]; $m < $ctr; $m++) { 
						array_push($score, $fieldname[$m]);
					}
					$scores = implode('+', $score);

					$sql = mysqli_query($connect, "SELECT COUNT(a.prof_id) as samp_size, CONCAT(b.prof_fname,' ',b.prof_lname) as prof_name, SUM($scores) as total FROM tbl_eval a INNER JOIN tbl_prof b ON a.prof_id = b.prof_id INNER JOIN tbl_period c ON a.period = c.id WHERE b.prof_col = '$college' AND a.prof_id = $prof AND c.id = (SELECT id FROM tbl_period WHERE active = 1) GROUP BY a.prof_id");
					$row_cnt = mysqli_num_rows($sql);
					$sum = array();
					while ($row = mysqli_fetch_assoc($sql)) {
						$totalscores = $row['total']/($row['samp_size']*5);
						array_push($sum, $totalscores);
					}
					array_push($sectionscores, $sum);
				}
				for ($m=0; $m < $row_cnt; $m++) { 
	 				$secave = 0;
	 				$sectotal = 0;
	 				// echo "<tr>";
	 				for ($n=0; $n < $numrows; $n++) { 
	 					echo "<td>".$sectionscores[$n][$m]."</td>";
	 					$sectotal = $sectotal + $sectionscores[$n][$m];

	 				}
	 					echo "<td>".$secave = $sectotal/$numrows."</td>";

	 					if ($secave >= 0.00 && $secave <= 1.99) {
		  					$desc_ave = "Poor";
		  				} elseif ($secave >= 2.00 && $secave <= 2.99) {
		  					$desc_ave = "Fair";
		  				} elseif ($secave >= 3.00 && $secave <= 3.99) {
		  					$desc_ave = "Satisfactory";
		  				} elseif ($secave >= 4.00 && $secave <= 4.99) {
		  					$desc_ave = "Very Satisfactory";
		  				} elseif ($secave >= 5.00) {
		  					$desc_ave = "Outstanding";
		  				}
	 					echo "<td><small>".$desc_ave."</small></td>";
	 					echo "</tr>";
	 			}
			}
 		?>
  		</table>
  		<?php 
  			if ($row_cnt == 0) {
  				echo "<p class='text-center'><small'>Nothing to show here</small></p>";
  			}
  		 ?>
  		<br>
  		<div><button class="btn btn-default" onclick="window.history.back();"><span class="fas fa-chevron-left"></span> Back to List</button></div>
  	</div>	
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>