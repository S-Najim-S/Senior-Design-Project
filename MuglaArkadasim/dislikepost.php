<?php
include('./classes/Post.php');

if (isset($_GET['postid']) && isset($_POST['dislike'])) {
    Post::dislikePost($_GET['postid'],$followerid);
}

 ?>
