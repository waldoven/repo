<?php 
define("APPLICATION_PATH",  dirname(__FILE__));
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
  $userid = $_SESSION["id"];
  $rol = $_SESSION["rol"];
} else {
  header("location: ./user/login.php");
}

?>
<!DOCTYPE html>
  <html lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Waldo Venn" />
    <title>IMPORTADORA REPO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/fonts/all.css">
    <link href="./css/header.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container-fluid">
      <?php require( BASE_DIR."/header.php");  ?>
      <?php require( BASE_DIR."/navigation.php"); ?>
      <footer>
        <hr />
        <div class="container">
          <p id="footer" class="text-right"></p>
        </div>
      </footer>
    </div>

    <script type="text/javascript" src="./js/jquery-3.4.0.min.js"></script>
    <script type="text/javascript" src="./js/inputmask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/main.js"></script>

  </body>
</html>