
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
        Mail::sendMail('Forgot Password!', "<a href='http://localhost/MuglaArkadasim3/forgotPassword.php?token=$token'>http://localhost/MuglaArkadasim3/forgotPassword.php?token=$token</a>", $email);
        $success = true;
  }else {
    $error = true;
  }
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
      <form action="emailtoken.php" method="post">
        <!-- <img src="img/avatar.svg"> -->
        <div class="textpartc">
          <h3>Forgot your password?</h3>
          <h5 id="leftp">Enter your email addres and we'll get you back on track</h5>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success">
          <li><?php echo "We Sent a reset link to this ". $email. ", Please login to your email to reset password"; ?></li>
          <?php $email = ''; ?>
      </div>
      <?php endif;?>

      <?php if ($error): ?>
      <div class="alert alert-danger">
        <li><?php echo "Unregistered email address"; ?></li>
        <?php $email = ''; ?>
    </div>
    <?php endif;?>

        <div class="input-div one">
          <div class="i">
            <i class="fas fa-envelope-open-text"></i>
          </div>
          <div class="div" id="email">
            <input type="email" class="input" name="email" placeholder="E-mail" required>
          </div>
        </div>

        <input type="submit" class="btn btn-success mt-2 px-3" name="resetpassword"style="display:block; margin-left: 24%; margin-bottom:10px;" value="Request Reset Link">
        <p id="signup"><a href="loginPage.php">back to login</a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
