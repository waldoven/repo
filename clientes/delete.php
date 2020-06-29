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
  $id = 0;
  $clienteBorrar = false;
  $clienteBorrarControl = "0";

  if ( !empty($_GET['rut_cliente'])) {
    $nom_cliente = $_REQUEST['nom_cliente'];
    $rut_cliente = $_REQUEST['rut_cliente'];
    $giro_cliente = $_REQUEST['giro_cliente'];
  }
   
  if ( !empty($_POST)) {
      // keep track post values
      $rut_cliente = $_POST['rut_cliente'];
      // delete data
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "DELETE FROM clientes WHERE rut_cliente = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($rut_cliente));
      Database::disconnect();
      $clienteBorrar = true;
      $clienteBorrarControl = "1";
      $codigoControl = 'Cliente '.$rut_cliente.' eliminado exitosamente...';
//        header("Location: ../clientes/read.php");
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
<!-- box1 -->
    <div id="box1" class="home">
      <?php require  BASE_DIR."/header.php"; ?>
    </div>
<!-- /box1 --> 

    <div id="navigation">
      <?php require  BASE_DIR."/navigation.php"; ?>
    </div>
<!-- /navbar --> 

    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 mt-3"></div>
        <div class="col-lg-6 mt-3">
          <div class="row mb-3 ">
            <h3>Eliminar Cliente</h3>
          </div>
          <table class="table table-hover table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">RUT Cliente</th>
                <th scope="col">Giro Cliente</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#</th>
                <td><?php echo $nom_cliente; ?></th>
                <td><?php echo $rut_cliente; ?></th>
                <td><?php echo $giro_cliente; ?></th>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-3 mt-3"></div>
      </div>

      <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $clienteBorrarControl; ?>" data-cliente="<?php echo $nom_cliente; ?>" data-action="clientes/read">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">ELIMINAR CLIENTE</h4>
<!--              <button type="button" class="close" data-dismiss="modal">&times;</button>  -->
            </div>
            <div class="modal-body"><?php echo  $codigoControl; ?></div>
            <div class="modal-footer <?php echo ($clienteBorrar)? 'mostrar':'ocultar'; ?>">
              <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 mt-3"></div>
        <div class="col-lg-4 mt-3">
          <form class="form-horizontal" action="./delete.php?rut_cliente=<?php echo $rut_cliente; ?>&nom_cliente=<?php echo $nom_cliente; ?>&giro_cliente=<?php echo $giro_cliente; ?>" method="post">
            <input type="hidden" name="rut_cliente" value="<?php echo $rut_cliente;?>"/>

            <div class="d-flex flex-column mt-3 <?php echo ($clienteBorrar)?'ocultar':'mostrar'; ?>">            
              <p class="alert alert-secondary" role="alert">Esta seguro de eliminar a <?php echo $rut_cliente; ?> ?</p>
              <div class="d-flex flex-row justify-content-center mt-3">
                <button type="submit" class="btn btn-danger mr-3">Eliminar</button>
                <a class="btn btn-outline-danger" href="../clientes/read_delete.php" role="button">Atras</a>
              </div>
            </div>

          </form>
        </div>
        <div class="col-lg-4 mt-3"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>

  </body>
</html>
