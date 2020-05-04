<?php

  class Post {

      public static  function createPost($postBody, $loggedInUserId, $profileUserId){
        // echo $postBody;

        if (strlen($postBody) > 240 || strlen($postBody) < 1) {
            die("incorrect length! from Create Post");
        }

        // if the logged in user is the one who is tryin to post then query
        if ($loggedInUserId == $profileUserId) {
          DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0,0, \'\')', array(':postbody'=>$postBody, ':userid'=>$profileUserId));

        } else {
          die( "Incorrect User!");
        }
    }

    public static  function createImgPost($postBody, $loggedInUserId, $profileUserId){
      // echo $postBody;

      if (strlen($postBody) > 240) {
          die("incorrect length! from Create Post");
      }

      // if the logged in user is the one who is tryin to post then query
      if ($loggedInUserId == $profileUserId) {
        DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0,0, \'\')', array(':postbody'=>$postBody, ':userid'=>$profileUserId));
        $postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY ID DESC LIMIT 1;', array(':userid'=>$loggedInUserId))[0]['id'];
                      return $postid;
      } else {
        die( "Incorrect User!");
      }
  }
    public static function likePost($postId, $likerId){
      if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {

      DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
      DB::query('INSERT INTO post_likes VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
    }else {
      DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
      DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
    }
    }

    public static function dislikePost($postId, $likerId){
      if (!DB::query('SELECT user_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {

      DB::query('UPDATE posts SET dislikes=dislikes+1 WHERE id=:postid', array(':postid'=>$postId));
      DB::query('INSERT INTO post_dislikes VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
    }else {
      DB::query('UPDATE posts SET dislikes=dislikes-1 WHERE id=:postid', array(':postid'=>$postId));
      DB::query('DELETE FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
    }
    }

    public static function displayPosts($userid, $username, $loggedInUserId) {

      // $dbposts creates an array of posts and then to print each post we loop through it.
      $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
      $posts = '';

      foreach ($dbposts as $p) {
                    if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))) {
                      $posts = htmlspecialchars($p['body']);
                      echo '<div class="posting card">
                        <div class="header" style="display:flex;">
                          <div class="header-img" style="display:inline-block">
                            <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
                          </div>
                          <div class="timestamp">
                            <a href="profile.php?username='.$username.'" style="color:#3a9c3a; display:inline"><strong>'.$username.'</strong></a>
                            <span class="timestampContent" id="time">'.TimeConv::timeSpan($p['posted_at']).'</span>
                          </div>
                        </div>
                        <hr>
                        <div class="card-body">
                          <h6>'.$posts.'</h6>
                          <img src="img\image-2.png" alt="" height="100%" width="100%">
                          <hr>
                          <div class="row">

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                    <i class="far fa-heart" style="margin-right:5px; color:#5cb85c;"></i>
                                    <span>'.$p['likes'].'</span>
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes">
                                <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart-broken" style="margin-right:5px; color:red;"></i>
                                    <span>'.$p['dislikes'].'</span>
                                    <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes ">
                                <form class="" action="index.html" method="post">
                                    <i class="fas fa-exclamation-circle " style="margin-right:5px; color:#ec971f;"></i><input class="like-button" type="submit" name="report" value="Report"></input>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>';
                    }else {
                      $posts = htmlspecialchars($p['body']);
                      echo '<div class="posting card">
                        <div class="header" style="display:flex;">
                          <div class="header-img" style="display:inline-block">
                            <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
                          </div>
                          <div class="timestamp">
                            <a href="profile.php?username='.$username.'" style="color:#3a9c3a; display:inline"><strong>'.$username.'</strong></a>
                            <span class="timestampContent" id="time">'.TimeConv::timespan($p['posted_at']).'</span>
                          </div>
                        </div>
                        <hr>
                        <div class="card-body">
                          <h6>'.$posts.'</h6>
                          <img src="img\image-2.png" alt="" height="100%" width="100%">
                          <hr>
                          <div class="row">

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart" style="margin-right:5px; color:#5cb85c;"></i>
                                    <span>'.$p['likes'].'</span>
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes">
                                <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart-broken" style="margin-right:5px; color:red;"></i>
                                    <span>'.$p['dislikes'].'</span>
                                    <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes ">
                                <form class="" action="index.html" method="post">
                                    <i class="fas fa-exclamation-circle " style="margin-right:5px; color:#ec971f;"></i><input class="like-button" type="submit" name="report" value="Report"></input>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>';
                    }
                       }
                       return $posts;
    }

    public static function displayFollowerPosts($followingposts, $userid){
      foreach ($followingposts as $p) {
                    // print_r($p);
                    if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid))) {
                      $posts = htmlspecialchars($p['body']);
                      echo '<div class="posting card">
                        <div class="header" style="display:flex;">
                          <div class="header-img" style="display:inline-block">
                            <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
                          </div>
                          <div class="timestamp">
                            <a href="profile.php?username='.$p['username'].'" style="color:#3a9c3a; display:inline"><strong>'.$p['username'].'</strong></a>
                            <span class="timestampContent" id="time">'.TimeConv::timeSpan($p['posted_at']).'</span>
                          </div>
                        </div>
                        <hr>
                        <div class="card-body">
                          <h6>'.$posts.'</h6>
                          <img src="img\image-2.png" alt="" height="100%" width="100%">
                          <hr>
                          <div class="row">

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class="" action="index1.php?postid='.$p['id'].'" method="post">
                                    <i class="far fa-heart" style="margin-right:5px; color:#5cb85c;"></i>
                                    <span>'.$p['likes'].'</span>
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class="" action="index1.php?postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart-broken" style="margin-right:5px; color:red;"></i>
                                    <span>'.$p['dislikes'].'</span>
                                    <input class="like-button" type="submit" name="dislike" value="Dislike" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes ">
                                <form class="" action="index.html" method="post">
                                    <i class="fas fa-exclamation-circle " style="margin-right:5px; color:#ec971f;"></i><input class="like-button" type="submit" name="report" value="Report"></input>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>';
                    }else {
                      $posts = htmlspecialchars($p['body']);
                      echo '<div class="posting card">
                        <div class="header" style="display:flex;">
                          <div class="header-img" style="display:inline-block">
                            <img src="img/userimage.png" alt="userimg" height="40px" width="40px">
                          </div>
                          <div class="timestamp">
                            <a href="profile.php?username='.$p['username'].'" style="color:#3a9c3a; display:inline"><strong>'.$p['username'].'</strong></a>
                            <span class="timestampContent" id="time">'.TimeConv::timeSpan($p['posted_at']).'</span>
                          </div>
                        </div>
                        <hr>
                        <div class="card-body">
                          <h6>'.$posts.'</h6>
                          <img src="img\image-2.png" alt="" height="100%" width="100%">
                          <hr>
                          <div class="row">

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class=""  action="index1.php?postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart" style="margin-right:5px; color:#5cb85c;"></i>
                                    <span>'.$p['likes'].'</span>
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class=" col-sm-4 my-footer">
                              <div class="likes " style="margin-left:10px;">
                                <form class=""  action="index1.php?postid='.$p['id'].'" method="post">
                                    <i class="fas fa-heart-broken" style="margin-right:5px; color:red;"></i>
                                    <span>'.$p['dislikes'].'</span>
                                    <input class="like-button" type="submit" name="dislike" value="Dislike" placeholder="&#6144;"></input>
                                </form>
                              </div>
                            </div>

                            <div class="col-sm-4 my-footer">
                              <div class="likes ">
                                <form class="" action="index.html" method="post">
                                    <i class="fas fa-exclamation-circle " style="margin-right:5px; color:#ec971f;"></i><input class="like-button" type="submit" name="report" value="Report"></input>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>';
                    }
                       }
                       // return $posts;
    }

    public static function showNavBar($username){
      echo '     <nav class="navbar navbar-expand-md navbar-dark bg-blue">
             <a class="navbar-brand logo" href="#">MuglaArkadasim</a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
               <span class="navbar-toggler-icon"></span>
             </button>

             <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav mx-auto">
                 <li class="nav-item active">
                   <a class="nav-link" href="profile.php?username='.$username.'"><i class=" usercircle far fa-user-circle fa-lg"></i>'.$username.'</a>
                 </li>
                 <li class="nav-item">
                   <a class="nav-link" href="index1.php">Home</a>
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
                     <a href="editProfile.php">Edit profile</a>
                     <div class="dropdown-divider"></div>
                     <a href="createClub.html">Create club</a>
                     <div class="dropdown-divider"></div>
                     <a href="createChatroom.html">Create chatroom</a>
                     <div class="dropdown-divider"></div>
                     <a href="logout.php?pageSet=true">Logout</a>
                   </div>
                 </li>
               </ul>
               <div class="search-btn">
               <form class="form-inline">
                 <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" >
                 <input type="submit" class="btn btn-light my-sm-0" name="post" value="Search"></input>
               </form>
             </div>
             </div>
           </nav>';
    }



  }

 ?>
