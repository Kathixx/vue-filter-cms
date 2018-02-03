<?php
if (isset($_POST["action"]) && !empty($_POST["action"])) {
  $action = $_POST["action"];
  
  if ($action === 'save') {
    $path = "src/assets/json/data.js";
    print "SAVED!";
    $content = "var data = " . $_POST['data'] . ";";
    file_put_contents($path, $content);
  } else if ($action === 'authenticate') {
    if (isset($_POST['username'], $_POST['password']) && !empty($_POST['password']) && !empty($_POST['username'])) {
      $user     = array(
        "user" => "foo",
        "pass" => "foobar"
      );
      $username = $_POST['username'];
      $pass     = $_POST['password'];
      if($username == $user['user'] && $pass == hash('sha3-512' , $user['pass'])){
        session_start();
        $_SESSION['loggedIn'] = $username;
        print "true";
        exit();
      } else {
        print 'false';
      }
    }
  }
} else {
  header('Location: login.php');
}
?>