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
  $nom_clienteInvalid = false;
  $rut_clienteInvalid = false;
  $giro_clienteInvalid = false;
  $dire_clienteInvalid = false;
  $comuna_clienteInvalid = false;
  $ciudad_clienteInvalid = false;
  $correo_clienteInvalid = false;
  $telef_clienteInvalid = false;
  $contac_clienteInvalid = false;
  $descuentoInvalid = false;
  $clienteCreadoMensaje = '';
  $clienteCreado = false;
  $clienteCreadoControl = "0";

  if ( !empty($_POST)) {

    $nom_clienteError = null;
    $rut_clienteError = null;
    $giro_clienteError = null;
    $dire_clienteError = null;
    $comuna_clienteError = null;
    $ciudad_clienteError = null;
    $correo_clienteError = null;
    $telef_clienteError = null;
    $contac_clienteError = null;
    $descuentoError = null;

    $nom_cliente = $_POST['nom_cliente'];
    $rut_cliente = $_POST['rut_cliente'];
    $giro_cliente = $_POST['giro_cliente'];
    $dire_cliente = $_POST['dire_cliente'];
    $comuna_cliente = $_POST['comuna_cliente'];
    $ciudad_cliente = $_POST['ciudad_cliente'];
    $correo_cliente = $_POST['correo_cliente'];
    $telef_cliente = $_POST['telef_cliente'];
    $contac_cliente = $_POST['contac_cliente'];
    $descuento = 0;     //$_POST['descuento'];

    // validar input
    $valid = true;
    if (empty($nom_cliente)) {
      $nom_clienteInvalid = true;
      $nom_clienteError = 'Introduzca el Nombre del cliente';
      $valid = false;
    }
    if (empty($rut_cliente)) {
      $rut_clienteInvalid = true;
      $rut_clienteError = 'Introduzca el RUT del cliente';
      $valid = false;
    }
    if (empty($giro_cliente)) {
      $giro_clienteInvalid = true;
      $giro_clienteError = 'Introduzca el Giro del cliente';
      $valid = false;
    }
    if (empty($dire_cliente)) {
      $dire_clienteInvalid = true;
      $dire_clienteError = 'Introduzca la Direccion del cliente';
      $valid = false;
    }
    if (empty($dire_cliente)) {
      $comuna_clienteInvalid = true;
      $comuna_clienteError = 'Introduzca la Comuna del cliente';
      $valid = false;
    }
    if (empty($ciudad_cliente)) {
      $ciudad_clienteInvalid = true;
      $ciudad_clienteError = 'Introduzca la Ciudad del cliente';
      $valid = false;
    }
    if (empty($correo_cliente)) {
      $correo_clienteInvalid = true;
      $correo_clienteError = 'Introduzca el Correo del cliente';
      $valid = false;
    }
    if (empty($telef_cliente)) {
      $telef_clienteInvalid = true;
      $telef_clienteError = 'Introduzca el Telefono del cliente';
      $valid = false;
    }
    if (empty($contac_cliente)) {
      $contac_clienteInvalid = true;
      $contac_clienteError = 'Introduzca el Contacto del cliente';
      $valid = false;
    }
// search for data
    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT rut_cliente FROM clientes WHERE rut_cliente = '".$rut_cliente."'";
      $stmt = $pdo->query($sql);
      $row = $stmt->fetch(PDO::FETCH_ASSOC); 
      if(is_array($row)) {
        $rut_clienteInvalid = true;
        $rut_clienteError = 'RUT de cliente existe'; 
        $clienteCreadoControl = "2";
        $codigoControl ='RUT de cliente '.$rut_cliente.' existe. Modifique...';
        $valid = false;
      }
    }

    // insert data
    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO clientes (nom_cliente,rut_cliente,giro_cliente,descuento,dire_cliente,comuna_cliente,ciudad_cliente,correo_cliente,telef_cliente,contac_cliente) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($nom_cliente,$rut_cliente,$giro_cliente,$descuento,$dire_cliente,$comuna_cliente,$ciudad_cliente,$correo_cliente,$telef_cliente,$contac_cliente));
      Database::disconnect();
      $clienteCreado = true;
      $clienteCreadoControl = "1";
      $codigoControl = 'Cliente '.$nom_cliente.' creado exitosamente... !!';
//      header("Location: ../clientes/create.php");
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
    <link rel="stylesheet" href="../css/fonts/all.css">
    <link href="../css/header.css" rel="stylesheet" />
  </head>
  <body>

	<div class="container-fluid">
    <?php require  BASE_DIR."/header.php"; ?>
    <?php require  BASE_DIR."/navigation.php"; ?>	 
  	<div class="row m-3">
  		<h3>Crear Cliente</h3>
  	</div>
    <div class="row m-3">
			<form class="form-horizontal needs-validation w-100 " novalidate action="create.php" method="post">
			  <div class="row">        
				  <div class="col-lg-6">
					  <div class="d-flex flex-column col-lg-12 pr-0">
							<label class="control-label">Nombre</label>
							<input id="nom_cliente" class="form-control form-control-sm <?php echo ($nom_clienteInvalid)?'is-invalid':''; ?>" name="nom_cliente" type="text"  placeholder="Nombre Cliente" value="<?php echo !empty($nom_cliente)?$nom_cliente:'';?>" >
							<?php if (!empty($nom_clienteError)): ?>
								<small class="text-danger"><?php echo $nom_clienteError;?></small>
							<?php endif; ?>
					  </div>
          </div>
          <div class="col-lg-1 p-0">
            <div class="d-flex flex-column col-lg-12 p-0 has-error has-feedback <?php echo !empty($emailError)?'error':'';?>">
              <label class="control-label">RUT Cliente</label>
              <input id="rut_cliente" class="form-control form-control-sm <?php echo ($rut_clienteInvalid)?'is-invalid':''; ?>" name="rut_cliente" type="text" placeholder="RUT Cliente" value="<?php echo !empty($rut_cliente)?$rut_cliente:'';?>" >
              <?php if (!empty($rut_clienteError)): ?>
                <small class="text-danger"><?php echo $rut_clienteError;?></small>
              <?php endif;?>
            </div>
          </div>
          <div class="col-lg-3 p-0">
            <div class="d-flex flex-column col-lg-12 <?php echo !empty($giro_clienteError)?'error':'';?>">
              <label class="control-label">Giro</label>
              <input id="giro_cliente" class="form-control form-control-sm <?php echo ($giro_clienteInvalid)?'is-invalid':''; ?>" name="giro_cliente" type="text"  placeholder="Giro Cliente" value="<?php echo !empty($giro_cliente)?$giro_cliente:'';?>" >
              <?php if (!empty($giro_clienteError)): ?>
                <small class="text-danger"><?php echo $giro_clienteError;?></small>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="d-flex flex-column col-lg-12 mt-3 pr-0">
              <label class="control-label">Direccion Cliente</label>
              <input id="dire_cliente" class="form-control form-control-sm <?php echo ($dire_clienteInvalid)?'is-invalid':''; ?>" name="dire_cliente" type="text" placeholder="Direccion Cliente" value="<?php echo !empty($dire_cliente)?$dire_cliente:'';?>" >
              <?php if (!empty($dire_clienteError)): ?>
                <small class="text-danger"><?php echo $dire_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-3 p-0">
            <div class="d-flex flex-column col-lg-12 mt-3 p-0 has-error has-feedback ">
              <label class="control-label">Comuna</label>
              <input class="form-control form-control-sm <?php echo ($comuna_clienteInvalid)?'is-invalid':''; ?>" name="comuna_cliente" type="text" placeholder="Comuna Cliente" value="<?php echo !empty($comuna_cliente)?$comuna_cliente:'';?>">
              <?php if (!empty($comuna_clienteError)): ?>
                <small class="text-danger"><?php echo $comuna_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="d-flex flex-column col-lg-12 mt-3 p-0">
              <label class="control-label">Ciudad</label>
              <input class="form-control form-control-sm <?php echo ($ciudad_clienteInvalid)?'is-invalid':''; ?>" name="ciudad_cliente" type="text" placeholder="Ciudad Cliente" value="<?php echo !empty($ciudad_cliente)?$ciudad_cliente:'';?>">
              <?php if (!empty($ciudad_clienteError)): ?>
                <small class="text-danger"><?php echo $ciudad_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="d-flex flex-column col-lg-12 mt-3 pr-0">
              <label class="control-label">Contacto</label>
              <input class="form-control form-control-sm <?php echo ($contac_clienteInvalid)?'is-invalid':''; ?>" name="contac_cliente" type="text"  placeholder="Contacto Cliente" value="<?php echo !empty($contac_cliente)?$contac_cliente:'';?>">
              <?php if (!empty($contac_clienteError)): ?>
                <small class="text-danger"><?php echo $contac_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-4 p-0">
            <div class="d-flex flex-column col-lg-12 mt-3 p-0">
							<label class="control-label">Correo Electronico</label>
							<input class="form-control form-control-sm <?php echo ($correo_clienteInvalid)?'is-invalid':''; ?>" name="correo_cliente" type="text"  placeholder="Correo Cliente" value="<?php echo !empty($correo_cliente)?$correo_cliente:'';?>">
							<?php if (!empty($correo_clienteError)): ?>
								<small class="text-danger"><?php echo $correo_clienteError;?></small>
							<?php endif; ?>
					  </div>
          </div>
          <div class="col-lg-4 p-0">
					  <div class="d-flex flex-column col-lg-12 mt-3">
							<label class="control-label">Telefono</label>
							<input class="form-control form-control-sm <?php echo ($telef_clienteInvalid)?'is-invalid':''; ?>" name="telef_cliente" type="text"  placeholder="Telefono Cliente" value="<?php echo !empty($telef_cliente)?$telef_cliente:'';?>">
							<?php if (!empty($telef_clienteError)): ?>
								<small class="text-danger"><?php echo $telef_clienteError;?></small>
							<?php endif; ?>
					  </div>
				  </div>
				</div>

        <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $clienteCreadoControl; ?>" data-cliente="<?php echo $nom_cliente; ?>" data-action="clientes/read">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">CREAR CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body"><?php echo  $codigoControl; ?></div>
              <div class="modal-footer <?php echo ($clienteCreado)? 'mostrar':'ocultar'; ?>">
                <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-5"></div>
          <div class="col-lg-3 mt-5 p-0 <?php echo ($clienteCreado)? 'ocultar':'mostrar'; ?>"">
            <button type="submit" class="btn btn-danger px-4 py-1">Crear</button>
            <a class="btn btn-outline-dark px-4 py-1 mx-3" href="./update.php" role="button">Atras</a>
          </div>
        </div>
        
			</form>
		</div>
				 
	</div> <!-- /container -->
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

