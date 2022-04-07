<?php 
	include 'connect.php';
	session_start();

	if ($_SESSION['access'] == 'student') {
		$prof_id = $_GET['prof_id'];
		$stud_id = $_SESSION['studentnumber'];
		$semyr = $_SESSION['cursemyr'];

		$sql = "SELECT prof_id, concat(prof_fname,' ',prof_lname) AS prof_name FROM tbl_prof WHERE prof_id = $prof_id";

		$res = mysqli_query($connect, $sql);
		$inst = mysqli_fetch_assoc($res);

		if (isset($_POST['sub'])) {
			$sql2 = "SELECT * FROM tbl_ques";
			$res2 = mysqli_query($connect, $sql2);
			$rw_cnt = mysqli_num_rows($res2);

			for ($i=1; $i <= $rw_cnt; $i++) { 
				$ans[$i] = $_POST["aq".$i];
			}

			$evalscore = implode(", ", $ans);

			$ins_ans = "INSERT INTO $semyr values (NULL, $stud_id, $prof_id, $evalscore)";
			$res3 = mysqli_query($connect, $ins_ans);

			if ($res3) {
				echo "<script>alert('Scores are submitted!');
						window.location.href='studProf.php';</script>";
				// header("location:studProf.php");
			} else {
				echo "<script>alert('Scores not submitted!');
						window.location.href='studProf.php';</script>";
				// header("location:studProf.php");
			// header("location:studProf.php");
			}
		}

	} elseif ($_SESSION['access'] == 'admin') {
		$prof_id = 'None';
		$inst['prof_name'] = 'None';
	}

	if (isset($_POST['logout'])) {
		header('location: index.php');
		session_destroy();
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
 	<title>CvSU | SET</title>
 	<style type="text/css">
 		body {
 			padding-top: 4em;
 		}

 		#teacher {
 			text-indent: 5em;
 			font-size: 16pt;
 			font-weight: bold;
 		}
 	</style>
</head>
<body>
	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #287722;">
    	<a class="navbar-brand"><img src="img/cvsu.png" style="width: 50px;"> CvSU Student Evaluation for Teachers</a>
      	<ul class="navbar-nav ml-auto mt-2 mt-lg-0" role="tablist">
        	<li class="nav-item"><a class="nav-link" href="prof.php" id="col"><span class="fas fa-chevron-left"></span> Back to Colleges</a></li>
        	<li class="nav-item"><a class="nav-link" href="#" id="stud" onclick="window.history.back();"><span class="fas fa-home"></span> Back to Profile</a></li>
      	</ul>
  	</nav>
  	<p id="demo"></p>
	<form method="POST">
 		<div class="container form-group">
	 		<div class="card mb-3">
			  	<div class="card-header bg-success text-white"><strong>Instructions</strong></div>
			  	<div class="card-body">
			    	<h5 class="card-title">Please evaluate the faculty using the scale below:</h5>
			    	<div class="text-center">			    		
				    	<div class="row">
				    		<div class="col-sm-1"><strong>Scale</strong></div>
				    		<div class="col-sm-4"><strong>Descriptive Rating</strong></div>
				    		<div class="col-sm-6"><strong>Qualitative Description</strong></div>
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1">5</div>
				    		<div class="col-sm-4">Outstanding</div>
				    		<div class="col-sm-6">The performance almost always exceeds the job requirements. The Faculty is an exceptional role model.</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1">4</div>
				    		<div class="col-sm-4">Very Satisfactory</div>
				    		<div class="col-sm-6">The performance meets and often exceeds the job requirements</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1">3</div>
				    		<div class="col-sm-4">Satisfactory</div>
				    		<div class="col-sm-6">The performance meets job requirements.</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1">2</div>
				    		<div class="col-sm-4">Fair</div>
				    		<div class="col-sm-6">The performance needs some development to meet job requirements.</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1">1</div>
				    		<div class="col-sm-4">Poor</div>
				    		<div class="col-sm-6">The faculty fails to meet job requirements.</div>
				    	</div>
			    	</div>
				</div>
			</div>
			<hr>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
				    <label class="input-group-text bg-success text-white" for="prof">Teacher</label>
				</div>
				<input id="teacher" type="text" name="prof" value="<?php echo $inst['prof_name'] ?>" class="form-control form-control-plaintext" readonly>
			</div>

			<!-- Questions -->
			<?php 
				$section = mysqli_query($connect, "SELECT * FROM questionsection");
				$count = mysqli_num_rows($section);

				for ($i=1; $i <= $count; $i++) {
					$sql = "SELECT a.ques_id, a.questions, b.section, b.sectionname FROM tbl_ques a INNER JOIN questionsection b ON a.section = b.section WHERE a.section = $i";
					$res = mysqli_query($connect, $sql);

					$sectionname1 = mysqli_query($connect, "SELECT * FROM questionsection WHERE section = $i");

					?>

					<div class="card">
						<div class="card-header bg-success text-white">
							<?php 
								while ($sectionname = mysqli_fetch_assoc($sectionname1)) {
									echo "<strong>".$sectionname['sectionname']."</strong>";
									echo "<button class='btn btn-default float-right' name='delete' type='submit' onclick='tanggal(".$sectionname['section'].")'><span class='fas fa-window-close'></span></button>";
								}
							 ?>
						</div>
						<ul class="list-group list-group-flush">
							<?php
								while ($ques = mysqli_fetch_assoc($res)) {

									echo "<li class='list-group-item'>";
									echo "<textarea row='3' style='resize:none;' class='form-control-plaintext h6'  id='q" .$ques['ques_id']. "' value='ehe' readonly>" .$ques['questions']. "</textarea>
										<button class='btn btn-primary float-right' onclick='save(".$ques['ques_id'].")'><span class='fa fa-save'></span></button>
										<button class='btn btn-light float-right' onclick='edit(".$ques['ques_id'].")'><span class='fa fa-edit'></span></button>";
									?>
									<div class="row text-center">
										<div class="col">Outstanding</div>
									<?php
									for ($j=5; $j > 0 ; $j--) { 
										echo "<div class='col-sm-1 custom-control custom-radio'>";
										echo "<input type='radio' name='aq".$ques['ques_id']."' id='a"  .$ques['ques_id'].$j. "' value='" .$j. "' class='custom-control-input' required>";
				    					echo "<label class='custom-control-label' for='a" .$ques['ques_id'].$j. "'>" .$j. "</label>";
				    					echo "</div>";
									}
									echo "<div class='col'>Poor</div>";
									echo "</div></li>";
								}
							?>
						</ul>
		 			</div>
		 			<br>
					<?php
				}
			 ?>

	 		<hr>
			<br>
	 		<input type="submit" id="sub" name="sub" class="btn btn-success form-control" value="Submit">

	 		<a href="addsection.php"><input type="button" id="add" name="add" class="btn btn-success form-control" value="Add new Section"></a>
 	</form>
 	<script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script>
		var access = "<?php echo $_SESSION['access']; ?>";
		var j = document.getElementsByTagName('button');

		if (access == 'student') {
			document.getElementById('col').style.display = 'none';
			// document.getElementById('delete').style.display = 'none';
			document.getElementById('add').style.display = 'none';
			for (var n in j) {
				if (j[n].type = 'button') {
					j[n].style.display = 'none';
				}
			}
		} if (access == 'admin'){
			document.getElementById('stud').style.display = 'none';
			document.getElementById('sub').style.display = 'none';
			for (var n in j) {
				if (j[n].type = 'button') {
					j[n].style.display = 'default';
				}
			}
		}

		function edit(qid) {
			document.getElementById('q'+qid).removeAttribute('readonly');
		}

		function save(qid) {
			var ques = document.getElementById('q'+qid).value;
			document.getElementById('q'+qid).setAttribute('readonly', 'true');
			location.href = 'save_ques.php?qid='+qid+'&question='+ques;
		}

		function tanggal(section) {
			var section = section;
			confirm('Are you sure you want to delete this section?');
			if (true) {
				location.href = 'deletesection.php?section='+section;
			}
		}
		</script>
</body>
</html>