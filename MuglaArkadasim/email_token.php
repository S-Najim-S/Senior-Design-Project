<?php
include('./classes/DB.php');
  include('./classes/Mail.php');
  $success = false;
  $error = false;

  if ((isset($_POST['resetpassword']))) {
    $email = $_POST['email'];
    if (DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
        // Generate Token using openssl_random_pseudo_bytes function
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
        $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
        DB::query('INSERT INTO password_token VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
        Mail::sendMail('Forgot Password!', "<a href='http://localhost/MuglaArkadasim4/forgotPassword.php?token=$token'>http://localhost/MuglaArkadasim4/forgotPassword.php?token=$token</a>", $email);
        $success = true;
  }else {
    $error = true;
  }
}
 ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="login-dark">

      <div class="col-md-8 offset-md-2">
          <h1 class="text-center" style="color: #a2c9fd;padding-top:80px;">Mugla Arkadasim</h1>
          <h4 style="color: #a2c9fd; text-align:center;">Please help us get you back on track</h4>


          <!-- popup error message if there is any error in the array -->
          <?php if ($error): ?>
            <h5 style="color: red; text-align:center;"><?php echo $email.' address does not exist' ?></h5>
          <?php endif; ?>
          <?php if ($success): ?>
            <h5 style="color: green; text-align:center;"><?php echo "We send you a reset link to ". $email. ", Please login to your email to reset password"; ?></h5>
          <?php endif; ?>
          <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"></iframe></div>
      </div>

        <form action="email_token.php" method="post">
            <h2 class="sr-only">Forgot Password Form</h2>
            <div class="illustration"><i class="icon ion-email"></i></div>
            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
            <div class="form-group"><input class="btn btn-primary btn-block" type="submit" name="resetpassword" value="Send rest link"></input></div><a class="forgot" href="loginPage.php">Back to login page</a></form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
