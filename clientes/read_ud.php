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
	 
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec("SET NAMES 'utf8';");
	$sql = "SELECT * FROM clientes";

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
		  <div class="col-lg-11"><h3 class="texto">Modificar / Eliminar</h3></div>
		</div>
		<div class="table-responsive overflow_sticky">
			<table class="table table-sm w-100">
			  <thead class="thead-dark">
					<tr>
					  <th>#</th>
					  <th>Nombre Cliente</th>
					  <th>RUT Cliente</th>
					  <th>Giro Cliente</th>
					  <th>Comuna</th>
					  <th>Telefono</th>
					  <th>Correo</th>
					  <th>Accion</th>
					</tr>
				</thead>
			  <tbody>
			<?php
				$num = 1;
				foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
					$class = ($num&1) ? 'class="table-light"' : 'class="table-secondary"';
					echo '<tr '.$class.'>';
					echo '<th scope="row" rowspan="2" class="align-middle">'.$num.'</th>';
					echo '<td>'.$row['nom_cliente'].'</td>';
					echo '<td>'.$row['rut_cliente'].'</td>';
					echo '<td>'.$row['giro_cliente'].'</td>';
					echo '<td>'.$row['comuna_cliente'].'</td>';
					echo '<td>'.$row['telef_cliente'].'</td>';
					echo '<td>'.$row['correo_cliente'].'</td>';
					echo '<td class="align-middle" rowspan="2"><a class="btn btn-sm btn-outline-danger mx-1" href="update.php?rut_cliente='.$row['rut_cliente'].'&nom_cliente='.$row['nom_cliente'].'&giro_cliente='.$row['giro_cliente'].'"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-sm btn-outline-dark mx-1" href="delete.php?rut_cliente='.$row['rut_cliente'].'&nom_cliente='.$row['nom_cliente'].'&giro_cliente='.$row['giro_cliente'].'"><i class="fas fa-trash"></i></a></td>';
					echo '</tr>';
					echo '<tr '.$class.'>';
					echo '<td colspan="1"><b>Contacto : </b>'.$row['contac_cliente'].'</td>';
					echo '<td colspan="2"><b>Ciudad : </b>'.$row['ciudad_cliente'].'</td>';
					echo '<td colspan="3"><b>Direccion : </b>'.$row['dire_cliente'].'</td>';
					echo '</tr>';
					++$num;
				}
			?>
			  </tbody>
			</table>
		</div>
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
