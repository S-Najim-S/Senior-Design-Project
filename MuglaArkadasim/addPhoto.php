<?php
  // include('./classes/uploadImage.php'); 
 ?>

<!DOCTYPE html>
<html>

<head>
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="css\bootstrap.min.css"> -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body>
  <img class="wave" src="img/wave.png">
  <div class="container">
    <div class="img">
      <img src="img/profilephoto.svg">
    </div>
    <div class="login-content">
      <form action="./classes/uploadImage.php" method="post" enctype="multipart/form-data">
        <!-- <img src="img/avatar.svg"> -->
        <div class="textpartc">
          <h3>Set your profile picture</h3>
        </div>

        <div id="avatar-img">
          <img src="img/avatar.svg">
        </div>

        <div class="input-div one">
        </div>

        <input type="file" name="profileimg" class=" btn btn-success mt-2 px-3" style="display:block; margin-left:5%;">
        <input value="Upload & continue" type="submit" class=" btn btn-success mt-2 px-3" name="uploadprofileimg">
        <p id="signup"><a href="index.html" style="margin-top:11px;"> <strong>Skip</strong></a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
