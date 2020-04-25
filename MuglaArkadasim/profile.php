<?php

  include('./classes/DB.php');
  include('./classes/Login.php');

  $username = '';
  $isFollowing = false;
  $loggedInUserId = Login::isLoggedIn();


  // Check the link if the username is passed
  if (isset($_GET['username'])) {
      if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
          $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
          $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
          $followerid = Login::isLoggedIn();

          if (isset($_POST['follow'])) {
              if ($userid != $followerid) {
                  if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                      DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                  } else {
                      echo 'Already following!';
                  }
                  $isFollowing = true;
              }
          }
          if (isset($_POST['unfollow'])) {
              if ($userid != $followerid) {
                  if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                      DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                  }
                  $isFollowing = false;
              }
          }
          if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
              //echo 'Already following!';
              $isFollowing = true;
          }

          if (isset($_POST['post'])) {
              $postBody = $_POST['postbody'];

              if (strlen($postBody) > 240 || strlen($postBody) < 1) {
                  die("incorrect length!");
              }

              // if the logged in user is the one who is tryin to post then query

                DB::query('INSERT INTO posts VALUES(\'\', :postbody, NOW(), :userid, 0)', array(':postbody'=>$postBody, ':userid'=>$userid));

          }

          // $dbposts creates an array of posts and then to print each post we loop through it.
          $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
          $posts = '';
      } else {
          die('User not found!');
      }
  }

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

<body style="background-color:#e9ebee;">

  <div class="navbar-div">
    <nav class="navbar navbar-expand-md navbar-dark bg-blue">
      <a class="navbar-brand logo" href="#">MuglaArkadasim</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item active">
            <a class="nav-link" href="userTimeline.html"><i class=" usercircle far fa-user-circle fa-lg"></i> <?php echo $username ?></a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="joinClub.html">Clubs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="joinChatrooms.html">Chatrooms</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="homepage.php?pageSet=true">Logout</a>
          </li>

          <li class="nav-item">
            <a class="nav-link fas fa-bell fa-sx" style="
    margin-top:4px;" href="#"></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link fas fa-caret-down fa-lg" id="navbarDropdown" role="button" data-toggle="dropdown" style="margin-top:4px;" href="#"></a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a href="editProfile.php">Edit profile</a>
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
  <div class="container" style="display:block; text-align:center;">
    <?php   if ($loggedInUserId == $userid) {
      echo '<form class="col-md-7" style="display:inline-block; margin-top:50px; margin-right:9%;" action="profile.php?username='.$username.'" method="post">
        <textarea class="form-control description" name="postbody" placeholder="Wanna Share Something?" rows="4"></textarea>
        <div class="btn-div" style="text-align:right;">
          <input type="submit" class="btn btn-success mt-2 px-3" name="post" value="Post"></input>
          <!-- <button type="submit" class="btn btn-success mt-2 px-4" id="deposit" style="margin-left: 6px;"><i class="fa fa-plus-circle" id="dloader" aria-hidden="true"></i> Post </button> -->
        </div>
      </form>';
    } ?>



    <div class="row">
      <div class="col-sm ">
        <!-- my right column for now it's empty -->
      </div>
      <div class="col-lg-7 post">

        <?php    foreach ($dbposts as $p) {
                  $posts = htmlspecialchars($p['body']);
          echo '<div class="posting card">
            <div class="header" style="display:flex;">
              <div class="header-img" style="display:inline-block">
                <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
              </div>
              <div class="timestamp">
                <a href="#" style="color:#3a9c3a; display:inline"><strong>'.$username.'</strong></a>
                <span class="timestampContent" id="time">59 mins</span>
              </div>
            </div>
            <hr>
            <div class="card-body">
              <h6>'.$posts.'</h6>
              <img src="img\image-2.png" alt="" height="100%" width="100%">
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
          </div>';
           } ?>


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

        <form class="" action="profile.php?username=<?php echo $username;?>" method="post">
          <?php
          if($userid != $followerid){
            if ($isFollowing) {
                echo '<div class="d-flex flex-wrap justify-content-between align-items-center col-sm" style="margin-bottom:10px; margin-left:15px;">';
                echo '<input type="submit" name="unfollow" value="          Unfollow          " class="btn btn-style-1 btn-success">';
                echo '</div>';
            } else {
              echo '<div class="d-flex flex-wrap justify-content-between align-items-center col-sm" style="margin-bottom:10px; margin-left:15px;">';
              echo '<input type="submit" name="follow" value="            Follow          " class="btn btn-style-1 btn-success">';
              echo '</div>';
            }
            }
           ?>
        </form>

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
