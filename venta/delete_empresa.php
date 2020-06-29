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

// Calculate number of CONSECUTIVO
  $fecha_now = new DateTime();
  $fecha_5atras = $fecha_now->modify("-5 day");
  $x = $fecha_5atras->format("Ymd");
  $x = $x + "0";
  $fecha_5 = (int) $x;
//  echo $fecha_5;

  $num_venta = null;
  $fecha_venta = null;
  $rut_cliente = null;
  $data_venta = null;
  $data_cliente = null;
    // keep track post values
  if ( !empty($_REQUEST)) {
    $accion = $_REQUEST['accion'];
    if (!empty($_REQUEST['numven'])) {
      $num_venta = $_REQUEST['numven'];
      $fecha_venta = $_REQUEST['fechaven'];
      $rut_cliente = $_REQUEST['rut'];
    }
  }
//echo $accion;
  if ($accion == "delete") {

    $cliente = "";
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM clientes WHERE rut_cliente = '$rut_cliente'";
    $stmt = $pdo->query($sql);
    if ( $stmt->rowCount() > 0 ) {
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
      }

      $data_cliente = $data_cliente.
            '<tr>'.
              '<td>'.$num_venta.'</td>'.
              '<td>'.$fecha_venta.'</td>'.
              '<td>'.$nom_cliente.'</td>'.
              '<td>'.$rut_cliente.'</td>'.
            '</tr>';
    } else {
      $data_cliente = $data_cliente.
            '<tr>'.
              '<td>'.$num_venta.'</td>'.
              '<td>'.$fecha_venta.'</td>'.
              '<td>Cliente no existe...</td>'.
              '<td>No existe...</td>'.
            '</tr>';
    }

    Database::disconnect();

  // search for COTIZACION data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM ventas WHERE num_venta = $num_venta";
  //echo $sql;
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data_venta = "<table id='cotizacionTabla' class='table table-hover table-sm'>
                    <thead>
                      <tr class='table-secondary'>
                        <th scope='col'>Marca</th>
                        <th scope='col'>Modelo</th>
                        <th scope='col'>Tipo</th>
                        <th scope='col'>Codigo</th>
                        <th scope='col'>Descripcion</th>
                        <th scope='col'>Cantidad</th>
                        <th scope='col'>Valor</th>
                        <th scope='col'>Total</th>
                      </tr>
                    </thead>
                    <tbody>";
    foreach($rows as $row){
      $codigo_parte = $row['codigo_parte'];
      $sql = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";

      $stmt = $pdo->query($sql);
      if ( $stmt->rowCount() > 0 ) {
        $row1s = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($row1s as $row1){
          $marca = $row1['marca'];
          $modelo = $row1['modelo'];
          $tipo_rep = $row1['tipo_rep'];
          $descripcion = $row1['descripcion'];
          $valor = $row1['valor'];
        }
        $total = number_format($valor*$row['cantidad'], 2, ',', '.');
        $data_venta = $data_venta.
                  "<tr>".
                    "<td>".$marca."</td>".
                    "<td>".$modelo."</td>".
                    "<td>".$tipo_rep."</td>".
                    "<td>".$row['codigo_parte']."</td>".
                    "<td>".$descripcion."</td>".
                    "<td>".$row['cantidad']."</td>".
                    "<td>".$valor."</td>".
                    "<td>".$total."</td>".
                  "</tr>";
      } else {
        $data_venta = $data_venta.
                "<tr>".
                  "<td>...</td>".
                  "<td>...</td>".
                  "<td>...</td>".
                  "<td>".$row['codigo_parte']."</td>".
                  "<td>Repuesto no existe...</td>".
                  "<td>".$row['cantidad']."</td>".
                  "<td>...</td>".
                  "<td>...</td>".
                "</tr>";
      }
      Database::disconnect();
    }
    $data_venta = $data_venta."</tbody></table>";
  }

  if ($accion == "deleteok") {
    if ( !empty($_GET)) {
      $num_venta = $_GET['num_venta'];
     } 
      // delete data
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "DELETE FROM ventas WHERE num_venta = ?";
      $del = $pdo->prepare($sql);
      $del->execute(array($num_venta));
      if ( $del->rowCount() > 0 ) {
        $ventaEliminar = true;
        $ventaEliminarControl = "1";
        $codigoControl = 'Venta Nº '.$num_venta.' eliminada exitosamente...';
      } else {
        $cotizacionEliminar = true;
        $cotizacionEliminarControl = "1";
        $codigoControl = 'Algo salió mal al eliminar la Venta '.$num_venta.' ...';
      }
      Database::disconnect();
//      header("Location: ../cotizacion/read_ud.php?accion=delete");
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

    <div class="row mt-3 no-gutters">
      <div class="col-lg-1"></div>
      <div class="col-lg-3"><h3 class="texto">
          Eliminar Venta
      </h3></div>
      <div class="col-lg-8"></div>
    </div>

    <div class="row mt-3">
      <div class="d-flex flex-column col-lg-2"></div>
      <div class="d-flex flex-column col-lg-8">
        <table id="ven_vigentesTabla" class="table table-hover table-sm">
          <tr class="table-secondary">
            <th scope="col">Venta Nº</th>
            <th scope="col">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">RUT</th>
          </tr>
          <tbody>
            <?php echo $data_cliente; ?>
            <?php echo $data_venta; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex flex-column col-lg-2"></div>
    </div>

    <div class="row">
      <div class="col-lg-4 mt-3"></div>
      <div class="col-lg-4 mt-3">
        <form class="form-horizontal" action="./delete_empresa.php?accion=deleteok&num_venta=<?php echo $num_venta; ?>" method="post">
          <div class="d-flex flex-column mt-3 <?php echo ($ventaBorrar)?'ocultar':'mostrar'; ?>">            
            <p class="alert alert-secondary" role="alert">Esta seguro de eliminar la Venta Nº<?php echo $num_venta; ?> ?</p>
            <div class="d-flex flex-row justify-content-center mt-3">
              <button type="submit" class="btn btn-danger mr-3">Eliminar</button>
              <a class="btn btn-outline-danger" href="../venta/read_ud.php?tipo=empresa" role="button">Atras</a>
            </div>
          </div>

        </form>
      </div>
      <div class="col-lg-4 mt-3"></div>
    </div>

    <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $ventaEliminarControl; ?>" data-cliente="<?php echo $codigo; ?>" data-action="venta/read_empresa">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">ELIMINAR VENTA</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div id="MyModalText" class="modal-body"><?php echo  $codigoControl; ?></div>
          <div class="modal-footer <?php echo ($ventaEliminar)? 'mostrar':'ocultar'; ?>">
            <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
          </div>
        </div>
      </div>
    </div>

  </div> <!-- /container -->

<!-- footer -->
  <footer>
    <div class="piepagina">
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

