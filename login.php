<?php
  session_start();
  if(isset($_SESSION['loggedIn'])){
      header("Location: backend.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
</head>
<body>
  <div id="login"></div>
  <script src="/dist/login.js" defer></script>
</body>
</html>