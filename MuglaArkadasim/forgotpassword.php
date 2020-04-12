<?php
  include('./classes/DB.php');

  if(isset($_POST['resetPassword'])){
     // to send a password reset link to user we can use mail function of php, but as
     // I run this app in local host I will do it later for now I will print the token and then how to reset the password
     $cstrong = True;
     $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
  }
 ?>

<!DOCTYPE html>
<html>

<head>
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body>
  <img class="wave" src="img/wave.png">
  <div class="container">
    <div class="img">
      <img src="img/forgot_password.svg">
    </div>
    <div class="login-content">
      <form action="forgotpassword.php">
        <!-- <img src="img/avatar.svg"> -->
        <div class="textpartc">
          <h3>Forgot your password?</h3>
          <h4 id="leftp">Enter your email addres and we'll get you back on track</h4>
        </div>

        <div class="input-div one">
          <div class="i">
            <i class="fas fa-envelope-open-text"></i>
          </div>
          <div class="div" id="email">
            <h5>E-Mail</h5>
            <input type="text" class="input">
          </div>
        </div>

        <input type="submit" class="btn btn-success mt-2 px-3" style="display:block; margin-left: 22%;" name="resetPassword"value="Request Reset Link">
        <p id="signup"><a href="index.html">back to login</a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
