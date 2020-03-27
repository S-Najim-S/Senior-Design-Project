<?php
  include('classes/DB.php');

  $pdo = new PDO('mysql:127.0.0.1=localhost;dbname=SocialNetwork;chartset=utf8', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_POST['createaccount'])) {
    $fname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $type = $_POST['type'];

    // Check if user exists
    if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){


          // Check length of the username
          if(strlen($username) >=3 && strlen($username)<=32){

              // Check if username is consist of valid charachters
              if(preg_match('/[a-zA-Z0-9_]+/', $username)){

                  // Passwords consisting of minimum charachters
                  if (strlen($password) >=8 && strlen($password)<=60) {

                      // Check if E-mail is valid
                      if(filter_var($email, FILTER_VALIDATE_EMAIL)){

          // Insert Fields into the database and the password as hash value
          DB::query('INSERT INTO users VALUES(\'\',:fname, :username, :email, :password, :gender, :type)',array(':fname'=>$fname,':username'=>$username,':email'=>$email,':password'=>password_hash($password,PASSWORD_BCRYPT),':gender'=>$gender,':type'=>$type));
          echo "Success";
        } else {
          echo "Invalid e-mail!";
        }
      }else {
        echo "Invalid password length!";
      }

        } else {
          echo "Invalid characters in Username!";
        }

        } else {
          echo "Invalid length Username!";
        }

      } else {
        echo "User already exists!";
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
        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <h5>FullName</h5>
            <input type="text" name="fullname" value="" class="input">
          </div>
        </div>


        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <h5>Username</h5>
            <input type="text" name="username" value="" class="input">
          </div>
        </div>

        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <h5>Password</h5>
            <input type="password" name="password" class="input">
          </div>
        </div>


        <div class="input-div one">
          <div class="i">
            <i class="fas fa-envelope-open-text"></i>
          </div>
          <div class="div" id="email">
            <h5>E-Mail</h5>
            <input type="text" name="email"class="input">
          </div>
        </div>

        <div class="gender">
          <input type="radio"  name="gender" id="male" value="male" >
          <label for="male" >Male</label>

          <input type="radio"  name="gender" id="female" value="female">
          <label for="female" >Female</label>

        </div>

        <div class="userType">
          <input type="radio"  name="type" id="teacher" value="teacher" >
          <label for="teacher" >Teacher</label>

          <input type="radio"  name="type" id="student" value="student">
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
