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
  
  if ( !empty($_GET['rut_cliente'])) {
    $rut_cliente = $_REQUEST['rut_cliente'];
  }
  if ( null==$rut_cliente  ) {
      header("Location: ../index.php");
  }

  if ( !empty($_POST)) {
    $valid = true;

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
    $clienteModificado = false;
    $clienteModificadoControl = "0";

    $nom_cliente = $_POST['nom_cliente'];
    $rut_cliente = $_POST['rut_cliente'];
    $rut_cliente_ant = $_POST['rut_cliente_ant'];
    $giro_cliente = $_POST['giro_cliente'];
    $dire_cliente = $_POST['dire_cliente'];
    $comuna_cliente = $_POST['comuna_cliente'];
    $ciudad_cliente = $_POST['ciudad_cliente'];
    $correo_cliente = $_POST['correo_cliente'];
    $telef_cliente = $_POST['telef_cliente'];
    $contac_cliente = $_POST['contac_cliente'];
//    $descuento = $_POST['descuento'];

  // validar input

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

    // update data
    if ($valid) {
//  ver si existe el rut_cliente que se quiere actualizar si es diferente al anterior
  // search for data
      $existe = true;
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT rut_cliente FROM clientes WHERE rut_cliente = '".$rut_cliente."'";
      $stmt = $pdo->query($sql);
      $row = $stmt->fetch(PDO::FETCH_ASSOC); 
      if(!is_array($row)) {
        $existe = false;
      }
      Database::disconnect();

      if (($rut_cliente == $rut_cliente_ant) || (!$existe)) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE clientes
                SET nom_cliente = '$nom_cliente',
                    rut_cliente = '$rut_cliente',
                    giro_cliente = '$giro_cliente',
                    dire_cliente = '$dire_cliente',
                    comuna_cliente = '$comuna_cliente',
                    ciudad_cliente = '$ciudad_cliente',
                    correo_cliente = '$correo_cliente',
                    telef_cliente = '$telef_cliente',
                    contac_cliente = '$contac_cliente'
                    WHERE rut_cliente = '$rut_cliente_ant'";
//        echo $sql;            
        $filas = $pdo->exec($sql);
        Database::disconnect();
        if ($filas > 0) {
          $clienteModificado = true;
          $clienteModificadoControl = "1";
          $codigoControl = 'Cliente '.$rut_cliente.' modificado exitosamente...';
//          header("Location: ../clientes/read_update.php");
        }

      } else {
        $clienteModificadoControl = "2";
        $codigoControl = 'RUT Cliente '.$rut_cliente.' existe. Modifique el RUT...';
      }

    }
  } else {
  // search for data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM clientes WHERE rut_cliente = '".$rut_cliente."'";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach($rows as $row) {
      $nom_cliente = $row['nom_cliente'];
      $rut_cliente_ant = $row['rut_cliente'];
      $rut_cliente = $row['rut_cliente'];
      $giro_cliente = $row['giro_cliente'];
      $dire_cliente = $row['dire_cliente'];
      $comuna_cliente = $row['comuna_cliente'];
      $ciudad_cliente = $row['ciudad_cliente'];
      $correo_cliente = $row['correo_cliente'];
      $telef_cliente = $row['telef_cliente'];
      $contac_cliente = $row['contac_cliente'];
      $descuento = $row['descuento'];
    }
    Database::disconnect();
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
      <h3>Modificar Cliente</h3>
    </div>
    <div class="row m-3">
      <form class="form-horizontal needs-validation w-100" novalidate action="./update.php?rut_cliente= <?php echo $rut_cliente_ant; ?>" method="post">
        <div class="row">
          <div class="col-lg-6">
            <div class="d-flex flex-column col-lg-12 pr-0">
              <label class="control-label">Nombre</label>
              <input class="form-control form-control-sm <?php echo ($nom_clienteInvalid)?'is-invalid':''; ?>" name="nom_cliente" type="text"  placeholder="Nombre Cliente" value="<?php echo !empty($nom_cliente)?$nom_cliente:'';?>" onkeyup="this.value = this.value.toUpperCase();">
              <?php if (!empty($nom_clienteError)): ?>
                <small class="text-danger"><?php echo $nom_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-2 p-0">
            <div class="d-flex flex-column col-lg-12 p-0 has-error has-feedback <?php echo !empty($rut_clienteError)?'error':'';?>">
            <label class="control-label">RUT Cliente</label>
            <input class="form-control" name="rut_cliente_ant" type="hidden" value="<?php echo !empty($rut_cliente_ant)?$rut_cliente_ant:'';?>">
            <input class="form-control form-control-sm <?php echo ($rut_clienteInvalid)?'is-invalid':''; ?>" name="rut_cliente" type="text" placeholder="RUT Cliente" value="<?php echo !empty($rut_cliente)?$rut_cliente:'';?>">
            <?php if (!empty($rut_clienteError)): ?>
              <small class="text-danger"><?php echo $rut_clienteError;?></small>
            <?php endif;?>
            </div>
          </div>
          <div class="col-lg-3 p-0">
            <div class="d-flex flex-column col-lg-12 <?php echo !empty($giro_clienteError)?'error':'';?>">
              <label class="control-label">Giro</label>
              <input class="form-control form-control-sm <?php echo ($giro_clienteInvalid)?'is-invalid':''; ?>" name="giro_cliente" type="text"  placeholder="Giro Cliente" value="<?php echo !empty($giro_cliente)?$giro_cliente:'';?>">
              <?php if (!empty($giro_clienteError)): ?>
                <small class="text-danger"><?php echo $giro_clienteError;?></small>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 pr-0">
            <div class="d-flex flex-column col-lg-12 mt-2">
              <label class="control-label">Direccion Cliente</label>
              <input class="form-control form-control-sm <?php echo ($dire_clienteInvalid)?'is-invalid':''; ?>" name="dire_cliente" type="text" placeholder="Direccion Cliente" value="<?php echo !empty($dire_cliente)?$dire_cliente:'';?>">
              <?php if (!empty($dire_clienteError)): ?>
                <small class="text-danger"><?php echo $dire_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-3 p-0">
            <div class="d-flex flex-column col-lg-12 mt-2 p-0 has-error has-feedback ">
              <label class="control-label">Comuna</label>
              <input class="form-control form-control-sm <?php echo ($comuna_clienteInvalid)?'is-invalid':''; ?>" name="comuna_cliente" type="text" placeholder="Comuna Cliente" value="<?php echo !empty($comuna_cliente)?$comuna_cliente:'';?>">
              <?php if (!empty($comuna_clienteError)): ?>
                <small class="text-danger"><?php echo $comuna_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-3 p-0">
            <div class="d-flex flex-column col-lg-12 mt-2">
              <label class="control-label">Ciudad</label>
              <input class="form-control form-control-sm <?php echo ($ciudad_clienteInvalid)?'is-invalid':''; ?>" name="ciudad_cliente" type="text" placeholder="Ciudad Cliente" value="<?php echo !empty($ciudad_cliente)?$ciudad_cliente:'';?>">
              <?php if (!empty($ciudad_clienteError)): ?>
                <small class="text-danger"><?php echo $ciudad_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 pr-0">
            <div class="d-flex flex-column col-lg-12 mt-2">
              <label class="control-label">Contacto</label>
              <input class="form-control form-control-sm <?php echo ($contac_clienteInvalid)?'is-invalid':''; ?>" name="contac_cliente" type="text"  placeholder="Contacto Cliente" value="<?php echo !empty($contac_cliente)?$contac_cliente:'';?>">
              <?php if (!empty($contac_clienteError)): ?>
                <small class="text-danger"><?php echo $contac_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-4 p-0">
            <div class="d-flex flex-column col-lg-12 mt-2">
              <label class="control-label">Correo Electronico</label>
              <input class="form-control form-control-sm <?php echo ($correo_clienteInvalid)?'is-invalid':''; ?>" name="correo_cliente" type="text"  placeholder="Correo Cliente" value="<?php echo !empty($correo_cliente)?$correo_cliente:'';?>">
              <?php if (!empty($correo_clienteError)): ?>
                <small class="text-danger"><?php echo $correo_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-4 p-0">
            <div class="d-flex flex-column col-lg-12 mt-2">
              <label class="control-label">Telefono</label>
              <input class="form-control form-control-sm <?php echo ($telef_clienteInvalid)?'is-invalid':''; ?>" name="telef_cliente" type="text"  placeholder="Telefono Cliente" value="<?php echo !empty($telef_cliente)?$telef_cliente:'';?>">
              <?php if (!empty($telef_clienteError)): ?>
                <small class="text-danger"><?php echo $telef_clienteError;?></small>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $clienteModificadoControl; ?>" data-cliente="<?php echo $nom_cliente; ?>" data-action="clientes/read">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">MODIFICAR CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body"><?php echo $codigoControl; ?></div>
              <div class="modal-footer <?php echo ($clienteModificado)? 'mostrar':'ocultar'; ?>">
                <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-5"></div>
          <div class="col-lg-3 mt-4 <?php echo ($clienteModificado)?'ocultar':'mostrar'; ?>">
            <button type="submit" class="btn btn-dark py-2 px-3">Modificar</button>
            <a class="btn btn-outline-dark py-2 px-3 mx-3" href="./update.php" role="button">Atras</a>
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
