<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/Image.php');
  $success = false;
  $errors = array();
  $userid = Login::isLoggedIn();
  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];
    $profileimg = DB::query('SELECT profileimg FROM `users` WHERE username=:username',array(':username'=>$loggedInUserName))[0]['profileimg'];
    $email = DB::query('SELECT email FROM `users` WHERE username=:username',array(':username'=>$loggedInUserName))[0]['email'];
    $created_at = DB::query('SELECT users.created_at FROM users
      WHERE username=:username', array(':username'=>$loggedInUserName))[0]['created_at'];
  // Using Login:: to refrence the function
  if (Login::isLoggedIn()) {
      if (isset($_POST['changepassword'])) {
          $oldPassword = $_POST['oldpassword'];
          $newPassword = $_POST['newpassword'];
          $confirmPassword = $_POST['confirmpassword'];

          if (password_verify($oldPassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])) {
              if ($newPassword == $confirmPassword) {
                  echo $newPassword;
                  if (strlen($newPassword) >=6 && strlen($newPassword)<=60) {
                      DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newPassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                      $success = true;
                  } else {
                      $errors['password'] = 'Password length must be more than 7 characters';
                  }
              } else {
                $errors['password'] = "Password doesn't match";

              }
          } else {
            $errors['password'] = 'Incorrect old password';
          }
      }

      if (isset($_POST['updateProfile'])) {
          $conPassword = $_POST['conpassword'];
          $newUsername = $_POST['newUsername'];
          if (password_verify($conPassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])) {
                  if (strlen($newUsername) >=3 && strlen($newUsername)<=32) {
                  // Check if username is consist of valid charachters
                      if (preg_match('/^[a-zA-Z0-9_]*$/', $newUsername)) {
                      DB::query('UPDATE users SET username=:newUsername WHERE id=:userid', array(':newUsername'=>$newUsername, ':userid'=>$userid));
                      // $success = true;
                  } else {
                      $errors['username'] = 'username length must be more than 7 characters';
                  }
              }else{
                $errors['username'] = 'Invalid username charachters';
      }
    }
  }
  if(isset($_POST['uploadImage'])){
    Image::uploadImage('profileimg',"UPDATE users SET profileimg = :profileimg WHERE id=:userid", array(':userid'=>Login::isLoggedIn()));
  }
} else {
      die( "Not Logged in ");
  }




 ?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>MuglaArkadasim</title>
  <link rel="stylesheet" href="css\bootstrap.min.css">
  <link rel="stylesheet" href="css\editProfileStyle.css">
  <!-- <link rel="stylesheet" href="css/style.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body>

  <?php Post::showNavBar($loggedInUserName, 'profile.php?username='.$loggedInUserName); ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-4 pb-5">
        <!-- Account Sidebar-->
        <div class="author-card pb-3">
          <div class="author-card-cover" style="background-image: url(https://demo.createx.studio/createx-html/img/widgets/author/cover.jpg);"></div>
          <div class="author-card-profile">
            <?php if($profileimg == null){
            echo'<div class="author-card-avatar"><img src="./img/profileplaceholder.png" max-height="50px" max-width="220px" alt="User_Image">';
            }else {
              echo'<div class="author-card-avatar"><img src="'.$profileimg.'" alt="User_Image">';

            }?>
            </div>
            <div class="author-card-details">
              <h5 class="author-card-name text-lg"><?php echo $loggedInUserName; ?></h5><span class="author-card-position"><?php echo $created_at;?></span>
            </div>
          </div>
          <form class="col-md-2" style="display:inline-block; margin-top:50px; margin-right:9%;" action="editProfile.php" method="post"  enctype="multipart/form-data">
             <!-- <img src="img/avatar.svg"> -->
             <input type="file" class="btn btn-success mt-2 px-3" name="profileimg" ></input>
             <input value="Upload photo" type="submit" class="btn btn-success mt-2 px-3" name="uploadImage" style="display:block; margin-left: 25%;">
           </form>
        </div>
      </div>
      <!-- Profile Settings-->
      <div class="col-lg-8 pb-5">
        <form class="row" action="editProfile.php" method="post">

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Username</label>
              <input class="form-control" type="text" name="newUsername" id="account-un" value="<?php echo $loggedInUserName; ?>" required="">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-email">E-mail Address</label>
              <input class="form-control" type="email" id="account-email" value="<?php echo $email; ?>" disabled="">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-confirm-pass">Confirm Password</label>
              <input class="form-control" type="password" name="conpassword" id="account-confirm-pass">
            </div>
          </div>
          <div class="col-12">
            <hr class="mt-2 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <input class="btn btn-style-1 btn-success" type="submit" name="updateProfile" value="Update username"></input>
            </div>
          </div>
        </form>

         <!-- change Password -->
        <form class="row" style="margin-top: 20px;" action="editProfile.php" method="post">
          <?php if ($success): ?>
          <div class="alert alert-success col-md-6" style="display:block">
            <li><?php echo "Password Changed"; ?></li>
          </div>
        <?php endif; ?>

        <?php if (count($errors) >0): ?>
        <div class="alert alert-danger col-md-12" style="display:block; text-align: center">
        <?php foreach ($errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Old password</label>
              <input class="form-control" name="oldpassword" type="password" >
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">New password</label>
              <input class="form-control" name="newpassword" type="password" >
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Confirm password</label>
              <input class="form-control" name="confirmpassword" type="password" >
            </div>
          </div>

          <div class="col-12">
            <hr class="mt-2 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <input class="btn btn-style-1 btn-success" name="changepassword" type="submit" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!"
                data-toast-message="Your profile updated successfuly." value="Change Password"></input>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
