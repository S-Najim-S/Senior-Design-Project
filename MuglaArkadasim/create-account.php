<?php
  include('classes/DB.php');

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
      $email = $_POST['email'];
      $password = $_POST['password'];
      $gender = $_POST['gender'];
      $type = $_POST['type'];
      $confPassword = $_POST['coPassword'];


      // Check if user exists
      if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

          //check if email already exists
          if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

          // Check length of the username
              if (strlen($username) >=6 && strlen($username)<=32) {

              // Check if username is consist of valid charachters
                  if (preg_match('/^[a-zA-Z0-9_]*$/', $username)) {

                  // Passwords consisting of minimum charachters
                      if (strlen($password) >=8 && strlen($password)<=60) {

                          if ($password == $confPassword) {

                      // Check if E-mail is valid
                          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                        // Insert Fields into the database and the password as hash value
                              DB::query('INSERT INTO users VALUES(\'\', :username, :email, :password, :gender, :type)', array(':username'=>$username,':email'=>$email,':password'=>password_hash($password, PASSWORD_BCRYPT),':gender'=>$gender,':type'=>$type));
                              $success = true;
                          } else {
                              $errors['email'] = "Invalid e-mail";
                          }
                      } else {
                          $errors['coPassword'] = "Passwords does not match";
                      }
                    }else {
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
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body>
  <img class="wave" src="img/wave.png">
  <div class="container">
    <div class="img">
      <img src="img/createbg.svg">
    </div>
    <div class="login-content">
      <form action="create-account.php" method="post">
        <!-- <img src="img/avatar.svg"> -->
        <div class="textpartc">
          <h3>MuglaArkadasim</h3>
          <h4 id="leftp">Sign up to get updates about events happening around you</h4>
        </div>

        <!-- popup error message if there is any error in the array -->
        <?php if (count($errors) >0): ?>
        <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($success): ?>
      <div class="alert alert-success">
        <li><?php echo "Check". $email. ", and verify to login"; ?></li>
        <?php $username = '';
        $email = ''; ?>
    </div>
    <?php endif;?>


        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <input type="text" name="username" value="<?php echo $username ?>" class="input" placeholder="Username" required>
          </div>
        </div>

        <div class="input-div one">
          <div class="i">
            <i class="fas fa-envelope-open-text"></i>
          </div>
          <div class="div" id="email">
            <input type="email" name="email"class="input"  value="<?php echo $email ?>"placeholder="University email address" required>
          </div>
        </div>


        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <input type="password" name="password" class="input" placeholder="Password" required>
          </div>
        </div>

        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <input type="password" name="coPassword" placeholder="Confirm password" class="input" required>
          </div>
        </div>

        <div class="gender">
          <input type="radio"  name="gender" id="male" value="male" required>
          <label for="male" >Male</label>
          <input type="radio"  name="gender" id="female" value="female" required>
          <label for="female" >Female</label>

        </div>

        <div class="userType">
          <input type="radio"  name="type" id="teacher" value="teacher" required>
          <label for="teacher" >Teacher</label>

          <input type="radio"  name="type" id="student" value="student" required>
          <label for="student" >Student</label>

        </div>

        <input value="signup" type="submit" class="btn btn-success mt-2 px-3"  name="createaccount" style="display:block; margin-left: 40%; margin-bottom:5px;">
        <p id="signup">Already have an account? <a href="index.html"> <strong>Sign in</strong></a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
