<?php

include('classes/DB.php');

$errors =[];
$success = false;

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
                    DB::query('DELETE FROM password_token WHERE user_id=:userid', array(':userid'=>$userid));
                    $success = true;
                    header('Location:loginPage.php');
                }else{
                  $errors['password'] = 'Invalid password length';

                }
            } else {
                $errors['password'] = 'Passwords don\'t match!';
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
           <!-- <h4 style="color: #a2c9fd; text-align:center;">Log in to get updates about the events around you</h4> -->

           <!-- popup error message if there is any error in the array -->
           <?php if (count($errors) >0): ?>
           <?php foreach ($errors as $error): ?>
             <h5 style="color: red; text-align:center;"><?php echo $error; ?></h5>
           <?php endforeach; ?>
           <?php endif; ?>
           <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"></iframe></div>

         </div>

         <form action="forgotPassword.php?token=<?php echo $token; ?>" method="post">
             <h2 class="sr-only">Login Form</h2>
             <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
             <div class="form-group"><i class="fas fa-user"></i><input class="form-control" type="password" name="newpassword" placeholder="New password" required></div>
             <div class="form-group"><input class="form-control" type="password" name="newpasswordrepeat" placeholder="Confirm password" required></div>
             <a href="email_token.php">Forgot Password?</a>
             <div class="form-group"><input class="btn btn-primary btn-block" value="Save password" name="changepassword" type="submit"></input></div>
             <p id="signup">Don't have an account? <a href="create-account.php">Sign up</a></p>
           </form>
     </div>
     <script src="assets/js/jquery.min.js"></script>
     <script src="assets/bootstrap/js/bootstrap.min.js"></script>
     <script src="assets/js/bs-init.js"></script>
</body>

</html>
