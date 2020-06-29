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
  $x = $x."0";
  $fecha_5 = (int) $x;

  $num_cotiza = null;
  $fecha_cotiza = null;
  $rut_cliente = null;
  $data_cotiza = null;

    // keep track get values
  if ( !empty($_GET)) {
    $accion = $_REQUEST['accion'];
    if (!empty($_REQUEST['numcot'])) {
      $num_cotiza = $_REQUEST['numcot'];
      $fecha_cotiza = $_REQUEST['fechacot'];
      $rut_cliente = $_REQUEST['rut'];
      $descuento = $_REQUEST['desc'];
      $delete = true;
    }
  }

  $clientes = "";
  $rutclientes = "";
  $cot_vigentes = "";
  $num = 1;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($accion == 'empresa') {
    $sql = "SELECT DISTINCT num_cotizacion, rut_cliente, fecha_cotizacion, descuento FROM cotizaciones WHERE num_cotizacion >= $fecha_5 ORDER BY fecha_cotizacion";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){

      $rut_cliente = $row['rut_cliente'];
      $descuento = $row['descuento'];
      $sql = "SELECT * FROM clientes WHERE rut_cliente = '".$rut_cliente."'";
      $stmt = $pdo->query($sql);
      $row1s = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($row1s as $row1) {
        $nom_cliente = $row1['nom_cliente'];
        $rut_cliente = $row1['rut_cliente'];
        $giro_cliente = $row1['giro_cliente'];
        $dire_cliente = $row1['dire_cliente'];
        $comuna_cliente = $row1['comuna_cliente'];
        $ciudad_cliente = $row1['ciudad_cliente'];
        $correo_cliente = $row1['correo_cliente'];
        $telef_cliente = $row1['telef_cliente'];
        $contac_cliente = $row1['contac_cliente'];
      }

      $fecha_cotizacion_ver = new DateTime($row['fecha_cotizacion']);
      $fecha_cotizacion_ver = $fecha_cotizacion_ver->format('d-m-Y');
      
      $cot_vigentes = $cot_vigentes.
            '<tr>'.
              '<td class="text-center">'.$row['num_cotizacion'].'</td>'.
              '<td class="text-center">'.$fecha_cotizacion_ver.'</td>'.
              '<td class="text-center">'.$nom_cliente.'</td>'.
              '<td class="text-center">'.$row['rut_cliente'].'</td>';

      $cot_vigentes = $cot_vigentes.'<td class="text-center"><a title="VER" class="btn btn-outline-dark btn-sm ml-2" href="read_empresa.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-eye"></i></a>';
      $cot_vigentes = $cot_vigentes.'<a title="MODIFICAR" class="btn btn-outline-danger btn-sm ml-2" href="update_empresa.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&desc='.$row['descuento'].'&nomcliente='.$nom_cliente.'"><i class="fas fa-pencil-alt"></i></a>';
      $cot_vigentes = $cot_vigentes.'<a title="ELIMINAR" class="btn btn-outline-dark btn-sm ml-2" href="delete_empresa.php?accion=delete&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-trash"></i></a></td>';
      $cot_vigentes = $cot_vigentes.'</tr>';
      if ($num_cotiza == $row['num_cotizacion']) {
         $cot_vigentes = $cot_vigentes.'<tr><td colspan="5">'.$data_cotiza.'</td></tr>';
      }
    }
  }

  if ($accion == 'particular') {
    $sql = "SELECT DISTINCT num_cotizacion, rut_cliente, nombre, correo, fecha_cotizacion FROM cotizaciones_part WHERE num_cotizacion >= $fecha_5 ORDER BY fecha_cotizacion";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){

      $rut_cliente = $row['rut_cliente'];
      $nom_cliente = $row['nombre'];
      $correo_cliente = $row['correo'];

      $fecha_cotizacion_ver = new DateTime($row['fecha_cotizacion']);
      $fecha_cotizacion_ver = $fecha_cotizacion_ver->format('d-m-Y');
      
      $cot_vigentes = $cot_vigentes.
            '<tr>'.
              '<td class="text-center">'.$row['num_cotizacion'].'</td>'.
              '<td class="text-center">'.$fecha_cotizacion_ver.'</td>'.
              '<td class="text-center">'.$nom_cliente.'</td>'.
              '<td class="text-center">'.$row['rut_cliente'].'</td>'.
              '<td  class="text-center"class="text-center">'.
                '<a title="VER" class="btn btn-outline-dark btn-sm" href="read_particular.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-eye"></i></a>'.
                '<a title="MODIFICAR" class="btn btn-outline-danger btn-sm ml-2" href="update_particular.php?p=part&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&nomcliente='.$nom_cliente.'"><i class="fas fa-pencil-alt"></i></a>'.
                '<a title="ELIMINAR" class="btn btn-outline-dark btn-sm ml-2" href="delete_particular.php?accion=delete&p=part&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&nomcliente='.$row['nombre'].'"><i class="fas fa-trash"></i></a></td>'.
              '</tr>';
      if ($num_cotiza == $row['num_cotizacion']) {
         $cot_vigentes = $cot_vigentes.'<tr><td colspan="5">'.$data_cotiza.'</td></tr>';
      }
    }
  }
  Database::disconnect();

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
      <div class="col-lg-6"><h3 class="texto">
      <?php
        if ($accion === "empresa"){
          echo "Ver / Modificar / Eliminar Cotización de Empresas";
        }
        if ($accion == "particular"){
          echo "Ver / Modificar / Eliminar Cotización de Particulares";
        }
      ?>
      </h3></div>
      <div class="col-lg-8"></div>
    </div>

    <div class="row mt-3">
      <div class="d-flex flex-column col-lg-2"></div>
      <div class="d-flex flex-column col-lg-8">
        <h3 class="texto">Cotizaciones vigentes</h3>
        <table id="cot_vigentesTabla" class="table table-hover table-sm">
          <tr class="table-secondary text-center">
            <th scope="col">Cotizacion Nº</th>
            <th scope="col">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">RUT</th>
            <th scope="col" class="text-center">Acción</th>
          </tr>
          <tbody>
            <?php echo $cot_vigentes; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex flex-column col-lg-2"></div>
    </div>

  </div> <!-- /container -->

<!-- /PopUP clientes -->
  <div class="clientes-popup" id="FormClientes">
    <form action="#" class="clientes-container">
      <div id="clientes" class="right bg-light">
        <ul>

        </ul>
      </div>
      <button type="button" class="btn cancel" onclick="closeClientes()">Cerrar</button>
    </form>
  </div>
<!-- /PopUP clientes -->

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
  <script src="../js/main.js"></script>

</body>
</html>

