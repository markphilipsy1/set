<?php  
	include 'connect.php';
	session_start();

	if ($_SESSION['access'] != 'admin') {
		header('location:index.php');
		session_destroy();
	}
	
	$college = $_GET['prof_col'];

	$sql = mysqli_query($connect, "SELECT * FROM questionsection");

	//comment report
	if (isset($_GET['tocsv'])) {
		$pid = mysqli_query($connect, "SELECT prof_id FROM tbl_prof WHERE prof_col = '$college'");

		$listpid = array();
		while ($rwpid = mysqli_fetch_assoc($pid)) {
			array_push($listpid, $rwpid['prof_id']);
		}

		// print_r($listpid);
		$applicants = array();
		$header = array('Instructor', 'Comments', 'Strengths', 'Suggestions', 'Impressions');
		array_push($applicants, $header);

		// $comments = array();
		for ($i=0; $i < count($listpid); $i++) { 
			$sqlrep = mysqli_query($connect, "SELECT CONCAT(a.prof_lname, ' ', a.prof_fname) as prof_name, b.question1, b.question2, b.question3, b.question4 FROM tbl_prof a RIGHT JOIN tbl_eval b ON a.prof_id = b.prof_id WHERE a.prof_id = $listpid[$i]");

			// echo $listpid[$i];
			if ($sqlrep) {
				while ($comm = mysqli_fetch_assoc($sqlrep)) {
					array_push($applicants, $comm);
				}
			}
		}
		date_default_timezone_set('Asia/Manila');
		array_to_csv_download($applicants,
			"Comments - ".date("Y-m-d H:i:s").".csv"
		);
	}

	function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
	    header('Content-Encoding: UTF-8');
	    header('Content-Type: text/csv;charset=UTF-8');
	    header('Content-Disposition: attachment; filename="'.$filename.'";');
	    echo "\xEF\xBB\xBF"; 

	    $f = fopen('php://output', 'w');

	    foreach ($array as $line) {
	        fputcsv($f, $line, $delimiter);
	    }
	}
	
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
  	<br>
  	<div class="container">
  		<div class="float-right"><button class="btn btn-success" onclick="tableToCSV();"><span class="fas fa-download"></span> Download Scores as CSV</button></div>
  		<div class="float-right"><button class="btn btn-success"><a href="report_main(test).php?prof_col=<?php echo $college ?>&tocsv=true" class="text-decoration-none text-white"><span class="fas fa-download"></span> Download Comments as CSV</a></button></div>
  		<br>
  		<br>
  		<table class="table table-sm text-center" id="reports">
  			<tr class="text-center" style="font-weight: bold;">
  				<td scope="col" class="align-middle">Name of Faculty</td>
  				<td scope="col" class="align-middle">Sample Size</td>
  				<?php 
  					while ($row = mysqli_fetch_array($sql)) {
  						echo "<td class='align-middle'>".$row['sectionname']."</td>";
  					}
  				 ?>
  				<td>Numerical Rating</td>
  				<td>Adjectival Rating</td>
  			</tr>
  			<tr>
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
	 					echo "<td>".$desc_ave."</td>";
	 					echo "</tr>";
	 			}
			} //end thescore()
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
	<script type="text/javascript">
        function tableToCSV() {
 
            var csv_data = [];
 
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
 
                var cols = rows[i].querySelectorAll('td,th');
 
                var csvrow = [];
                for (var j = 0; j < cols.length; j++) {
 
                    csvrow.push(cols[j].innerHTML);
                }
 
                csv_data.push(csvrow.join(","));
            }
 
            csv_data = csv_data.join('\n');
 
            downloadCSVFile(csv_data);
 
        }
 
        function downloadCSVFile(csv_data) {
 
            CSVFile = new Blob([csv_data], {
                type: "text/csv"
            });
 
            var temp_link = document.createElement('a');
 
            temp_link.download = "Report.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;
 
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);
 
            temp_link.click();
            document.body.removeChild(temp_link);
        }
    </script>
</body>
</html>