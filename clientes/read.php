<?php
  define('BASE_DIR', '..');
  // Initialize the session
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $username = $_SESSION["username"];
    $userid = $_SESSION["id"];
    $rol = $_SESSION["rol"];
  } else {
    header("location: ../user/login.php");
  }
  
	require 'database.php';
	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	 
	if ( false) {
		header("Location: read.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("SET NAMES 'utf8';");
		$sql = "SELECT * FROM clientes";
  	}
?>
 
<!DOCTYPE html>
  <html lang="en" >
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="author" content="Waldo Venn" />
	<title>IMPORTADORA REPO</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/fonts/all.css">
	<link href="../css/header.css" rel="stylesheet" />
  </head>
  <body>
	<div class="container-fluid">
    <?php require  BASE_DIR."/header.php"; ?>
    <?php require  BASE_DIR."/navigation.php"; ?>
    <div class="row mt-3 no-gutters">
      <div class="col-lg-1"></div>
      <div class="col-lg-11"><h3 class="texto">Ver Clientes</h3></div>
    </div>

    <div class="row mt-3 no-gutters">
      <div class="col-lg-11 mx-3">
    		<ul>
    	<?php
    		$num = 1;
    		foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
				  echo '<li class="list-unstyled">';
					echo '<div class="card border-dark mw-100 mb-2 shadow">';
					echo 	'<div class="card-header bg-light p-1">'.
							    '<div class="row no-gutters">'.
							      '<div class="col-lg-6 rounded border border bg-white p-1 mx-3 shadw"><b> Cliente : </b>'.$row['nom_cliente'].'</div>'.
							      '<div class="col-lg-2 rounded border border bg-white bg-white p-1 mx-3 shadw"><b> RUT : </b>'.$row['rut_cliente'].'</div>'.
							    '</div>'.
						    '</div>';
					echo 	'<div class="card-body py-0">';
					echo 		'<div class="row no-gutters">';
					echo 			'<div class="col-lg-6 p-1"><b> Dirección : </b>'.$row['dire_cliente'].'</div>';
					echo 			'<div class="col-lg-3 p-1"><b> Comuna : </b>'.$row['comuna_cliente'].'</div>';
					echo 			'<div class="col-lg-2 p-1"><b> Ciudad : </b>'.$row['ciudad_cliente'].'</div>';
					echo 		'</div>';
					echo 		'<div class="row no-gutters">';
					echo 			'<div class="col-lg-5 p-1"><b>Contacto : </b>'.$row['contac_cliente'].'</div>';
					echo 			'<div class="col-lg-3 p-1"><b>Teléfono : </b>'.$row['telef_cliente'].'</div>';
					echo 			'<div class="col-lg-4 p-1"><b>Correo : </b>'.$row['correo_cliente'].'</div>';
					echo 		'</div>';
					echo 	'</div>';
					echo '</div>';

					echo '</li>';
    			++$num;
    		}
    	?>
    		</ul>
      </div>
      <div class="col-lg-1"></div>
    </div>
<!-- footer -->
    <footer>
      <hr />
      <div class="container">
        <p class="text-right"></p>
      </div>
    </footer>
<!-- /footer --> 

  <script src="../js/jquery-3.4.0.min.js"></script>
  <script src="../js/inputmask/jquery.inputmask.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

  </body>
</html>
