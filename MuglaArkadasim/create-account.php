<?php
  include('classes/DB.php');
  include('classes/Mail.php');


  $pdo = new PDO('mysql:127.0.0.1=localhost;dbname=mynetwork;chartset=utf8', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $errors = array();
  $success = false;
  $username = '';
  $email = '';
  $type = '';
  $gender = '';

  if (isset($_POST['createaccount'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $gender = $_POST['gender'];
      $type = $_POST['type'];
      $confPassword = $_POST['coPassword'];

      $cstrong = True;
      $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
      echo $token;


      if ($type === 'staff') {
          $email = $username.'@mu.edu.tr';
          if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email)) && !DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
              Mail::sendMail('Welcome to Mugla Arkadasim', 'Your account has been created', $email);
          }
      } else if($type === 'student'){
        $email = $username.'@posta.mu.edu.tr';
        if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email)) && !DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
            Mail::sendMail('Verification','<a href="http://localhost/MuglaArkadasim3/verify.php?token='.$token.'">Register Account</a>', $email);
      }
    }else {
      $errors['email'] = "Can't send Email";
    }

      // Check if user exists
      if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

          //check if email already exists
          if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

          // Check length of the username
              if (strlen($username) >=3 && strlen($username)<=32) {

              // Check if username is consist of valid charachters
                  if (preg_match('/^[a-zA-Z0-9_]*$/', $username)) {

                  // Passwords consisting of minimum charachters
                      if (strlen($password) >=8 && strlen($password)<=60) {
                          if ($password == $confPassword) {

                      // Check if E-mail is valid
                              if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                        // Insert Fields into the database and the password as hash value
                                  DB::query('INSERT INTO users VALUES(\'\', :username, :email, :password, :gender, :type, \'\',0, :token)', array(':username'=>$username,':email'=>$email,':password'=>password_hash($password, PASSWORD_BCRYPT),':gender'=>$gender,':type'=>$type, ':token'=>sha1($token)));
                                  $success = true;
                              } else {
                                  $errors['email'] = "Invalid e-mail";
                              }
                          } else {
                              $errors['coPassword'] = "Passwords does not match";
                          }
                      } else {
                          $errors['password'] = "invalid Passwords length";
                      }
                  } else {
                      $errors['username'] = "Invalid characters in username";
                  }
              } else {
                  $errors['username'] = "Invalid in user length";
              }
          } else {
              $errors['email'] = "email already exists";
          }
      } else {
          $errors['username'] = "User already exists";
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700">
  <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="./css/style.css">

</head>

<body>
  <div class="login-dark" >
    <div class="col-md-8 offset-md-2">
        <h1 class="text-center" style="color: #a2c9fd;padding-top:30px;">Mugla Arkadasim</h1>
        <h4 style="color: #a2c9fd; text-align:center;">Signup to get updates about the events around you</h4>


        <!-- popup error message if there is any error in the array -->
        <?php if (count($errors) >0): ?>
        <?php foreach ($errors as $error): ?>
          <h5 style="color: red; text-align:center;"><?php echo $error; ?></h5>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($success): ?>
          <h5 style="color: green; text-align:center;"><?php echo "We have sent an e-mail to ".$email.", login to verify"; ?></h5>
          <?php $username = '';
            $email = ''; ?>
            <?php endif;?>

        <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"></iframe></div>
    </div>
    <form action="create-account.php" method="post">
      <h2 class="sr-only">Login Form</h2>
      <div class="illustration">
        <i class="icon ion-android-create"></i>
      </div>
      <div class="form-group" >
        <input class="form-control"  type="text" name="username" value="<?php echo $username ?>" placeholder="University username" required="">
      </div>
      <div class="form-group">
        <input class="form-control" type="password" name="password" placeholder="Password" required="">
      </div>
      <input class="form-control" type="password" name="coPassword" placeholder="Confirm password" required>

      <div class="form-check form-check-inline" style="margin:10px 10px;">
        <input class="form-check-input" name="type" value="staff" type="radio" required>
        <label class="form-check-label" for="formCheck-1">Staff</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="type" value="student"type="radio" required>
        <label class="form-check-label" for="formCheck-2">Student</label>
      </div>

      <div class="">
      </div>

      <div class="form-check form-check-inline" style="margin:10px 10px;">
        <input class="form-check-input" name="gender" value="male" type="radio" id="formCheck-3">
        <label class="form-check-label" for="formCheck-3">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="gender" value="female" type="radio" id="formCheck-4">
        <label class="form-check-label" for="formCheck-4">Female</label>
      </div>
      <div class="form-group">
        <input value="Signup"class="btn btn-primary btn-block" type="submit" name="createaccount">
      </div>
        <p id="signup">Already have an account? <a href="loginPage.php"> <strong>Sign in</strong></a></p>
    </form>
  </div>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
