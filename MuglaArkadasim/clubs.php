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

  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];
    $usertype = DB::query('SELECT users.`type` FROM users
    WHERE users.id = '.$userid.'')[0]['type'];

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
    <?php POST::showNavBar($loggedInUserName, 'profile.php?username='.$loggedInUserName, $usertype); ?>
    <section class="hero">
      <div class="container">
        <div class="row">


      <div class="col-lg-6 offset-lg-3">

        <?php
         // $dbposts creates an array of posts and then to print each post we loop through it.
            $clubs = DB::query('SELECT * FROM clubs ORDER BY id DESC');
            // print_r($clubs);
            // $dbprofileimg = DB::query('SELECT clubImage FROM `clubs` WHERE cName=:cName')[0]['clubImage'];
            // print_r($dbprofileimg);
            foreach ($clubs as $club) {
              echo '<div class="shadow-lg p-4 mb-2 bg-white author">
                <div class="club-boxes">
                <a href=""><img class="img-fluid rounded-circle" height="50px" width="50px" src='.$club['clubImage'].' alt="User"></a>
                </div>
                <div class="club-boxes txt" style="max-width:99px; margin-right:99px">
                <h5>'.$club['cName'].'</h5>
                </div>
                <div class="txt fl-btn ">
                <form>
                <input type="submit" name="follow" value="            Follow          " class="btn btn-style-1 btn-success">
                </form>
                </div>

              </div>';
            }

            ?>


        </div>




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
