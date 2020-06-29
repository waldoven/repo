<?php
  define('BASE_DIR', '..');
  define("APPLICATION_PATH",  dirname(__FILE__));

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
// Load library DOMPDF for print PDF
	require '../vendor/autoload.php';
	use Dompdf\Dompdf;
	use Dompdf\Options;
  require 'database.php';
	$accion = null;
  $fecha_now = new DateTime();
  $fecha = $fecha_now->format("d / m / Y");
	if ( !empty($_GET['accion'])) {
		$accion = $_REQUEST['accion'];
	}
	 
	if ( false) {
		header("Location: read.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("SET NAMES 'utf8';");
		$sql = "SELECT * FROM partes";
  }

  $partesTable = '<!DOCTYPE html>
  								<head>
	  								<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
										<title>IMPORTADORA REPO</title>
										<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
										<link rel="stylesheet" href="../css/bootstrap.min.css">
										<link rel="stylesheet" href="../css/font/all.css">
										<link href="header.css" rel="stylesheet" />
  								</head>
                  <body>
                    <div class="row mt-3">
                      <table width="100%">
                        <tr>
                          <td width="3%"> </td>
                          <td width="40%"><strong>IMPORTADORA REPO SPA</strong></td>
                          <td rowspan="5" width="40%"><img src="'.BASE_DIR.'/img/repo20.png"></td>
                          <td width="27%"><strong>LISTADO DE REPUESTOS</strong></td>
                        </tr>
                        <tr>
                          <td> </td>
                          <td><strong>RUT : 76.451.381-9</strong></td>
                          <td><strong>Fecha : '.$fecha.' </strong></td>
                        </tr>
                        <tr>
                          <td> </td>
                          <td><strong>repuestos@repo.cl</strong></td>
                          <td><strong></strong></td>
                        </tr>
                        <tr>
                          <td> </td>
                          <td><strong>232667848 - 232667847</strong></td>
                          <td><strong><input type="text" id="search" placeholder="Buscar..."></strong></td>
                        </tr>
                        <tr>
                          <td> </td>
                          <td> </td>
                        </tr>
                        <tr>
                          <td> </td>
                          <td> </td>
                        </tr>
                      </table>
                    </div>
  									<table id="tabla">
	                    <tr>
	                      <th scope="col">#</th>
	                      <th scope="col">Marca</th>
	                      <th scope="col">Modelo</th>
	                      <th scope="col">Tipo</th>
	                      <th scope="col">Codigo</th>
	                      <th scope="col">Descripcion</th>
	                      <th class="text-center">Costo</th>
	                      <th class="text-center">Valor</th>
	                    </tr>
                    <tbody>';
	$num = 1;
	foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
		$partesTable .= '<tr>
                      <th style="vertical-align:middle;">'.$num.'</td>
                      <td>'.$row['marca'].'</td>
                      <td>'.$row['modelo'].'</td>
                      <td>'.$row['tipo_rep'].'</td>
                      <td>'.$row['codigo'].'</td>
                      <td>'.$row['descripcion'].'</td>
                      <td class="text-right">'.$row['costo'].'</td>
                      <td class="text-right">'.$row['valor'].'</td>
              			</tr>';
		++$num;
	}
	$partesTable .= '</tbody></table></body></html>';

	if ($accion == 'pdf') {

		$pdf = new DOMPDF();
		$pdf->set_base_path(APPLICATION_PATH);
	//	$pdf->set_paper("A4", "portrait");
		$pdf->setPaper('A4', 'Landscape');
		$pdf->load_html(utf8_decode($partesTable));
		$pdf->render();
		$filename = "newpdffile";
//		$output = $dompdf->output();
//		file_put_contents("file.pdf", $output);
//		$pdf->stream($filename+'.pdf');
		$pdf->stream($partesTable,array("Attachment"=>0));

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
  		<div class="row ml-3 mt-3">
  		  <div class="col-lg-2"><h3>Ver Repuestos</h3></div>
  		  <div class="col-lg-8"></div>
  			<div class="col-lg-2">
  				<a class="btn btn-outline-dark" href="./read.php?accion=pdf" role="button">Generar PDF</a>
  			</div>
  		</div>
  		<?php echo $partesTable; ?>
  	</div>
    <footer>
      <hr />
      <div class="container">
        <p class="text-right"></p>
      </div>
    </footer>

    <script src="../js/jquery-3.4.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    
  </body>
</html>
