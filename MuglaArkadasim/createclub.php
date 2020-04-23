<?php
  include('classes/DB.php');

  $pdo = new PDO('mysql:127.0.0.1=localhost;dbname=mynetwork;chartset=utf8', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $errors = false;
  $success = false;
  $clubName = '';
  $clubDesc = '';

  if (isset($_POST['createclub'])) {
      $clubName = $_POST['cName'];
      $clubDesc = $_POST['cDescription'];

      // Check if user exists
      if (!DB::query('SELECT cName FROM clubs WHERE cName=:cName', array(':cName'=>$clubName))) {

          // Insert Fields into the database and the password as hash value
          DB::query('INSERT INTO clubs VALUES(\'\', :cName, :cDescription)', array(':cName'=>$clubName,':cDescription'=>$clubDesc));
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
           <li class="nav-item active">
             <a class="nav-link" href="homepage.html">Home</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" href="joinClub.html">Clubs</a>
           </li>
           <li class="nav-item">
             <a class="nav-link" href="joinChatrooms.html">Chatrooms</a>
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
       <div class="col-sm-4 pb-5">
         <!-- Account Sidebar-->

       </div>
       <!-- Profile Settings-->
       <div class="col-lg-8 pb-5">
         <form class="row" action="createclub.php" method="post">
           <div class="col-md-6">
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
