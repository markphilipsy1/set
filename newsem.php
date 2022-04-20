<?php  
  include 'connect.php';
  session_start();

  // if ($_SESSION['access'] != 'student') {
  //   header('location:index.php');
  //   session_destroy();
  // }


  if (isset($_POST['newsem'])) {
    $newsem = $_POST['sem'];
    $newyear = $_POST['year'];

    $sql = mysqli_query($connect, "SELECT * FROM tbl_period WHERE semester = '$newsem' AND year = $newyear");
    $row = mysqli_num_rows($sql);
    
    if ($row != 0) {
      echo "<script>alert('Semester and Year already exist!')
            window.location.href = 'newsem.php'</script>"; 
    } else {
      $sql = mysqli_query($connect, "UPDATE tbl_period SET active = 0 WHERE id = (SELECT MAX(id) FROM tbl_period)");
      $sql1 = mysqli_query($connect, "INSERT INTO tbl_period values (NULL, '$newsem', $newyear, 1)");
      echo "<script>alert('New semester created!')
            window.location.href = 'prof.php'</script>";
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
      <p><strong>Please specify semester and year</strong></p>
      <form method="POST" class="form-group">
        <div class="row">
          <label for="sem">SEMESTER</label>
          <div class="col-sm-2">
              <select id="sem" name="sem" class="custom-select">
                <option value="first">1st SEM</option>
                <option value="second">2nd SEM</option>
                </select>
            </div>
          <label for="schyear">School Year</label>
            <div class="col-sm-2">
              <input type="number" name="year" min="2020" max="2060" step="1" value="2021" class="form-control">
            </div>
              <button type="submit" class="btn btn-light" name="newsem" style="height: 30pt;">Add New Semester</button>
        </div>
      </form>
    </div>
  <script type="text/javascript" src="bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
