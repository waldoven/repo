<?php
  define('BASE_DIR', '.');
// Initialize the session
  session_start();

  require 'database.php';
  date_default_timezone_set('America/Santiago');
// Calculate number of CONSECUTIVO
  $fecha_now = new DateTime();
  $fecha_5atras = $fecha_now->modify("-5 day");
  $x = $fecha_5atras->format("Ymd");
  $x = $x + "0";
  $fecha_5 = (int) $x;
//  echo $fecha_5;


$to = "aldoven@gmail.com";
$subject = "Nonsensical Latin";

// compose headers
$headers = "From: webmaster@example.com\r\n";
$headers .= "Reply-To: webmaster@example.com\r\n";
$headers .= "X-Mailer: PHP/".phpversion();

// compose message
$message = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit.";
$message .= " Nam iaculis pede ac quam. Etiam placerat suscipit nulla.";
$message .= " Maecenas id mauris eget tortor facilisis egestas.";
$message .= " Praesent ac augue sed enim aliquam auctor. Ut dignissim ultricies est.";
$message .= " Pellentesque convallis tempor tortor. Nullam nec purus.";
$message = wordwrap($message, 70);

// send email
mail($to, $subject, $message, $headers);

Database::disconnect();

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
<!-- box1 -->
    <div id="box1" class="home">
      <?php require( BASE_DIR."/header.php");  ?>
    </div>
<!-- /box1 --> 
<!-- navbar --> 
    <div id="navigation">
      <?php require( BASE_DIR."/navigation.php" ); ?>
    </div>
<!-- /navbar --> 
<div class="container-fluid">
  <h3>Toast Example</h3>

  <div class="toast" data-autohide="false">
    <div class="toast-header">
      <strong class="mr-auto text-primary">Toast Header</strong>
      <small class="text-muted">5 mins ago</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body">
      Some text inside the toast body
    </div>
  </div>

</div>

<!-- footer -->
    <footer>
      <hr />
      <div class="container">
        <p id="footer" class="text-right"></p>
      </div>
    </footer>
<!-- /footer --> 

    <script src="./js/jquery-3.4.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/main.js"></script>
<script>

</script>
  </body>
</html>