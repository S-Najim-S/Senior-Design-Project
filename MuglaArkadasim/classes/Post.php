<?php

  class Post
  {
      public static function createPost($postBody, $loggedInUserId, $profileUserId)
      {
          // echo $postBody;

          if (strlen($postBody) > 240 || strlen($postBody) < 1) {
              die("incorrect length! from Create Post");
          }

          $topics = self::getTopics($postBody);

          // if the logged in user is the one who is tryin to post then query
          if ($loggedInUserId == $profileUserId) {

            if (count(self::notify($postBody)) != 0) {
                               foreach (self::notify($postBody) as $key => $n) {
                                               $s = $loggedInUserId;
                                               $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                               if ($r != 0) {
                                                       DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                                               }
                                       }
                               }


              DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0,0, \'\', :topics)', array(':postbody'=>$postBody, ':userid'=>$profileUserId, ':topics'=>$topics));
          } else {
              die("Incorrect User!");
          }
      }

      public static function createImgPost($postBody, $loggedInUserId, $profileUserId)
      {
          // echo $postBody;

          if (strlen($postBody) > 240) {
              die("incorrect length! from Create Post");
          }
          $topics = self::getTopics($postBody);

          // if the logged in user is the one who is tryin to post then query
          if ($loggedInUserId == $profileUserId) {
            if (count(self::createNotify($postBody)) != 0) {
                               foreach (self::notify($postBody) as $key => $n) {
                                               $s = $loggedInUserId;
                                               $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                               if ($r != 0) {
                                                       DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                                               }
                                       }
                               }


              DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0,0, \'\', :topics)', array(':postbody'=>$postBody, ':userid'=>$profileUserId, ':topics'=> $topics));
              $postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY ID DESC LIMIT 1;', array(':userid'=>$loggedInUserId))[0]['id'];
              return $postid;
          } else {
              die("Incorrect User!");
          }
      }
      public static function likePost($postId, $likerId)
      {
          if (!DB::query('SELECT user_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId)) && DB::query('SELECT user_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId)) ) {
              DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
              DB::query('DELETE FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
              DB::query('UPDATE posts SET dislikes=dislikes-1 WHERE id=:postid', array(':postid'=>$postId));
              DB::query('INSERT INTO post_likess VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));

          } else if(!DB::query('SELECT user_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId)) && !DB::query('SELECT user_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))){
              DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
              DB::query('INSERT INTO post_likess VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
              self::notify("", $postId);

          }
          else {
            DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
            DB::query('DELETE FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
           }
      }

      public static function dislikePost($postId, $likerId)
      {
          if (!DB::query('SELECT user_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId)) && DB::query('SELECT user_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {
              DB::query('UPDATE posts SET dislikes=dislikes+1 WHERE id=:postid', array(':postid'=>$postId));
              DB::query('DELETE FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
              DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
              DB::query('INSERT INTO post_dislikes VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
          } else if(!DB::query('SELECT user_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId)) && !DB::query('SELECT user_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
            DB::query('INSERT INTO post_dislikes VALUES(\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
          }
          else {
            DB::query('UPDATE posts SET dislikes=dislikes-1 WHERE id=:postid', array(':postid'=>$postId));
            DB::query('DELETE FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
          }
      }

      public static function getTopics($text)
      {
          $text = explode(" ", $text);

          $topics = "";

          foreach ($text as $word) {
              if (substr($word, 0, 1) == "#") {
                  $topics .= substr($word, 1).",";
              }
          }

          return $topics;
      }

      public static function notify($text = "", $postid = 0) {
               $text = explode(" ", $text);
               $notify = array();

               foreach ($text as $word) {
                       if (substr($word, 0, 1) == "@") {
                               $notify[substr($word, 1)] = array("type"=>1, "extra"=>' { "postbody": "'.htmlentities(implode($text, " ")).'" } ');
                       }
               }

               if (count($text) == 1 && $postid != 0) {
                       $temp = DB::query('SELECT posts.user_id AS receiver, post_likess.user_id AS sender FROM posts, post_likess WHERE posts.id = post_likess.post_id AND posts.id=:postid', array(':postid'=>$postid));
                       $r = $temp[0]["receiver"];
                       $s = $temp[0]["sender"];
                       DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>2, ':receiver'=>$r, ':sender'=>$s, ':extra'=>""));
               }

               return $notify;
       }

      public static function link_add($text)
      {
          $text = explode(" ", $text);
          $newstring = "";

          foreach ($text as $word) {
              if (substr($word, 0, 1) == "@") {
                  $newstring .= "<a href='profile.php?username=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
              } elseif (substr($word, 0, 1) == "#") {
                  $newstring .= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
              } else {
                  $newstring .= htmlspecialchars($word)." ";
              }
          }

          return $newstring;
      }

      public static function displayPosts($userid, $username, $loggedInUserId)
      {

      // $dbposts creates an array of posts and then to print each post we loop through it.
          $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
          $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
          // print_r($dbposts);
          $posts = '';

          foreach ($dbposts as $p) {

                  $posts = self::link_add($p['body']);
                  // $postsImage = ."<img style='margin-top:20px' height='100%' width='100%' src='".$p['postimg']."'>"

                  echo '<div class="cardbox shadow-lg bg-white">
                              <div class="cardbox-heading" style="padding-bottom:0px;">'; ?>

                                <?php
                                if ($userid == $loggedInUserId) {
                            echo  '
                                <div class="dropdown float-right">
                                <form class="delete"  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                  <button class="btn btn-flat btn-flat-icon" type="submit" name="deletepost">
                                    <em class="far fa-trash-alt"></em>
                                  </button>
                                 </form>
                                </div>
                                ';
                              }?>
                              <?php
                                echo '<div class="media m-0">
                                  <div class="d-flex mr-3">
                                    <a href=""><img class="img-fluid rounded-circle" src="http://www.themashabrand.com/templates/bootsnipp/post/assets/img/users/4.jpg" alt="User"></a>
                                  </div>
                                  <div class="media-body" style="padding-bottom:0px;">
                                    <h6 class="m-0"><a href="profile.php?username='.$username.'" style="color:#3a9c3a; display:inline"><strong>'.$username.'</strong></a></h6>
                                    <small><span><i class="icon ion-md-time"></i> '.TimeConv::timeSpan($p['posted_at']).'</span></small>
                                    <hr />
                                  </div>
                                </div>
                                <!--/ media -->
                              </div>
                              <!--/ cardbox-heading -->

                              <div class="cardbox-body">
                                <h5 style="margin:10px 15px 8px 35px">'.$posts.'</h5>
                              </div>';?>

                              <?php if ($p['postimg'] !=null) {
                                echo '<div class="cardbox-item">
                                  <img class="img-fluid" src="'.$p['postimg'].'" height="100%" width="100%" alt="Image">
                                </div>
                                <!--/ cardbox-item -->';

                              } ?>

                              <?php
                              echo '<hr style="margin:25px 30px 0px 30px">
                              <div class="cardbox-base" style="text-align:center">
                                <ul>';?> <?php

                                if (!DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId)) && !DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))) {
                                echo '<li>
                                <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                                </li>
                                  <li><a><i class="fa fa-thumbs-up"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>

                                  <li>
                                  <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>
                                  ';
                                } else if(!DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId)) && DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))){
                                  echo '<li>
                                  <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                  </form>
                                  </li>
                                    <li><a><i class="fa fa-thumbs-up" style="color:green"></i></a></li>
                                    <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>


                                    <li>
                                  <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>

                                    ';
                                }

                                else if(DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId)) && !DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))){
                                  echo '<li>
                                  <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                  </form>
                                  </li>
                                    <li><a><i class="fa fa-thumbs-up" style=""></i></a></li>
                                    <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>


                                    <li>
                                  <form class=""  action="profile.php?username='.$username.'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down" style="color:red"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>

                                    ';
                                }
                                ?>

                                <?php
                                echo '  <li><a><i class="fa fa-exclamation-circle"></i></a></li>
                                  <li><a><em class="mr-4">Report</em></a></li>
                                </ul>
                              </div>

                            </div>';

          }
          return $posts;
      }

      public static function displayFollowerPosts($followingposts, $userid)
      {
          foreach ($followingposts as $p) {
              // print_r($p);
                  $posts = self::link_add($p['body']);
                  echo '<div class="cardbox shadow-lg bg-white">
                              <div class="cardbox-heading" style="padding-bottom:0px;">'; ?>

                              <?php
                                echo '<div class="media m-0">
                                  <div class="d-flex mr-3">
                                    <a href=""><img class="img-fluid rounded-circle" src="http://www.themashabrand.com/templates/bootsnipp/post/assets/img/users/4.jpg" alt="User"></a>
                                  </div>
                                  <div class="media-body" style="padding-bottom:0px;">
                                    <h6 class="m-0"><a href="profile.php?username='.$p['username'].'" style="color:#3a9c3a; display:inline"><strong>'.$p['username'].'</strong></a></h6>
                                    <small><span><i class="icon ion-md-time"></i> '.TimeConv::timeSpan($p['posted_at']).'</span></small>
                                    <hr />
                                  </div>
                                </div>
                                <!--/ media -->
                              </div>
                              <!--/ cardbox-heading -->

                              <div class="cardbox-body">
                                <h5 style="margin:10px 15px 8px 35px">'.$posts.'</h5>
                              </div>';?>

                              <?php if ($p['postimg'] !=null) {
                                echo '<div class="cardbox-item">
                                  <img class="img-fluid" src="'.$p['postimg'].'" height="100%" width="100%" alt="Image">
                                </div>
                                <!--/ cardbox-item -->';

                              } ?>

                              <?php
                              echo '<hr style="margin:25px 30px 0px 30px">
                              <div class="cardbox-base" style="text-align:center">
                                <ul>';?> <?php

                                if (!DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid)) && !DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid))) {
                                echo '<li>
                                <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                    <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                </form>
                                </li>
                                  <li><a><i class="fa fa-thumbs-up"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>

                                  <li>
                                  <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>
                                  ';
                                } else if(!DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid)) && DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid))){
                                  echo '<li>
                                  <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                  </form>
                                  </li>
                                    <li><a><i class="fa fa-thumbs-up" style="color:green"></i></a></li>
                                    <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>


                                    <li>
                                  <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>

                                    ';
                                }

                                else if(DB::query('SELECT post_id FROM post_dislikes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid)) && !DB::query('SELECT post_id FROM post_likess WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid))){
                                  echo '<li>
                                  <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="like" value="Like" placeholder="&#6144;"></input>
                                  </form>
                                  </li>
                                    <li><a><i class="fa fa-thumbs-up" style=""></i></a></li>
                                    <li><a><em class="mr-4">'.$p['likes'].'</em></a></li>


                                    <li>
                                  <form class=""  action="profile.php?username='.$p['username'].'&postid='.$p['id'].'" method="post">
                                      <input class="like-button" type="submit" name="dislike" value="Dislike"></input>
                                  </form>
                                  </li>
                                  <li><a><i class="fa fa-thumbs-down" style="color:red"></i></a></li>
                                  <li><a><em class="mr-4">'.$p['dislikes'].'</em></a></li>

                                    ';
                                }

                                ?>

                                <?php
                                echo '  <li><a><i class="fa fa-exclamation-circle"></i></a></li>
                                  <li><a><em class="mr-4">Report</em></a></li>
                                </ul>
                              </div>

                            </div>';

          // return $posts;
      }
    }

      public static function showNavBar($username,$address)
      {
          echo '  <div class="bs-example">
              <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="#" style="margin-right:20%">MuglaArkadasim</a>
                <!-- Navbar brand yo -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTop">
                  <ul class="navbar-nav mr-auto">
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
                  <form class="form-inline ml-auto col-sm-4" style="float:right" form action="index1.php" method="post">
                    <input type="text" class="form-control mr-sm-2" placeholder="Search" name="searchbox">
                    <button type="submit" name="search" class="btn btn-outline-light">Search</button>
                  </form>
                </div>

              </nav>
            </div>';
      }
  }

 ?>
