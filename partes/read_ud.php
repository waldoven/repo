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
	$accion = $_REQUEST['accion'];

	if ( !empty($_GET['codigo'])) {
		$id = $_REQUEST['codigo'];
	}
	 
	if ( false) {
		header("Location: read.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("SET NAMES 'utf8';");
		$sql = "SELECT * FROM partes";
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
	  <?php require( BASE_DIR."/header.php");  ?>
	  <?php require( BASE_DIR."/navigation.php" ); ?>
    <div class="row m-1 no-gutters">
      <h3 class="texto">
      <?php
        if ($accion === "update"){
          echo "Modificar Repuesto";
        }
        if ($accion == "delete"){
          echo "Eliminar Repuesto";
        }
      ?>
      </h3>
    </div>
		<div class="row">
		  <div class="col-lg-9"></div>
			<div class="col-lg-2 mx-5"><input type="text" id="search" placeholder="Buscar..."></div>
		</div>
		<div class="table-responsive overflow_sticky">
			<table id="tabla" class="table table-hover table-sm">
			  <thead class="thead-dark">
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">Marca</th>
				  <th scope="col">Modelo</th>
				  <th scope="col">Tipo</th>
				  <th scope="col">Codigo</th>
				  <th scope="col">Descripcion</th>
				  <th scope="col">Costo</th>
				  <th scope="col">Valor</th>
				  <th scope="col">Accion</th>
				</tr>
			  </thead>
			  <tbody>
	<?php
			$num = 1;
			foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
				echo '<tr>';
				echo '<th>'.$num.'</td>';
				echo '<td>'.$row['marca'].'</td>';
				echo '<td>'.$row['modelo'].'</td>';
				echo '<td>'.$row['tipo_rep'].'</td>';
				echo '<td>'.$row['codigo'].'</td>';
				echo '<td>'.$row['descripcion'].'</td>';
				echo '<td>'.$row['costo'].'</td>';
				echo '<td>'.$row['valor'].'</td>';
				echo '<td><a class="btn btn-sm btn-outline-danger mx-1" href="update.php?codigo='.$row['codigo'].'&marca='.$row['marca'].'&modelo='.$row['modelo'].'"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-sm btn-outline-dark mx-1" href="delete.php?codigo='.$row['codigo'].'&marca='.$row['marca'].'&modelo='.$row['modelo'].'"><i class="fas fa-trash"></i></a></td>';
				echo '</tr>';
				++$num;
			}
	?>
			  </tbody>
			</table>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

  </body>
</html>
