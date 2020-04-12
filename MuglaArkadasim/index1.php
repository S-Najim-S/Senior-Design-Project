<?php

  include('./classes/DB.php');
  include('./classes/Login.php');

  Using Login:: to refrence the function
  if (Login::isLoggedIn()) {
    echo "Logged in ";
    echo Login::isLoggedIn();
  } else {
    echo "Not Logged in ";
  }


 ?>
