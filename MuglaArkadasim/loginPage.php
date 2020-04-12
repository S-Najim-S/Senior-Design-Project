<?php

include('classes/DB.php');
$errors = array();
$username = '';
if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

                if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                        echo 'Logged in!';

                        // Generate Token using openssl_random_pseudo_bytes function
                        $cstrong = True;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                        $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                        DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));

                        // Stores user cookie for 7 days and delets it once it expires
                        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7,'/' , NULL, NULL, TRUE );
                        // After 3 days the cookie expires and forces user to ask for a new cookie
                        setcookie("SNID_", '1', time() +60 * 60 * 24 * 3, '/', NULL, NULL, True);

                        header('Location:homepage.php');
                } else {
                        $errors['password'] = 'Incorrect password';

                }

        } else {
          $errors['username'] = 'User not registered';
        }

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
      <img src="img/background.svg">
    </div>
    <div class="login-content">
      <form action="loginPage.php" method="post">
        <!-- <img src="img/avatar.svg"> -->
          <div class="textpart">
          <h4>Welcome to</h4>
          <h3>MuglaArkadasim</h3>
          <h4 id="leftp">Log in to get updates about the events around you</h4>
        </div>

        <!-- popup error message if there is any error in the array -->
        <?php if (count($errors) >0): ?>
        <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>


          <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <input type="text" name="username" value="<?php echo $username; ?>"class="input" placeholder="Username" required>
          </div>
        </div>
        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <input type="password" class="input" name="password" placeholder="Password" required>
          </div>
        </div>
        <a href="forgotpassword.html">Forgot Password?</a>
        <input value="login" name="login" type="submit" class="btn btn-success mt-2 px-3"  id="login" style="display:block; margin-left: 40%;">
        <p id="signup">Don't have an account? <a href="createUser.html">Sign up</a></p>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
