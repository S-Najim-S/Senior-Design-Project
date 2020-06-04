<?php
  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/Image.php');
  $userid = Login::isLoggedIn();

  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];
    $profileimg = DB::query('SELECT profileimg FROM `users` WHERE username=:username',array(':username'=>$loggedInUserName))[0]['profileimg'];
    $email = DB::query('SELECT email FROM `users` WHERE username=:username',array(':username'=>$loggedInUserName))[0]['email'];
    $usertype = DB::query('SELECT users.`type` FROM users
    WHERE users.id = '.$userid.'')[0]['type'];

  $pdo = new PDO('mysql:127.0.0.1=localhost;dbname=mynetwork;chartset=utf8', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $errors = false;
  $success = false;
  $clubName = '';
  $clubDesc = '';
  $adminId = Login::isLoggedIn();

  if (isset($_POST['createclub'])) {
      $clubName = $_POST['cName'];
      $clubDesc = $_POST['cDescription'];


      // Check if Club exists
      if (!DB::query('SELECT cName FROM clubs WHERE cName=:cName', array(':cName'=>$clubName))) {

          // Insert Fields into the database and the password as hash value
          DB::query('INSERT INTO clubs VALUES(\'\', :cName, :cDescription, \'\',:adminId)', array(':cName'=>$clubName,':cDescription'=>$clubDesc, ':adminId'=>$adminId));
          $clubid = DB::query('SELECT id FROM clubs WHERE adminId=:adminId ORDER BY ID DESC LIMIT 1;', array(':adminId'=>$userid))[0]['id'];
          Image::uploadImage('clubimg', "UPDATE clubs SET clubImage = :clubimg WHERE id=:clubid", array(':clubid'=>$clubid));
          $success = true;
      } else {
          $errors['cName'] = "Club already exists";
      }
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

   <?php Post::showNavBar($loggedInUserName, 'profile.php?username='.$loggedInUserName, $usertype); ?>

   <div class="container mt-5">
     <div class="row">
       <div class="col-sm-4 pb-5">
         <!-- Account Sidebar-->

       </div>
       <!-- Profile Settings-->
       <div class="col-lg-8 pb-5">
         <form class="row" action="createclub.php" method="post" enctype="multipart/form-data">
           <div class="col-md-6">
             <div class="form-group">
               <label for="account-cln">Upload club profile</label>
              <input type="file" class="btn btn-success mt-2 px-3" name="clubimg" id="file" style=""></input>
             </div>
             <div class="form-group">
               <label for="account-cln">Name your club</label>
               <input class="form-control" type="text" name="cName" placeholder="Enter clubs name" required="">
             </div>

             <div class="form-group">
               <label for="account-cln">Description</label>
               <textarea class="form-control" type="text" name="cDescription" placeholder="Enter clubs name" required="" rows="5"></textarea>
               <hr>
             </div>
           </div>
           <div class="col-12">
             <div class="d-flex flex-wrap justify-content-between align-items-center">
               <input value="Create Club" type="submit" class="btn btn-success mt-2 px-3"  name="createclub" style="margin-left: 5px;">

             </div>
           </div>
         </form>
       </div>
     </div>
   </div>
 </body>

 </html>
