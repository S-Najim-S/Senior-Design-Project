<?php

include('classes/DB.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    if (DB::query('SELECT user_id FROM password_token WHERE token=:token', array(':token'=>sha1($token)))) {
        $userid = DB::query('SELECT user_id FROM password_token WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
        $tokenIsValid = true;
        if (isset($_POST['changepassword'])) {
            $newpassword = $_POST['newpassword'];
            $newpasswordrepeat = $_POST['newpasswordrepeat'];

            if ($newpassword == $newpasswordrepeat) {
                if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                    DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                    echo 'Password changed successfully!';
                    DB::query('DELETE FROM password_token WHERE user_id=:userid', array(':userid'=>$userid));
                }
            } else {
                echo 'Passwords don\'t match!';
            }
        }
    } else {
        die('Token invalid');
    }
} else {
    die('Not logged in');
}

 ?>

<!DOCTYPE html>
<html>

<head>
      <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/album/">
      <link rel="stylesheet" href="css\bootstrap.min.css">
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body>
  <img class="wave" src="img/wave.png">
  <div class="container">
    <div class="img">
      <img src="img/newPassword.svg">
    </div>
    <div class="login-content">
      <form action="forgotPassword.php" method="post">
        <!-- <img src="img/avatar.svg"> -->
          <div class="textpart">
          <h4>Welcome to</h4>
          <h3>MuglaArkadasim</h3>
        </div>

        <!-- popup error message if there is any error in the array -->
        <!-- <?php if (count($errors) >0): ?>
        <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </div>
      <?php endif; ?> -->


      <div class="input-div pass">
        <div class="i">
          <i class="fas fa-lock"></i>
        </div>
        <div class="div">
          <input type="password" class="input" name="password" placeholder="New Password" required>
        </div>
      </div>

        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <input type="password" class="input" name="newpasswordrepeat" placeholder="Confirm Password" required>
          </div>
        </div>
        <div style="margin-top:25px;">
        <input value="Save password" name="changepassword" type="submit" class="btn btn-success mt-2 px-3" style="display:block; margin-left: 30%;">
      </div>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
