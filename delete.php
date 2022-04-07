<?php 
	include 'connect.php';

	$prof_id = $_GET['prof_id'];

	$name = "SELECT CONCAT(prof_fname, ' ', prof_lname) as prof_name FROM tbl_prof";
	$name_res = mysqli_query($connect, $name);
	$prof = mysqli_fetch_assoc($name_res);


	echo "<script>confirm('Are you sure to delete information about ".$prof['prof_name']."?')</script>";

	if (true) {
		$sql = "DELETE FROM tbl_prof WHERE prof_id = $prof_id";
		$res = mysqli_query($connect, $sql);

		echo "<script>Successfully deleted information.
				window.history.back();</script>";
	} else {
		echo "<script>window.history.back();</script>";

	}
 ?>