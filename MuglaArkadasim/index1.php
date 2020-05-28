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

if (isset($_POST['searchbox'])) {
      $tosearch = explode(" ", $_POST['searchbox']);
      if (count($tosearch) == 1) {
              $tosearch = str_split($tosearch[0], 2);
      }
      $whereclause = "";
      $paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');
      for ($i = 0; $i < count($tosearch); $i++) {
              $whereclause .= " OR username LIKE :u$i ";
              $paramsarray[":u$i"] = $tosearch[$i];
      }
      $users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray);
      print_r($users);

      $whereclause = "";
      $paramsarray = array(':body'=>'%'.$_POST['searchbox'].'%');
      for ($i = 0; $i < count($tosearch); $i++) {
              if ($i % 2) {
              $whereclause .= " OR body LIKE :p$i ";
              $paramsarray[":p$i"] = $tosearch[$i];
              }
      }
      $posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereclause.'', $paramsarray);
      // echo '<pre>';
      // print_r($posts);
      // echo '</pre>';
}

  $followingposts = DB::query('SELECT posts.id, posts.body,posts.posted_at, posts.likes, posts.dislikes, posts.postimg,posts.user_id, users.`username` FROM users, posts, followers
    WHERE posts.user_id = followers.user_id
    AND users.id = posts.user_id
    AND follower_id = '.$userid.'
    ORDER BY posts.posted_at DESC;');

    $username = DB::query('SELECT users.`username` FROM users
    WHERE users.id = '.$userid.'')[0]['username'];

    $usertype = DB::query('SELECT users.`type` FROM users
    WHERE users.id = '.$userid.'')[0]['type'];


 ?>

 <!DOCTYPE html>
 <html>

 <head>
   <title>MuglaArkadasim</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="css\style.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
   <script src="https://kit.fontawesome.com/a81368914c.js"></script>
 </head>

 <body style="background-color:#e9ebee;">

   <?php POST::showNavBar($username,'index1.php',$usertype); ?>
   <section class="hero">
     <div class="container">
       <div class="row">
         <div class="col-lg-6 offset-lg-3">
         <?php    Post::displayFollowerPosts($followingposts, $userid) ?>

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
                                                        '<li class="list-group-item"><span>'+r[i].body+'</span></li>'
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
