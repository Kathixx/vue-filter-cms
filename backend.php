<?php
  session_start();
  if(!isset($_SESSION['loggedIn'])){
      header("Location: login.php");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Backend</title>
  </head>
<body>
  <div id="filterEntries"></div>
  <script src="/src/assets/json/data.js?random=<?php echo uniqid(); ?>"></script>
  <script src="/dist/backend.js"></script>
</body>
</html>