<?php

  class Login{

    public static function isLoggedIn(){

      if (isset($_COOKIE['SNID'])) {

          if (DB::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))) {
              $user_id = DB::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))[0]['user_id'];

              // Check if second cookie is set
              if (isset($_COOKIE['SNID_'])) {

                return $user_id;
              } else {
                $cstrong = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                // Inserts new cookie
                DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$userid));
                DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));

                // Create new SNID cookie to replace the old one
                // Stores user cookie for 7 days and delets it once it expires
                setcookie("SNID", $token, time() + 60 * 60 * 24 * 7,'/' , NULL, NULL, TRUE );
                // After 3 days the cookie expires and forces user to ask for a new cookie
                setcookie("SNID_", '1', time() +60 * 60 * 24 * 3, '/', NULL, NULL, True);
                return $userid;
              }
        }
      }

      return false;
    }

  }


 ?>
