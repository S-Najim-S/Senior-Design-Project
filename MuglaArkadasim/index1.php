<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/TimeConv.php');
  include('./classes/Post.php');


  $showTimeline = False;
  $dislik = isset($_POST['dislike']);
  $like = isset($_POST['like']);

  // Using Login:: to refrence the function
  if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    $showTimeline = True;
  } else {
    die("Not Logged in");
  }

  if (isset($_GET['postid']) && $dislik == 0) {
      Post::likePost($_GET['postid'],$userid);
}

if (isset($_GET['postid']) && isset($_POST['dislike'])) {
    Post::dislikePost($_GET['postid'],$userid);
}
  $followingposts = DB::query('SELECT posts.id, posts.body,posts.posted_at, posts.likes, posts.dislikes, users.`username` FROM users, posts, followers
    WHERE posts.user_id = followers.user_id
    AND users.id = posts.user_id
    AND follower_id = '.$userid.'
    ORDER BY posts.posted_at DESC;');

    $username = DB::query('SELECT users.`username` FROM users
    WHERE users.id = '.$userid.'')[0]['username'];
 ?>

 <!DOCTYPE html>
 <html>

 <head>
   <title>MuglaArkadasim</title>
   <link rel="stylesheet" href="css\bootstrap.min.css">
   <link rel="stylesheet" href="css\style.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
   <script src="https://kit.fontawesome.com/a81368914c.js"></script>
 </head>

 <body style="background-color:#e9ebee;" onload="form1.reset();">

   <?php POST::showNavBar($username); ?>
   <div class="container" style="display:block; text-align:center;">

     <div class="row">
       <div class="col-sm ">
         <!-- my right column for now it's empty -->
       </div>
       <div class="col-lg-7 post">

         <?php    Post::displayFollowerPosts($followingposts, $userid) ?>



         <div class="posting card">
           <div class="header" style="display:flex;">
             <div class="header-img" style="display:inline-block">
               <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
             </div>
             <div class="timestamp">
               <a href="#" style="color:#3a9c3a; display:inline"><strong>S.Najim_1177</strong></a>
               <span class="timestampContent" id="time">59 mins</span>
             </div>
           </div>
           <hr>
           <div class="card-body">
             <h6>Lorem Ipsum is simply dummy text of the recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</h6>
             <img src="img/postPhoto.jpg" alt="" height="100%" width="100%">
             <hr>
             <div class="row">
               <div class="col-lg-4 like-sec">
                 <div class="likes" style="margin-left:10px;">
                   <i class="far fa-heart">   0 Likes</i>
                 </div>
               </div>
               <div class="col-lg-4 dislike-sec">
                 <div class="dislike">
                   <i class="far fa-thumbs-down">  0 Dislike</i>
                 </div>
               </div>
               <div class="col-lg-4 report-sec">
                 <div class="report">
                   <i class="fas fa-exclamation-circle"></i>  Report</i>
                 </div>
               </div>
             </div>
           </div>
         </div>

       </div>
       <div class="col-sm-3 right-box">

         <div class="card" >
           <div class="chat-groups">
             <h6 style="margin-top:10px; color:#3a9c3a;">My Chatrooms</h6>
             <hr>
             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\chatroom-photo.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-2.png" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>HelloWorld</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-4.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-5.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\postPhoto.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\userimage.png" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>
           </div>

         </div>

         <div class="card" style="margin-top:10px;" >
           <div class="clubs">
             <h6 style="margin-top:10px; color:#3a9c3a;">My Clubs</h6>
             <hr>
             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\userimage.png" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>Bilgisayar Bilesim</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\postPhoto.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>Economist</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-5.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>Fen ve Edebyat</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\chatroom-photo.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-4.jpg" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>

             <div class="header" style="display:flex;">
               <div class="header-img" style="display:inline-block">
                 <img src="img\image-2.png" alt="userimg" height="40px" width="40px">
               </div>
               <div class="chatroom-name">
                 <a href="#" style="margin-top:15px;"><strong>CENG1920</strong></a>
               </div>
             </div>
           </div>
         </div>

       </div>
     </div>
   </div>
 </body>

 </html>
