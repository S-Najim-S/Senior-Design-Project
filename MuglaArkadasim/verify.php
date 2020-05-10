<?php
include('classes/DB.php');
$success = false;

  if(isset($_GET['token'])){
    $token = $_GET['token'];
    $result = DB::query('SELECT verified, token from users where verified = 0 AND token=:token LIMIT 1', array(':token'=>sha1($token)))[0]['verified'];

   if ($result == 0) {
     // Validate the email
     $update = DB::query('UPDATE users SET verified = 1 WHERE token=:token', array(':token'=>sha1($token)));
     $success = true;
   }else {
     echo "It's already Verified";
   }
  }else {
    die("Something went Wrong!");
  }
 ?>

 <?php if($success == true):?>
 <!DOCTYPE html>
 <html>

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
     <title>MuglaArkadasim</title>
     <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700">
     <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
     <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
     <link rel="stylesheet" href="assets/css/styles.css">
 </head>

 <body>
     <div class="login-dark-verify">
         <form class="col-md-4">
             <h2 class="sr-only">Login Form</h2>
             <div class="illustration"><i class="icon ion-android-done" style="color: rgb(41,132,239);"></i></div>
             <div class="col-md-8 offset-md-2">
                 <h3 style="color: #a2c9fd; text-align:center;">Your email has been verified</h3>
                 <h6 style="color: #a2c9fd; text-align:center; margin:15px;">You can now sign in with your new account</h6>

                 <a class="signinLink"href="loginPage.php" style="margin-left:40%;"><strong>Go to sign in</strong></a>
         </form>
     </div>
     <script src="assets/js/jquery.min.js"></script>
     <script src="assets/bootstrap/js/bootstrap.min.js"></script>
 </body>

 </html>
<?php endif; ?>
