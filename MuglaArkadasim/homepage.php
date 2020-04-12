<?php
  include('./classes/DB.php');
  include('./classes/Login.php');
  if (!Login::isLoggedIn()) {
      die("You are not logged in, for access please login!");
  }
        if ( isset($_GET['pageSet'])) {
            if (isset($_COOKIE['SNID'])) {
                DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
            }
            // expires the cookies and the user gets logged out
            setcookie('SNID', '1', time()-3600);
            setcookie('SNID_', '1', time()-3600);
            header('Location:loginPage.php');

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
            <a class="nav-link" href="userTimeline.html"><i class=" usercircle far fa-user-circle fa-lg"></i> User_name</a>
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

  <div class="container" style="display:block; text-align:center;">
    <div class="col-md-7" style="display:inline-block; margin-top:50px; margin-right:9%;">
      <textarea class="form-control description" name="description" id="description" placeholder="Wanna Share Something?" rows="3" autocomplete="off" required=""></textarea>
      <div class="btn-div" style="text-align:right;">
        <button type="submit" class="btn btn-success mt-2 px-3" id="withdraw"><i class="fas fa-image" id="wloader" aria-hidden="true"></i> Upload photo </button>
        <button type="submit" class="btn btn-success mt-2 px-4" id="deposit" style="margin-left: 6px;"><i class="fa fa-plus-circle" id="dloader" aria-hidden="true"></i> Post </button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm ">
        <!-- my right column for now it's empty -->
      </div>
      <div class="col-lg-7 post">

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
        </div>

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
            <img src="img\image-4.jpg" alt="" height="100%" width="100%">
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
            <img src="img/image-5.jpg" alt="" height="100%" width="100%">
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
