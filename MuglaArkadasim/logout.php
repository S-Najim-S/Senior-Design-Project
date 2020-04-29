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
