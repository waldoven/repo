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
      
  if ( !empty($_GET['codigo'])) {
    $codigo = $_REQUEST['codigo'];
    $marca = $_REQUEST['marca'];
    $modelo = $_REQUEST['modelo'];
  }
  if ( null==$codigo ) {
      header("Location: ../index.php");
  }

  if ( !empty($_POST)) {
    $valid = true;

    $marcaError  = null;
    $modeloError = null;
    $tipoError   = null;
    $codigoError = null;
    $descripcionError = null;
    $costoError  = null;
    $valorError  = null;
    $valor1Error = null;
    $parteModificado = false;
    $parteModificadoControl = "0";

    $marcas  = $_POST['marcas'];
    $tiporep   = $_POST['tiporep'];
    $marca  = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $tipo   = $_POST['tipo'];
    $codigo = $_POST['codigo'];
    $codigo_ant  = $_POST['codigo_ant'];
    $descripcion = $_POST['descripcion'];
    $costo  = $_POST['costo'];
    $valor  = $_POST['valor'];
    $valor1 = 0.00;             /*  $_POST['valor1']; */

  // validar input
    if (empty($marca)) {
      $marcaInvalid = true;
      $marcaError = 'Introduzca la marca del vehiculo';
      $valid = false;
    }
     
    if (empty($modelo)) {
      $modeloInvalid = true;
      $modeloError = 'Introduzca el modelo del vehiculo';
      $valid = false;
    }

    if (empty($tipo)) {
      $tipoInvalid = true;
      $tipoError = 'Introduzca el tipo del repuesto';
      $valid = false;
    }

    if (empty($codigo)) {
      $codigoInvalid = true;
      $codigoError = 'Introduzca el codigo del repuesto';
      $valid = false;
    }
    if (empty($codigo)) {
      $descripcionInvalid = true;
      $descripcionError = 'Introduzca la descripcion del repuesto';
      $valid = false;
    }
    if (empty($costo)) {
      $costoInvalid = true;
      $costoError = 'Introduzca el costo del repuesto';
      $valid = false;
    }
    if (empty($valor)) {
      $valorInvalid = true;
      $valorError = 'Introduzca el valor de venta';
      $valid = false;
    }
    if (false) {              /*  empty($valor1)  */
      $valor1Invalid = true;
      $valor1Error = 'Introduzca el valor 1 de venta';
      $valid = false;
    }
    if ($costo > $valor) {
      $costoInvalid = true;
      $costoError = 'El costo no debe ser mayor que valor';
      $valid = false;
    }
  // update data
    if ($valid) {
      $existe = true;
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT codigo FROM partes WHERE codigo = '".$codigo."'";
  //echo $sql."<br>";
      $stmt = $pdo->query($sql);
      $rows = $stmt->fetch(PDO::FETCH_ASSOC);
      if(!is_array($rows)) {
        $existe = false;
      }
      Database::disconnect();

      if (($codigo == $codigo_ant) || (!$existe)) {
  // update data
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE partes 
                SET marca = '$marca',
                    modelo = '$modelo',
                    tipo_rep = '$tipo',
                    codigo = '$codigo',
                    descripcion = '$descripcion',
                    costo = '$costo',
                    valor = '$valor',
                    valor1 =  '$valor1'
                WHERE codigo = '$codigo_ant'";

        $filas = $pdo->exec($sql);
        Database::disconnect();
        if ($filas > 0) {
          $parteModificado = true;
          $parteModificadoControl = "1";
          $codigoControl = 'Repuesto '.$marca.' '.$codigo.' modificado exitosamente... !!';
        }
  //      header("Location: ../clientes/create.php");
      }
    } else {
      $parteModificadoControl = "2";
    }

  } else {
  // search for data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM partes WHERE codigo = '".$codigo."' AND marca = '".$marca."' AND  modelo = '".$modelo."'";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach($rows as $row) {
      $marca  = strtoupper($row['marca']);
      $modelo = strtoupper($row['modelo']);
      $tipo   = strtoupper($row['tipo_rep']);
      $codigo_ant = strtoupper($row['codigo']);
      $codigo = strtoupper($row['codigo']);
      $descripcion = strtoupper($row['descripcion']);
      $costo  = $row['costo'];
      $valor  = $row['valor'];
      $valor1 = $row['valor1'];
    }
    Database::disconnect();

  //echo $codigo."<br>";echo $marca."<br>";echo $modelo."<br>";echo $tipo."<br>";

    $marcas = "";
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT marca, sigla FROM marcas ORDER BY marca";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      if ($row['marca'] == $marca) {
        $marcas =  $marcas."<option selected>".$row['marca']."</option>";
      } else {
        $marcas =  $marcas."<option>".$row['marca']."</option>";
      }
    }
    Database::disconnect();
  //echo $marcas;
    // search for tipo repuesto data
    $tiporep = "";
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT tipo_rep, cod_tipo_rep FROM tiporepuesto ORDER BY tipo_rep";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      if ($row['tipo_rep'] == $tipo) {
        $tiporep =  $tiporep."<option selected>".$row['tipo_rep']."</option>";
      } else {
        $tiporep =  $tiporep."<option>".$row['tipo_rep']."</option>";
      }
    }
    Database::disconnect();
}

?>

<!DOCTYPE html>
  <html>
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
      <div class="row my-3">
        <div class="col-lg-3"><h3>Modificar Repuesto</h3></div>
      </div>
   
      <form class="form-horizontal needs-validation" novalidate action="./update.php?codigo= <?php echo $codigo_ant; ?>" method="post">
        <div class="row">

          <div class="d-flex flex-column col-lg-3 pr-0 has-error has-feedback">
            <label class="control-label">Marca</label>
            <input class="form-control form-control-sm" name="marcas" type="hidden" value="<?php echo !empty($marcas)?$marcas:'';?>">
            <select class="form-control form-control-sm" id="marcaSelect" name="marca">
              <?php echo $marcas;?>
            </select>
            <?php if (!empty($marcaError)): ?>
              <small class="text-danger"><?php echo $marcaError;?></small>
            <?php endif; ?>
          </div>

          <div class="d-flex flex-column col-lg-3 pr-0 has-error has-feedback <?php echo !empty($mobileError)?'error':'';?>">
            <label class="control-label">Tipo</label>
            <input class="form-control form-control-sm" name="tiporep" type="hidden" value="<?php echo !empty($tiporep)?$tiporep:'';?>">
            <select class="form-control form-control-sm" id="tiporepSelect" name="tipo">
              <?php echo $tiporep;?>
            </select>
            <?php if (!empty($tipoError)): ?>
              <small class="text-danger"><?php echo $tipoError;?></small>
            <?php endif;?>
          </div>

          <div class="d-flex flex-column col-lg-3 has-error has-feedback">
            <label class="control-label">Costo</label>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input class="form-control form-control-sm <?php echo ($costoInvalid)?'is-invalid':''; ?>" name="costo" type="text" placeholder="Costo del repuesto" value="<?php echo !empty($costo)?$costo:'';?>">
              <?php if (!empty($costoError)): ?>
                <small class="text-danger"><?php echo $costoError;?></small>
              <?php endif; ?>
            </div>
          </div>

          <div class="d-flex flex-column col-lg-3">
            <label class="control-label">Valor</label>
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input class="form-control form-control-sm <?php echo ($valorInvalid)?'is-invalid':''; ?>" name="valor" type="text"  placeholder="Valor venta del repuesto" value="<?php echo !empty($valor)?$valor:'';?>">
              <?php if (!empty($valorError)): ?>
                <small class="text-danger"><?php echo $valorError;?></small>
              <?php endif; ?>
            </div>
          </div>

        </div>

        <div class="row">

          <div class="d-flex flex-column col-lg-3 mt-3 pr-0">
            <label class="control-label">Modelo</label>
            <input class="form-control form-control-sm <?php echo ($modeloInvalid)?'is-invalid':''; ?>" name="modelo" type="text" placeholder="Modelo" value="<?php echo !empty($modelo)?$modelo:'';?>" onkeyup="this.value = this.value.toUpperCase();">
            <?php if (!empty($modeloError)): ?>
              <small class="text-danger"><?php echo $modeloError;?></small>
            <?php endif;?>
          </div>

          <div class="d-flex flex-column col-lg-3 mt-3 pr-0 has-error has-feedback">
            <label class="control-label">Codigo</label>
            <input class="form-control" name="codigo_ant" type="hidden" value="<?php echo !empty($codigo_ant)?$codigo_ant:'';?>">
            <input class="form-control form-control-sm <?php echo ($codigoInvalid)?'is-invalid':''; ?>" name="codigo" type="text" placeholder="Codigo del repuesto" value="<?php echo !empty($codigo)?$codigo:'';?>" onkeyup="this.value = this.value.toUpperCase();">
            <?php if (!empty($codigoError)): ?>
              <small class="text-danger"><?php echo $codigoError;?></small>
            <?php endif; ?>
          </div>

          <div class="d-flex flex-column col-lg-6 mt-3 has-error has-feedback ">
            <label class="control-label">Descripcion</label>
            <input class="form-control form-control-sm <?php echo ($descripcionInvalid)?'is-invalid':''; ?>" name="descripcion" type="text" placeholder="Descripcion del repuesto" value="<?php echo !empty($descripcion)?$descripcion:'';?>" onkeyup="this.value = this.value.toUpperCase();">
            <?php if (!empty($descripcionError)): ?>
              <small class="text-danger"><?php echo $descripcionError;?></small>
            <?php endif; ?>
          </div>
        </div>

        <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $parteModificadoControl; ?>" data-cliente="<?php echo $codigo; ?>" data-action="partes/read">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">MODIFICAR REPUESTO</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body"><?php echo $codigoControl; ?></div>
              <div class="modal-footer <?php echo ($parteModificadoControl)? 'mostrar':'ocultar'; ?>">
                <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-5"></div>
          <div class="col-lg-3 mt-5 <?php echo ($parteCreado)? 'ocultar':'mostrar'; ?>">
            <button type="submit" class="btn btn-dark px-4 py-1">Modificar</button>
            <a class="btn btn-outline-dark px-4 py-1 mx-3" href="./update.php" role="button">Atras</a>
          </div>
        </div>

      </form>
    </div> <!-- /container -->
<!-- footer -->
    <footer>
      <div class="container">
        <p class="text-right"></p>
      </div>
    </footer>
<!-- /footer --> 

    <script src="../js/jquery-3.4.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/main.js"></script>

  </body>
</html>

