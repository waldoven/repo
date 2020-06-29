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
  $cot_vigentes = null;
  $cot_toast = null;
  $descuento = 0;
  $neto = 0.00;
  $iva = 0.00;
  $total_ac = 0.00;

// keep track get values  
  $tipo = 'empresa';
  if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
  } else {
    $accion = null;
  }
  
  if ($tipo == "empresa") {
    if (!empty($_REQUEST['numcot'])) {
      $num_cotiza = $_REQUEST['numcot'];
      $fecha_cotiza = $_REQUEST['fechacot'];
      $rut_cliente = $_REQUEST['rut'];
    }
    $cot_vigentes = "";
    $num = 1;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT num_cotizacion, rut_cliente, fecha_cotizacion  FROM cotizaciones WHERE num_cotizacion >= $fecha_5 ORDER BY fecha_cotizacion";
//echo $sql;
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $rut_cliente = $row['rut_cliente'];
      $fecha_cotizacion = $row['fecha_cotizacion'];

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
      $cot_vigentes = $cot_vigentes.'<td class="text-center"><a class="btn btn-outline-dark btn-sm ml-0 mr-1" href="read_ud.php?tipo=empresa&accion=ver&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-eye"></i></a>';
      $cot_vigentes = $cot_vigentes.'<a class="btn btn-outline-danger btn-sm mx-1" href="update_empresa.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&nomcliente='.$nom_cliente.'"><i class="fas fa-pencil-alt"></i></a>';
      $cot_vigentes = $cot_vigentes.'<a class="btn btn-outline-dark btn-sm mx-1" href="cotiz_venta.php?tipo=empresa&accion=con&userid='.$userid.'&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-play"></i></a></td>';
      $cot_vigentes = $cot_vigentes.'</tr>';
      if ($num_cotiza == $row['num_cotizacion']) {
         $cot_vigentes = $cot_vigentes.'<tr><td colspan="5">'.$data_cotiza.'</td></tr>';
      }
    }

    if ($accion == "ver") {

      $sql = "SELECT * FROM cotizaciones WHERE num_cotizacion = $num_cotiza";
  //echo $sql;
      $stmt = $pdo->query($sql);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $data_cotiza = "<table id='cotizacionTabla' class='table table-bordered table-hover table-sm'>
                      <thead class='text-center'>
                        <tr class='table-secondary'>
                          <th>Codigo</th>
                          <th>Descripcion</th>
                          <th>Cantidad</th>
                          <th>Valor x unidad</th>
                          <th>Descuento %</th>
                          <th>Valor con Desc.</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>";
      foreach($rows as $row){
        $codigo_parte = $row['codigo_parte'];
        $sql = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";
        $stmt1 = $pdo->query($sql);

        if ( $stmt1->rowCount() > 0 ) {
          $row1s = $stmt1->fetchAll(PDO::FETCH_ASSOC);
          foreach($row1s as $row1){
            $marca = $row1['marca'];
            $modelo = $row1['modelo'];
            $tipo_rep = $row1['tipo_rep'];
            $descripcion = $row1['descripcion'];
            $valor = $row1['valor'];
            $descuento = $row['descuento'];
            $valordesc = $valor * (1.0 - ($descuento * 0.01));
            $movil = $row['movil'];
            $solcompra = $row['solcompra'];
          }
          $total = $valordesc*$row['cantidad'];
          $data_cotiza .= "<tr>".
                            "<td>".$row['codigo_parte']."</td>".
                            "<td>".$descripcion."</td>".
                            "<td class='text-center'>".$row['cantidad']."</td>".
                            "<td class='text-right'>".number_format($valor, 2, '.', ',')."</td>".
                            "<td class='text-center'>".$row['descuento']."</td>".
                            "<td class='text-right'>".number_format($valordesc, 2, '.', ',')."</td>".
                            "<td class='text-right'>".number_format($total, 2, '.', ',')."</td>".
                          "</tr>";
          $neto = $total+$neto;
        } else {
          $data_cotiza .= "<tr>".
                            "<td colspan='7'>Repuesto eliminado de base de datos....</td>".
                          "</tr>";
          }
        }
        $iva = $neto * 0.19;
        $total_ac = $neto + $iva;
        $data_cotiza .= "<tr>".
                          "<td colspan='5'></td>".
                          "<td class='text-right table-secondary'>Total Neto</td>".
                          "<td class='text-right'>".number_format($neto, 2, '.', ',')."</td>".
                        "</tr>";
        $data_cotiza .= "<tr>".
                          "<td colspan='5'>Plazo de entrega en su bodega : 24 a 48 horas una vez recepcionada la O/C</td>".
                          "<td class='text-right table-secondary'>IVA 19%</td>".
                          "<td class='text-right'>".number_format($iva, 2, '.', ',')."</td>".
                        "</tr>";
        $data_cotiza .= "<tr>".
                          "<td colspan='5'></td>".
                          "<td class='text-right table-secondary font-weight-bold'>Total</td>".
                          "<td class='text-right font-weight-bold'>".number_format($total_ac, 2, '.', ',')."</td>".
                        "</tr>";
        $data_cotiza .= "</tbody></table>";

        $cot_toast = '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="position: relative;">
                        <div class="toast" data-autohide="false" style=" min-width: 1000px; ">';
        $cot_toast .=  '<div class="toast-header">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="row">
                                <div class="col"><strong>IMPORTADORA REPO SPA</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>RUT 76.451.381-9</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>repuestos@repo.cl</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>232667848 - 232667847</strong></div>
                              </div>
                            </div>
                            <div class="col-lg-4"><img src="'.BASE_DIR.'/img/repo.png" alt="" width="70%" ></div>
                            <div class="col-lg-4">
                              <div class="row">
                                <div class="col"><strong>Cotización Nº </strong></div>
                                <div class="col"><strong>'.$num_cotiza.'</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>Fecha :  </strong></div>
                                <div class="col"><strong>'.$fecha_cotizacion.'</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>Cliente :  </strong></div>
                                <div class="col"><strong>'.$nom_cliente.'</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>RUT : </strong></div>
                                <div class="col"><strong>'.$rut_cliente.'</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>Móvil : </strong></div>
                                <div class="col"><strong>'.$movil.'</strong></div>
                              </div>
                              <div class="row">
                                <div class="col"><strong>Sol. Compra : </strong></div>
                                <div class="col"><strong>'.$solcompra.'</strong></div>
                              </div>
                            </div>
                          </div>
                        </div>';

//echo $cot_toast;

        $cot_toast .=  '<div class="toast-body">'.$data_cotiza.'</div>
                        <div class="row">
                          <div class="col-lg-9"></div>
                          <div class="col-lg-3">
                            <form action="read_empresa.php" method="get">
                              <input type="hidden" name="action" value="pdf">
                              <input type="hidden" name="numcot" value="'.$num_cotiza.'">
                              <input type="hidden" name="fechacot" value="'.$fecha_cotiza.'">
                              <input type="hidden" name="rut" value="'.$rut_cliente.'">
                              <button class="btn btn-secondary btn-lg mb-3" type="submit" formaction="read_empresa.php"> 
                                <span aria-hidden="true">Imprimir</span>
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>';
    }
  }

  if ($_GET['tipo'] == "particular") {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT num_cotizacion FROM cotizaciones_part WHERE num_cotizacion >= $fecha_5 ORDER BY num_cotizacion";
//echo $sql;
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $num_cotizacion = $row['num_cotizacion'];
      $sql = "SELECT * FROM cotizaciones_part WHERE num_cotizacion = $num_cotizacion LIMIT 1";
      $stmt = $pdo->query($sql);
      $rows1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows1 as $row1){
        $rut_cliente = $row1['rut_cliente'];
        $nom_cliente = $row1['nombre'];
        $correo = $row1['correo'];
        $fecha_cotizacion_ver = new DateTime($row1['fecha_cotizacion']);
        $fecha_cotizacion_ver = $fecha_cotizacion_ver->format('d-m-Y');
        
        $cot_vigentes = $cot_vigentes.
                        '<tr>'.
                          '<td class="text-center">'.$num_cotizacion.'</td>'.
                          '<td class="text-center">'.$fecha_cotizacion_ver.'</td>'.
                          '<td class="text-center">'.$nom_cliente.'</td>'.
                          '<td class="text-center">'.$rut_cliente.'</td>';

        $cot_vigentes = $cot_vigentes.'<td class="text-center"><a class="btn btn-outline-danger btn-sm ml-0 mr-1" href="update_particular.php?p=part&numcot='.$num_cotizacion.'&fechacot='.$fecha_cotizacion_ver.'&rut='.$rut_cliente.'&nomcliente='.$nom_cliente.'"><i class="fas fa-eye"></i></a>';
        $cot_vigentes = $cot_vigentes.'<a class="btn btn-outline-dark btn-sm mx-1" href="delete.php?tipo=delete&p=part&numcot='.$num_cotizacion.'&fechacot='.$fecha_cotizacion_ver.'&rut='.$rut_cliente.'"><i class="fas fa-pencil-alt"></i></a></td>';
        $cot_vigentes = $cot_vigentes.'</tr>';
      }
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
      <div class="col-lg-3"></div>
      <div class="col-lg-6"><h3 class="texto">
      <?php
        if ($tipo === "empresa"){
          echo "Convertir Cotización de Empresa en Venta";
        }
        if ($tipo == "particular"){
          echo "Convertir Cotización de Particular";
        }
      ?>
      </h3></div>
      <div id="tipo_cliente" class="col-lg-5 d-none">
      <?php
        echo $tipo;
      ?>
      </div>
    </div>
    <?php echo $cot_toast; ?>
    <div class="row mt-3">
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
      <div class="d-flex flex-column col-lg-8">
        <h4 class="texto">Cotizaciones vigentes</h4>
        <table id="cot_vigentesTabla" class="table table-hover table-sm">
          <tr class="table-secondary text-center">
            <th scope="col">Cotizacion Nº</th>
            <th scope="col">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">RUT</th>
            <th scope="col">Acción</th>
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

