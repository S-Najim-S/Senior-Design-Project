<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/TimeConv.php');


  $username = '';
  $isFollowing = false;
  $loggedInUserId = Login::isLoggedIn();
  $postTime = '';
  $now = time();
  $dislik = isset($_POST['dislike']);
  $like = isset($_POST['like']);
  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];




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
                    // echo "Already FOllowing!";
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
              Post::createPost( $_POST['postbody'], Login::isLoggedIn(), $userid);
              header("Location: profile.php?username=$username");
          }

          if (isset($_GET['postid']) && $dislik == 0) {
              Post::likePost($_GET['postid'],$followerid);
        }

        if (isset($_GET['postid']) && isset($_POST['dislike'])) {
            Post::dislikePost($_GET['postid'],$followerid);
      }

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

<body style="background-color:#e9ebee;" onload="form1.reset();">
  <?php POST::showNavBar($loggedInUserName); ?>
  <div class="container" style="display:block; text-align:center;">
    <?php   if ($loggedInUserId == $userid) {
      echo '<form class="col-md-7" style="display:inline-block; margin-top:50px; margin-right:9%;" action="profile.php?username='.$username.'" method="post">
        <textarea class="form-control description" name="postbody" placeholder="Wanna Share Something?" rows="4" required></textarea>
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

        <?php $posts = Post::displayPosts($userid, $username, $followerid); ?>

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
