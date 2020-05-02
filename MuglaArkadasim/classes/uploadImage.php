<?php
if (isset($_POST['uploadprofileimg'])) {

  // we make sure imgur take care of file types by typing base64_encodefile_get_contents
   $image = base64_encode(file_get_contents($_FILES['profileimg']['tmp_name']));

   $options = array('http'=>array(
               'method'=>"POST",
               'header'=>"Authorization: Bearer bcac4922443b9a8db42e808494b70fc343553829\n".
               "Content-Type: application/x-www-form-urlencoded",
               'content'=>$image
       ));

  $context = stream_context_create($options);
  $imgurURL = "https://api.imgur.com/3/image";

  $response = file_get_contents($imgurURL, false, $context);

  header('Location:../loginPage.php');
}
 ?>
