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
	date_default_timezone_set('America/Santiago');
	// search for CLIENTES data
	$num_venta = null;
	$num_venta_p = null;
	$modalControl = null;

//	CONSECUTIVO number for ENTERPRISE 
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT num_venta,fecha_venta FROM ventas ORDER BY num_venta DESC LIMIT 1";
//echo $sql;
	$stmt = $pdo->query($sql);
	if ( $stmt->rowCount() > 0 ) {
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($rows as $row){
			$fechaconsec = $row['fecha_venta'];
			$numconsec = $row['num_venta'];
			$consec = str_split($row['num_venta'], 8);
		}
		if ($fechaconsec == date("Y-m-d")) {
			$consecutivo = $consec[1]+1;
		} else {
			$consecutivo = 1;
		}
	} else {
		$consecutivo = 1;
	}
//echo $consecutivo;
//	NUMERO CONSECUTIVO para particulares
	$sql = "SELECT num_venta,fecha_venta FROM ventas_part ORDER BY num_venta DESC LIMIT 1";
//echo $sql;
	$stmt = $pdo->query($sql);
	if ( $stmt->rowCount() > 0 ) {
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($rows as $row){
			$fechaconsec_p = $row['fecha_venta'];
			$numconsec_p = $row['num_venta'];
			$consec_p = str_split($row['num_venta'], 8);
		}
		if ($fechaconsec_p == date("Y-m-d")) {
			$consecutivo_p = $consec_p[1]+1;
		} else {
			$consecutivo_p = 1;
		}
	} else {
		$consecutivo_p = 1;
	}
//echo $consecutivo_p;
	Database::disconnect();
// End of search for CLIENTES data

	// search for CLIENTES data
	$clientes = "";
	$rutclientes = "";
	$num = 1;
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM clientes ORDER BY nom_cliente";
	$stmt = $pdo->query($sql);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row){
		$descuento = $row['descuento'];
		$clientes = $clientes."<tr><td width='30%'>".$row['nom_cliente']."</td><td width='10%'>".$row['rut_cliente']."</td><td width='30%'>".$row['contac_cliente']."</td><td width='10%'>".$row['descuento']."</td></tr>";
	}
	Database::disconnect();
// End of search for CLIENTES data

// search for PARTES data
	$partes = "";
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM partes";
	$stmt = $pdo->query($sql);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$partes = '<table id="listaPartes" class="table table-bordered table-hover table-sm">
							<thead class="thead-light">
								<tr>
									<th scope="col">Marca</th>
									<th scope="col">Modelo</th>
									<th scope="col">Tipo</th>
									<th scope="col">Codigo</th>
									<th scope="col">Descripcion</th>
									<th scope="col">.</th>
									<th scope="col">Valor</th>
									<th scope="col">.</th>
									<th scope="col">.</th>
									<th scope="col">.</th>
									<th scope="col">.</th>
								</tr>
							</thead>
							<tbody>';
	foreach($rows as $row) { 
		$partes = $partes.
							'<tr>'.
								'<td>'.$row['marca'].'</td>'.
								'<td>'.$row['modelo'].'</td>'.
								'<td>'.$row['tipo_rep'].'</td>'.
								'<td>'.$row['codigo'].'</td>'.
								'<td>'.$row['descripcion'].'</td>'.
								'<td> </td>'.
								'<td>'.$row['valor'].'</td>'.
								'<td> </td>'.
								'<td> </td>'.
								'<td> </td>'.
								'<td> </td>'.
							'</tr>';
	}
	$partes = $partes.'</tbody></table>';
	Database::disconnect();
// End of search for PARTES data
	if ( !empty($_POST)) {

		$nom_cliente = $_POST['nom_cliente'];
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM clientes WHERE nom_cliente = '".$nom_cliente."'";
//    echo $sql;
		$stmt = $pdo->query($sql);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($rows as $row) {
			$nom_cliente = $row['nom_cliente'];
			$rut_cliente = $row['rut_cliente'];
			$giro_cliente = $row['giro_cliente'];
			$dire_cliente = $row['dire_cliente'];
			$comuna_cliente = $row['comuna_cliente'];
			$ciudad_cliente = $row['ciudad_cliente'];
			$correo_cliente = $row['correo_cliente'];
			$telef_cliente = $row['telef_cliente'];
			$contac_cliente = $row['contac_cliente'];
			$descuento = 0;				//$row['descuento'];
		}
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
	<link href="../css/fonts/all.css" rel="stylesheet">
	<link href="../css/header.css" rel="stylesheet" />
</head>
<body>
	<div class="container-fluid">
		<?php require  BASE_DIR."/header.php"; ?>
		<?php require  BASE_DIR."/navigation.php"; ?>
		<div class="row mt-3 no-gutters">
			<div class="col-lg-1"></div>
			<div class="col-lg-2"><h3 class="texto">Crear Venta</h3></div>
			<div class="col-lg-5"></div>
			<div id="consec_e" class="d-none"><?php echo date("Ymd").$consecutivo; ?></div>
			<div id="consec_p" class="d-none"><?php echo date("Ymd").$consecutivo_p; ?></div>
			<div class="col-lg-2 text-center"><h3>Venta Nº</h3></div>
			<div id="num_venta" class="col-lg-1"></div>
		</div>

		<div class="row mt-3 m-0 p-0">

			<div class="d-flex flex-column col-lg-2">
			
				<h5 class="texto ml-4 mr-3 pt-2">Tipo de Cliente</h5>
				<div class="btn-group btn-group-toggle col-lg-1 ml-1" data-toggle="buttons">
				  <label for="particular" class="btn btn-outline-dark btn-sm">
				    <input id="particular" type="radio" name="tipo_cliente" autocomplete="off"> Particular
				  </label>
				  <label for="empresa" class="btn btn-outline-dark btn-sm active">
				    <input id="empresa" type="radio" name="tipo_cliente" autocomplete="off"> Empresa
				  </label>
				</div>
			</div>

			<div id="divnombre" class="col-lg-4 d-flex flex-column ml-0 mt-2 pr-2">
				<label for="nomcliente" class="control-label">Razón Social</label>
				<div id="nomcliente" class="form-control form-control-sm"></div>
			</div>
			<div id="divrut" class="col-lg-1 d-flex flex-column px-0 mt-2">
				<label for="rutcliente" class="control-label">RUT</label>
				<div id="rutcliente" class="form-control form-control-sm"></div>
			</div>
			<div id="divdesc" class="col-lg-1 d-flex flex-column px-2 mt-2">
				<label for="descuento" class="control-label">Descuento</label>
				<div class="input-group input-group-sm">
					<input id="descuento" class="form-control form-control-sm text-right w-25" type="text" name="descuent" value="" min="0" max="60" maxlength="2" onkeypress="return isNumeric(event)" />
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div id="fecha" class="col-lg-1 d-flex flex-column px-0 mt-2 mr-0">
				<label for="fechaventa" class="control-label">Fecha</label>
				<div id="fechaventa" class="form-control form-control-sm"><?php echo date("d/m/Y");?></div>
			</div>
			<div id="beforeClient" class="d-flex flex-row col-lg-1 m-0 p-0"></div>
		</div>

		<div class="row mt-3">
			<div class="col-lg-2 d-flex flex-column ml-3">
				<button id="botonClientes" type="submit" class="btn btn-dark btn-sm shadow sm" onclick="openClientes()">Seleccionar Cliente</button>
			</div>
<!--			<div class="d-flex flex-column col-lg-7"></div>			-->
			<div id="divmovil" class="col-lg-3 d-flex flex-row ml-0 pr-2">
				<label for="movil" class="control-label pr-3">Movil</label>
				<input id="movil" class="form-control form-control-sm" type="text" name="movil" value="" onkeyup="this.value = this.value.toUpperCase();" />
			</div>
			<div id="divsolcompra" class="col-lg-4 d-flex flex-row px-0">
				<label for="solcompra" class="col-lg-4 control-label pr-0 mr-2">Solicitud Compra</label>
				<input id="solcompra" class="form-control form-control-sm pl-0 ml-0" type="text" name="solcompra" value="" onkeyup="this.value = this.value.toUpperCase();" />
			</div>
			<div id="divselitems" class="col-lg-2 d-flex flex-column ml-4">
				<button id="botonPartes" type="submit" class="btn btn-dark btn-sm shadow sm" onclick="openPartes()">Seleccionar Items</button>
			</div>
		</div>

		<div class="row mt-3 table-responsive-sm">
			<div class="col-lg-12 ">
				<table id="ventaTabla" class="table table-hover table-sm">
					<thead>
						<tr class="table-secondary">
							<th scope="col" class="align-middle">Marca</th>
							<th scope="col" class="align-middle">Modelo</th>
							<th scope="col" class="align-middle">Tipo</th>
							<th scope="col" class="align-middle">Codigo</th>
							<th scope="col" class="align-middle">Descripcion</th>
							<th scope="col" class="align-middle">Cantidad</th>
							<th scope="col" class="align-middle text-center">Valor</th>
							<th scope="col" class="align-middle text-center">Desc.%</th>
							<th scope="col" class="align-middle text-right">Valor <br>c/desc</th>
							<th scope="col" class="align-middle text-center">Total</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot></tfoot>
				</table>
			</div>
		</div>

		<div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $modalControl; ?>" data-action="">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">CREAR VENTA</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div id="MyModalText" class="modal-body"></div>
					<div id="MyModalFooter" class="modal-footer">
						<input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
					</div>
				</div>
			</div>
		</div>

		<div id="ventaModal" class="modal fade" role="dialog" data-control="<?php echo $parteModificadoControl; ?>">
			<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">VENTA</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div id="ventaModalText" class="modal-body"></div>
					<div class="modal-footer <?php echo ($cotCreateControl)? 'mostrar':'ocultar'; ?>">
						<input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-3 no-gutters">
			<div class="col-lg-1"></div>
			<div class="col-lg-1"><input id="calcular" type="button" class="btn btn-dark create" value="Calcular"></div>
			<div class="col-lg-1"><input id="finalizar" type="button" class="btn btn-danger create" value="Finalizar"></div>
		</div>

	</div> <!-- /container -->

<!-- /PopUP clientes -->
	<div id="FormClientes" class="clientes-popup">
		<form action="#" class="clientes-container">
			<div id="clientes" class="right bg-light">
				<input id="clientesSearch" type='text' placeholder='Buscar clientes...''>
				<table id="listaClientes" class="table table-bordered table-hover table-sm">
					<tr class="table-secondary">
						<th scope="col">Cliente</th>
						<th scope="col">RUT</th>
						<th scope="col">Contacto</th>
					</tr>
					<?php echo $clientes; ?>
				</table>
			</div>
			<button type="button" class="btn cancel" onclick="closeClientes()">Cerrar</button>
		</form>
	</div>
<!-- /PopUP clientes -->

<!-- /PopUP partes -->
	<div id="FormPartes" class="partes-popup" >
		<form action="#" class="partes-container">
			<div id="partes" class="right bg-light">
				<input id="partesSearch" type='text' placeholder='Buscar repuestos...''>
				<?php echo $partes; ?>
			</div>
			<button type="button" class="btn cancel" onclick="closePartes()">Cerrar</button>
		</form>
	</div>
<!-- /PopUP partes -->

<!-- footer -->
	<footer>
		<div class="piepagina">
			<p class="text-right"></p>
		</div>
	</footer>
<!-- /footer --> 

	<script src="../js/jquery-3.4.0.min.js"></script>
  	<script src="../js/inputmask/jquery.inputmask.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/vencreate.js"></script>
</body>
</html>

