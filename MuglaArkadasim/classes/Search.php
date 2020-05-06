<?php

  class Search{


    public static function searchBox($post){
      $tosearch = explode(" ", $post);
          if (count($tosearch) == 1) {
                  $tosearch = str_split($tosearch[0], 2);
          }
          $whereclause = "";
          $paramsarray = array(':username'=>'%'.$post.'%');
          for ($i = 0; $i < count($tosearch); $i++) {
                  $whereclause .= " OR username LIKE :u$i ";
                  $paramsarray[":u$i"] = $tosearch[$i];
          }
          $users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray);
          print_r($users);

          $whereclause = "";
          $paramsarray = array(':body'=>'%'.$post.'%');
          for ($i = 0; $i < count($tosearch); $i++) {
                  if ($i % 2) {
                  $whereclause .= " OR body LIKE :p$i ";
                  $paramsarray[":p$i"] = $tosearch[$i];
                  }
          }
          $posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereclause.'', $paramsarray);
          echo '<pre>';
          print_r($posts);
          echo '</pre>';
    }
  }
 ?>
