
<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/TimeConv.php');
  include('./classes/Image.php');

  $userid = Login::isLoggedIn();
  $loggedInUserName = DB::query('SELECT login_tokens.user_id, users.`username` FROM users,login_tokens
    WHERE users.id = login_tokens.user_id')[0]['username'];
    $usertype = DB::query('SELECT users.`type` FROM users
    WHERE users.id = '.$userid.'')[0]['type'];

  if (Login::isLoggedIn()) {
  } else {
    die("Not Logged in");
  }?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MuglaArkadasim</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="assets/css/Highlight-Clean.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
</head>

<body>
  <?php POST::showNavBar($loggedInUserName, 'profile.php?username='.$loggedInUserName, $usertype); ?>
    <div class="container">
        <h1>My Messages</h1></div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <ul class="list-group" id="users">

                    </ul>
                </div>
                <div class="col-md-9" style="position:relative;">
                    <ul class="list-group">
                        <li class="list-group-item" id="m" style="overflow:auto;height:500px;margin-bottom:55px;">
                        </li>
                    </ul>
                    <button class="btn btn-default msg-button-send" style="background-color:black" id="sendmessage"type="button">SEND </button>
                    <div class="message-input-div">
                        <input id="messagecontent" type="text" style="width:100%;height:45px;outline:none;font-size:16px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
    <script type="text/javascript">
    SENDER = 3;
    function getUsername() {
            $.ajax({

                    type: "GET",
                    url: "api/users",
                    processData: false,
                    contentType: "application/json",
                    data: '',
                    success: function(r) {
                            USERNAME = r;
                    }
            })
    }

    $(document).ready(function() {
      $(window).on('hashchange', function() {
                   location.reload()
           })

            $('#sendmessage').click(function(){
              $.ajax({

                        type: "POST",
                        url: "api/message",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "body": "'+ $("#messagecontent").val() +'", "receiver": "'+ SENDER +'" }',
                        success: function(r) {
                                window.location = '';
                        },
                        error: function(r) {

                        }
                        })
            })
            $.ajax({

                    type: "GET",
                    url: "api/musers",
                    processData: false,
                    contentType: "application/json",
                    data: '',
                    success: function(r) {
                            r = JSON.parse(r)
                            for (var i = 0; i < r.length; i++) {
                                    $('#users').append('<li id="user'+i+'" data-id='+r[i].id+' class="list-group-item" style="background-color:#FFF;"><span style="font-size:16px;"><strong>'+r[i].username+'</strong></span></li>')
                                    $('#user'+i).click(function() {
                                            window.location = 'messages.html#' + $(this).attr('data-id')
                                    })
                            }
                    }
            })
            $.ajax({

                    type: "GET",
                    url: "api/messages?sender="+SENDER,
                    processData: false,
                    contentType: "application/json",
                    data: '',
                    success: function(r) {
                            r = JSON.parse(r)
                            $.ajax({

                                    type: "GET",
                                    url: "api/users",
                                    processData: false,
                                    contentType: "application/json",
                                    data: '',
                                    success: function(u) {
                                            USERNAME = u;
                                            for (var i = 0; i < r.length; i++) {
                                                    if (r[i].Sender == USERNAME) {
                                                            $('#m').append('<div class="message-from-me message" style="background-color:#343a40; "><p style="color:#FFF;padding:10px;">'+r[i].body+'</p></div><div class="message-spacer message"><p>'+r[i].body+'</p></div>')
                                                    } else {
                                                            $('#m').append('<div class="message-from-other message"><p>'+r[i].body+'</p></div><div class="message-spacer message"><p>'+r[i].body+'</p></div>')
                                                    }
                                            }
                                    }
                            })
                    },
                    error: function(r) {
                            console.log(r)
                    }
             })
    })
    </script>
</body>

</html>
