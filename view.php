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
			  			$sql1 = "SELECT * FROM tbl_eval a INNER JOIN tbl_period b ON a.period = b.id WHERE a.prof_id = $prof_id AND b.id = (SELECT id FROM tbl_period WHERE active = 1)";
			  			$res = mysqli_query($connect, $sql1);
			  			$row_cnt = mysqli_num_rows($res);
				  		$number = 1;

				  		$forran = mysqli_query($connect, "SELECT * FROM tbl_ques");
				  		$cntr = mysqli_num_rows($forran);
				  		$counter = range(0, $cntr, 5);

			  			if ($row_cnt != 0) {
				  			while ($score = mysqli_fetch_assoc($res)) {
				  				echo "<tr>";
				  				echo "<td><strong>".$number."</strong></td>";

				  				for ($a=0; $a < count($counter); $a++) { 
				  					$min = $counter[$a];
				  					$max = next($counter);
					  				for ($i=$min; $i < $max; $i++) { 
					  					echo "<td>".$score['score'.$i.'']."</td>";
					  				}
				  				}
				  				echo "</tr>";
				  				$number++;
				  			}
			  			}
			  		?>
  				</tbody>
  			</table>
  			<?php 
  				$sql = mysqli_query($connect, "SELECT question1, question2, question3, question4 FROM tbl_eval WHERE prof_id = $prof_id AND period = (SELECT id FROM tbl_period WHERE active = 1)");

  				while ($open = mysqli_fetch_array($sql)) {
  					echo "<div class='card'><div class='card-body'>";
  					echo "<strong>The course should: </strong>".$open['question1']."<br>";
  					echo "<strong>Strengths of the faculty: </strong>".$open['question2']."<br>";
  					echo "<strong>Suggestions for improvement: </strong>".$open['question3']."<br>";
  					echo "<strong>Impressions of the faculty: </strong>".$open['question4']."<br>";
  					echo "</div></div>";
  				}

  				if ($row_cnt == 0) {
  					echo "<center><small>Nothing to show here.</small></center>";
  				}
  			 ?>
  		<br>
  		<div><button class="btn btn-default" onclick="window.history.back();"><span class="fas fa-chevron-left"></span> Back to List</button></div>
  	</div>
	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>