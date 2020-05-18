<?php

include('classes/DB.php');
$errors = array();
$username = '';
if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

                if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                      $verified = DB::query('SELECT verified FROM users WHERE username=:username',array(':username'=>$username))[0]['verified'];
                      if($verified ==1){
                        // Generate Token using openssl_random_pseudo_bytes function
                        $cstrong = True;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                        $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                        DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));

                        // Stores user cookie for 7 days and delets it once it expires
                        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7,'/' , NULL, NULL, TRUE );
                        // After 3 days the cookie expires and forces user to ask for a new cookie
                        setcookie("SNID_", '1', time() +60 * 60 * 24 * 3, '/', NULL, NULL, True);

                        header('Location:index1.php');
                      }else {
                        $errors['email']= "Please verify your email";
                      }

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
            <h4 style="color: #a2c9fd; text-align:center;">Log in to get updates about the events around you</h4>


            <!-- popup error message if there is any error in the array -->
            <?php if (count($errors) >0): ?>
            <?php foreach ($errors as $error): ?>
              <h5 style="color: red; text-align:center;"><?php echo $error; ?></h5>
            <?php endforeach; ?>
            <?php endif; ?>
            <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"></iframe></div>
        </div>

          <form action="loginPage.php" method="post">
              <h2 class="sr-only">Login Form</h2>
              <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
              <div class="form-group"><i class="fas fa-user"></i><input class="form-control" type="text" value="<?php echo $username; ?>" name="username" placeholder="Username" required></div>
              <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
              <a href="email_token.php">Forgot Password?</a>
              <div class="form-group"><input class="btn btn-primary btn-block" name="login" type="submit" value="Login"></button></div>
              <p id="signup">Don't have an account? <a href="create-account.php">Sign up</a></p>
            </form>
      </div>
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/bootstrap/js/bootstrap.min.js"></script>
      <script src="assets/js/bs-init.js"></script>
</body>

</html>
