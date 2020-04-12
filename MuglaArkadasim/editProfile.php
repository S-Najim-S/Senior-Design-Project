<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  $success = false;
  $errors = array();

  // Using Login:: to refrence the function
  if (Login::isLoggedIn()) {
      if (isset($_POST['changepassword'])) {
          $oldPassword = $_POST['oldpassword'];
          $newPassword = $_POST['newpassword'];
          $confirmPassword = $_POST['confirmpassword'];
          $userid = Login::isLoggedIn();
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

  <div class="navbar-div">
    <nav class="navbar navbar-expand-md navbar-dark bg-blue">
      <a class="navbar-brand logo" href="#">MuglaArkadasim</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item ">
            <a class="nav-link" href="userTimeline.html"><i class=" usercircle far fa-user-circle fa-lg"></i> User_name</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="joinClub.html">Clubs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="joinChatrooms.html">Chatrooms</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="homepage.html">Logout</a>
          </li>

          <li class="nav-item">
            <a class="nav-link fas fa-bell fa-sx" style="
    margin-top:4px;" href="#"></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link fas fa-caret-down fa-lg" id="navbarDropdown" role="button" data-toggle="dropdown" style="margin-top:4px;" href="#"></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a href="editProfile.html">Edit profile</a>
              <div class="dropdown-divider"></div>
              <a href="createClub.html">Create club</a>
              <div class="dropdown-divider"></div>
              <a href="createChatroom.html">Create chatroom</a>
              <div class="dropdown-divider"></div>
              <a href="index.html">Logout</a>
            </div>
          </li>
        </ul>

        <form class="form-inline">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-light my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>
  </div>
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-4 pb-5">
        <!-- Account Sidebar-->
        <div class="author-card pb-3">
          <div class="author-card-cover" style="background-image: url(https://demo.createx.studio/createx-html/img/widgets/author/cover.jpg);"></div>
          <div class="author-card-profile">
            <div class="author-card-avatar"><img src="img\userimage.png" alt="User_Image">
            </div>
            <div class="author-card-details">
              <h5 class="author-card-name text-lg">S.Najim_1177</h5><span class="author-card-position">Joined March 06, 2017</span>
            </div>
          </div>
        </div>
      </div>
      <!-- Profile Settings-->
      <div class="col-lg-8 pb-5">
        <form class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Username</label>
              <input class="form-control" type="text" id="account-un" value="S.Najim_1177" required="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Date Of Birth</label>
              <input class="form-control" type="date" id="account-bd" value="S.Najim_1177" required="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="account-email">E-mail Address</label>
              <input class="form-control" type="email" id="account-email" value="S_najimullahs@posta.mu.edu.tr" disabled="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="account-phone">Phone Number</label>
              <input class="form-control" type="number" id="account-phone" value="+">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-confirm-pass">Confirm Password</label>
              <input class="form-control" type="password" id="account-confirm-pass">
            </div>
          </div>
          <div class="col-12">
            <hr class="mt-2 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <button class="btn btn-style-1 btn-success" type="button" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!"
                data-toast-message="Your profile updated successfuly.">Update Profile</button>
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
              <label for="account-fn">new password</label>
              <input class="form-control" name="newpassword" type="password" >
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="account-fn">Old password</label>
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
