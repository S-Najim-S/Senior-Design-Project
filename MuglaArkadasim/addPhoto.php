<?php

include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Image.php');

if (Login::isLoggedIn()) {
  $userid = Login::isLoggedIn();
}else {
  die("Please Login to proceed!");
}

if(isset($_POST['uploadImage'])){
  Image::uploadImage('profileimg',"UPDATE users SET profileimg = :profileimg WHERE id=:userid", array(':userid'=>$userid));
}



 ?>

<!DOCTYPE html>
<html>

<head>
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css\bootstrap.min.css">
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
    <form class="col-md-7" style="display:inline-block; margin-top:50px; margin-right:9%;" action="addPhoto.php" method="post"  enctype="multipart/form-data">
        <!-- <img src="img/avatar.svg"> -->
        <div class="textpartc">
          <h3>Set your profile picture</h3>
        </div>

        <div id="avatar-img">
          <img src="img/avatar.svg">
        </div>

        <div class="input-div one">
        </div>


        <input type="file" class="btn btn-success mt-2 px-3" name="profileimg"></input>
        <input value="Upload & continue" type="submit" class="btn btn-success mt-2 px-3" name="uploadImage" style="display:block; margin-left: 25%;">
        <p id="signup"><a href="index1.php" style="margin-top:11px;"> <strong>Skip</strong></a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
