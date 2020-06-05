<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/TimeConv.php');
  include('./classes/Image.php');

  if (Login::isLoggedIn()) {
  } else {
    die("Not Logged in");
  }
  $userid = Login::isLoggedIn();

  $usertype = DB::query('SELECT users.`type` FROM users
  WHERE users.id = '.$userid.'')[0]['type'];
  $username = '';
  $isFollowing = false;
  $loggedInUserId = Login::isLoggedIn();
  $postTime = '';
  $now = time();
  $dislik = isset($_POST['dislike']);
  $like = isset($_POST['like']);
  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];

    if (isset($_POST['searchbox'])) {
      Search::searchBox($_POST['searchbox']);
    }


  // Check the link if the username is passed
  if (isset($_GET['username'])) {
      if (DB::query('SELECT adminid FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
          $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
          $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
          $followerid = Login::isLoggedIn();

          if (isset($_POST['deletepost'])) {
            if (DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid))) {
                              DB::query('DELETE FROM post_likess WHERE post_id=:postid', array(':postid'=>$_GET['postid']));
                              DB::query('DELETE FROM posts WHERE id=:postid and user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid));

                      }
          }

          if (isset($_POST['post'])) {

              if($_FILES['postimg']['size'] == 0){
                  Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
          }else {
            $postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid);
            Image::uploadImage('postimg', "UPDATE posts SET postimg = :postimg WHERE id=:postid", array(':postid'=>$postid));
          }

          }


          if (isset($_GET['postid']) && $dislik == 0) {
              Post::likePost($_GET['postid'], $followerid);
          }

          if (isset($_GET['postid']) && isset($_POST['dislike'])) {
              Post::dislikePost($_GET['postid'], $followerid);

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
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css\bootstrap.min.css">
  <link rel="stylesheet" href="css\style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>

<body style="background-color:#e9ebee;">
  <?php POST::showNavBar($loggedInUserName, 'clubs.php?admin='.$username, $usertype); ?>
  <section class="hero">
    <div class="container">
      <div class="row">

    <?php   if ($loggedInUserId == $userid) {
      echo '
      <div class="col-lg-6 offset-lg-3" style="margin-bottom:15px;">
      <form class="" action="profile.php?username='.$username.'" method="post" enctype="multipart/form-data">
        <textarea class="form-control description" name="postbody" placeholder="Wanna Share Something?" rows="4"></textarea>
        <div class="btn-div" style="text-align:right;">
          <input type="file" class="btn btn-success mt-2 px-3" name="postimg" id="file" style=""></input>
          <input type="submit" class="btn btn-success mt-2 px-3" name="post" value="Post"></input>
        </div>
      </form>
      </div>';
    } ?>

    <div class="col-lg-6 offset-lg-3">
        <?php  // $posts = Post::displayPosts($userid, $username, $followerid); ?>

      </div>
      <div class="col-lg-3">

        <!-- Clubs Section -->
        <div class="shadow-lg p-4 mb-2 bg-white author">
          <a href="http://www.themashabrand.com/">Get more from themashabrand.com</a>
          <p>Bootstrap 4.1.0</p>
        </div>

        <!-- Chatrooms Section -->
          <div class="shadow-lg p-4 mb-2 bg-white author">
            <a href="http://www.themashabrand.com/">Get more from themashabrand.com</a>
            <p>Bootstrap 4.1.0</p>
          </div>
        <!--/ col-lg-3 -->
      </div>
      <!--/ col-lg-3 -->



    </div>
    <!--/ row -->
  </div>
  <!--/ container -->
</section>



<script src="jquery.min.js"></script>
<script src="bootstrap.min.js"></script>

<script type="text/javascript">
  $('#selectedFile').change(function() {
    var a = $('#selectedFile').val().toString().split('\\');
    $('#fakeInput').val(a[a.length - 1]);
  });
</script>

<script type="text/javascript">

    $(document).ready(function() {

            $('.sbox').keyup(function() {
                    $('.autocomplete').html("")
                    $.ajax({

                            type: "GET",
                            url: "api/search?query=" + $(this).val(),
                            processData: false,
                            contentType: "application/json",
                            data: '',
                            success: function(r) {
                              // alert(r)
                              r = JSON.parse(r)
                             for (var i = 0; i < r.length; i++) {
                                     console.log(r[i].body)
                                     $('.autocomplete').html(
                                                    $('.autocomplete').html() +
                                                    '<a href="profile.php?username='+r[i].username+'"><li class="list-group-item"><span>'+r[i].body+'</span></li></a>'
                                            )
                                   }
                            },
                            error: function(r){
                              console.log(r);
                            }
                          })
                        })
                      })

                      </script>

</body>

</html>
