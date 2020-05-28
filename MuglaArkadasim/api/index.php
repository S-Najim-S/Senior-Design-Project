<?php
require_once("DB.php");

$db = new DB("localhost", "mynetwork", "root", "");

if ($_SERVER['REQUEST_METHOD'] == "GET") {

        if ($_GET['url'] == "auth") {

        }if ($_GET['url'] == "musers") {

                $token = $_COOKIE['SNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                $users = $db->query("SELECT DISTINCT s.username AS Sender, r.username AS Receiver, s.id AS SenderID, r.id AS ReceiverID FROM messages LEFT JOIN users s ON s.id = messages.sender LEFT JOIN users r ON r.id = messages.receiver WHERE (s.id = :userid OR r.id=:userid)", array(":userid"=>$userid));
                $u = array();
                foreach ($users as $user) {
                        if (!in_array(array('username'=>$user['Receiver'], 'id'=>$user['ReceiverID']), $u)) {
                                array_push($u, array('username'=>$user['Receiver'], 'id'=>$user['ReceiverID']));
                        }
                        if (!in_array(array('username'=>$user['Sender'], 'id'=>$user['SenderID']), $u)) {
                                array_push($u, array('username'=>$user['Sender'], 'id'=>$user['SenderID']));
                        }
                }
                echo json_encode($u);

        }else if ($_GET['url'] == "messages") {
                $sender = $_GET['sender'];
                $token = $_COOKIE['SNID'];
                $receiver = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                $messages = $db->query('SELECT messages.id, messages.body, s.username AS Sender, r.username AS Receiver
                FROM messages
                LEFT JOIN users s ON messages.sender = s.id
                LEFT JOIN users r ON messages.receiver = r.id
                WHERE (r.id=:r AND s.id=:s) OR r.id=:s AND s.id=:r', array(':r'=>$receiver, ':s'=>$sender));

                echo json_encode($messages);

        } else if ($_GET['url'] == "search") {

                $tosearch = explode(" ", $_GET['query']);
                if (count($tosearch) == 1) {
                        $tosearch = str_split($tosearch[0], 2);
                }

                $whereclause = "";
                $paramsarray = array(':body'=>'%'.$_GET['query'].'%');
                for ($i = 0; $i < count($tosearch); $i++) {
                        if ($i % 2) {
                        $whereclause .= " OR body LIKE :p$i ";
                        $paramsarray[":p$i"] = $tosearch[$i];
                        }
                }
                $posts = $db->query('SELECT posts.body, users.username, posts.posted_at FROM posts, users WHERE users.id = posts.user_id AND posts.body LIKE :body '.$whereclause.' LIMIT 10', $paramsarray);
                // echo "<pre>";
                echo json_encode($posts);

        } else if ($_GET['url'] == "users") {

                $token = $_COOKIE['SNID'];
                $user_id = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $username = $db->query('SELECT username FROM users WHERE id=:uid', array(':uid'=>$user_id))[0]['username'];
                echo $username;


        } else if ($_GET['url'] == "posts") {

                $token = $_COOKIE['SNID'];

                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                $followingposts = $db->query('SELECT login_tokens.user_id, posts.id, posts.body,posts.posted_at, posts.likes, posts.dislikes, posts.postimg,posts.user_id, users.`username` FROM users, posts, followers, login_tokens
                  WHERE posts.user_id = followers.user_id
                  AND users.id = posts.user_id
                  AND follower_id = login_tokens.user_id
                  ORDER BY posts.posted_at DESC;');
                $response = "[";
                foreach($followingposts as $post) {

                        $response .= "{";
                                $response .= '"PostId": '.$post['id'].',';
                                $response .= '"PostBody": "'.$post['body'].'",';
                                $response .= '"PostedBy": "'.$post['username'].'",';
                                $response .= '"PostDate": "'.$post['posted_at'].'",';
                                $response .= '"Dislikes": "'.$post['dislikes'].'",';
                                $response .= '"Postimg": "'.$post['postimg'].'",';
                                $response .= '"Likes": '.$post['likes'].'';
                        $response .= "},";


                }
                $response = substr($response, 0, strlen($response)-1);
                $response .= "]";

                http_response_code(200);
                echo $response;

        }

}else if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $token = $_COOKIE['SNID'];

          $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

          $postBody = file_get_contents("php://input");
          $postBody = json_decode($postBody);

          $body = $postBody->body;
          $receiver = $postBody->receiver;

          if (strlen($body) > 100) {
                  echo "{ 'Error': 'Message too long!' }";
          }

          $db->query("INSERT INTO messages VALUES ('', :body, :sender, :receiver, '0')", array(':body'=>$body, ':sender'=>$userid, ':receiver'=>$receiver));

          echo '{ "Success": "Message Sent!" }';

          if ($_GET['url'] == "message") {

          }
} else {
        http_response_code(405);
}
?>
