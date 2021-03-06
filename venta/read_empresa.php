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

  require '../vendor/autoload.php';
  use Dompdf\Dompdf;
  use Dompdf\Options;

  require 'database.php';
  date_default_timezone_set('America/Santiago');
// Calculate number of CONSECUTIVO
  $fecha_now = new DateTime();
  $fecha_atras = $fecha_now->modify("-30 day");
  $fecha_5a = $fecha_atras->format("Ymd");
  $x = $fecha_atras->format("Ymd");
  $x = strval($x)."0";
  $fecha_5 = (int) $x;

  $num_venta = null;
  $fecha_venta = null;
  $nom_cliente = null;
  $rut_cliente = null;
  $data_venta = null;
  $ven_toast = null;
  $ventaTable = null;
  $movil = null;
  $solcompra = null;
  $descuento = 0;
  $neto = 0.00;
  $iva = 0.00;
  $total_ac = 0.00;
  if ( !empty($_GET)) {
    // keep track post values
    $num_venta = $_REQUEST['numven'];
    $fecha_venta = $_REQUEST['fechaven'];
    $fecha_venta_p = new DateTime($fecha_venta);
    $fecha_venta_p = $fecha_venta_p->format('d-m-Y');
    $rut_cliente = $_REQUEST['rut'];

  // search for CLIENTES data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM ventas WHERE num_venta = $num_venta";
//echo $sql;
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data_venta = "<table id='ventaTabla' class='table table-bordered table-hover table-sm'>
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
        $data_venta .= "<tr>".
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
        $data_venta .= "<tr>".
                          "<td colspan='7'>Repuesto eliminado de base de datos....</td>".
                        "</tr>";
        }
      }
      $iva = $neto * 0.19;
      $total_ac = $neto + $iva;
      $data_venta .= "<tr>".
                        "<td colspan='5'></td>".
                        "<td class='text-right table-secondary'>Total Neto</td>".
                        "<td class='text-right'>".number_format($neto, 2, '.', ',')."</td>".
                      "</tr>";
      $data_venta .= "<tr>".
                        "<td colspan='5'>Plazo de entrega en su bodega : 24 a 48 horas una vez recepcionada la O/C</td>".
                        "<td class='text-right table-secondary'>IVA 19%</td>".
                        "<td class='text-right'>".number_format($iva, 2, '.', ',')."</td>".
                      "</tr>";
      $data_venta .= "<tr>".
                        "<td colspan='5'></td>".
                        "<td class='text-right table-secondary font-weight-bold'>Total</td>".
                        "<td class='text-right font-weight-bold'>".number_format($total_ac, 2, '.', ',')."</td>".
                      "</tr>";
      $data_venta .= "</tbody></table>";
      Database::disconnect();

    }

  // search for CLIENTES data
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM ventas WHERE fecha_venta >= $fecha_5a ORDER BY num_venta";
//echo $sql;
  $stmt = $pdo->query($sql);
  if ( $stmt->rowCount() > 0 ) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $fechaconsec = $row['fecha_venta'];
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

  Database::disconnect();

  $clientes = "";
  $rutclientes = "";
  $ven_vigentes = "";
  $num = 1;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT DISTINCT num_venta, rut_cliente, fecha_venta 
          FROM ventas WHERE fecha_venta >= $fecha_5a ORDER BY fecha_venta";
  $stmt = $pdo->query($sql);
  if ( $stmt->rowCount() > 0 ) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $rut_cliente = $row['rut_cliente'];
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

      $fecha_venta_ver = new DateTime($row['fecha_venta']);
      $fecha_venta_ver = $fecha_venta_ver->format('d-m-Y');

      $ven_vigentes .= '<tr>'.
                          '<td class="text-center">'.$row['num_venta'].'</td>'.
                          '<td class="text-center">'.$fecha_venta_ver.'</td>'.
                          '<td class="text-center">'.$nom_cliente.'</td>'.
                          '<td class="text-center">'.$row['rut_cliente'].'</td>'.
                          '<td class="text-center">'.
                              '<a title="VER" class="btn btn-outline-dark btn-sm ml-0 mr-1" href="read_empresa.php?numven='.$row['num_venta'].'&fechaven='.$row['fecha_venta'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-eye"></i></a>'.
                              '<a title="MODIFICAR" class="btn btn-outline-danger btn-sm mx-1" href="update_empresa.php?numven='.$row['num_venta'].'&fechaven='.$row['fecha_venta'].'&rut='.$row['rut_cliente'].'&nomcliente='.$nom_cliente.'"><i class="fas fa-pencil-alt"></i></a>'.
                              '<a title="ELIMINAR" class="btn btn-outline-dark btn-sm mx-1" href="delete_empresa.php?accion=delete&userid='.$userid.'&numven='.$row['num_venta'].'&fechaven='.$row['fecha_venta'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-trash"></i></a>'.
                          '</td>'.
                        '</tr>';
      if ($num_venta == $row['num_venta']) {
        $ven_toast = '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="position: relative;">
                        <div class="toast" data-autohide="false" style=" min-width: 1000px; ">
                          <div class="toast-header">
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
                                  <div class="col"><strong>Venta Nº </strong></div>
                                  <div class="col"><strong>'.$num_venta.'</strong></div>
                                </div>
                                <div class="row">
                                  <div class="col"><strong>Fecha :  </strong></div>
                                  <div class="col"><strong>'.$fecha_venta_ver.'</strong></div>
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
                          </div>
                          <div class="toast-body">'.$data_venta.'</div>
                          <div class="row">
                            <div class="col-lg-9"></div>
                            <div class="col-lg-3">
                              <form action="read_empresa.php" method="get">
                                <input type="hidden" name="action" value="pdf">
                                <input type="hidden" name="numven" value="'.$num_venta.'">
                                <input type="hidden" name="fechaven" value="'.$fecha_venta.'">
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
  } else {
    $fecha_venta_ver = null;
  }
  Database::disconnect();

  $ventaTable = '<!DOCTYPE html>
                <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                  <title>IMPORTADORA REPO</title>
                  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
                  <link rel="stylesheet" href="../css/bootstrap.min.css">
                  <link rel="stylesheet" href="../css/font/all.css">
                  <link rel="stylesheet" href="../css/header.css" />
                </head>
                <body style="font-size: xx-small;">
                  <div class="row mt-3">
                    <table width="100%">
                      <tr>
                        <td width="25%"><strong>IMPORTADORA REPO SPA</strong></td>
                        <td rowspan="6" width="30%"><img src="'.BASE_DIR.'/img/repo20.png"></td>
                        <td width="15%"><strong>Venta N&ordm; </strong></td>
                        <td width="30%"><strong>'.$num_venta.'</strong></td>
                      </tr>
                      <tr>
                        <td><strong>RUT : 76.451.381-9</strong></td>
                        <td><strong>Fecha :  </strong></td>
                        <td><strong>'.$fecha_venta_ver.'</strong></td>
                      </tr>
                      <tr>
                        <td><strong>repuestos@repo.cl</strong></td>
                        <td><strong>Cliente :  </strong></td>
                        <td><strong>'.$nom_cliente.'</strong></td>
                      </tr>
                      <tr>
                        <td><strong>232667848 - 232667847</strong></td>
                        <td><strong>RUT : </strong></td>
                        <td><strong>'.$rut_cliente.'</strong></td>
                      </tr>
                      <tr>
                        <td> </td>
                        <td><strong>Móvil : </strong></td>
                        <td><strong>'.$movil.'</strong></td>
                      </tr>
                      <tr>
                        <td> </td>
                        <td><strong>Sol. Compra : </strong></td>
                        <td><strong>'.$solcompra.'</strong></td>
                      </tr>
                    </table>
                  </div>
                  <table id="tabla" width="100%">
                  <div class="row">'.$data_venta.'</div>
                </body></html>';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["action"])) {
    if ($_GET["action"] == "pdf") {

      $pdf = new DOMPDF();
      $pdf->set_option('isHtml5ParserEnabled', true);
      $pdf->set_option('defaultFont', 'Courier');
      $pdf->set_base_path(APPLICATION_PATH);
      $pdf->set_paper("letter", "landscape");
//      $pdf->setPaper('A4', 'Landscape');
      $pdf->load_html(utf8_decode($ventaTable));
      $pdf->render();
      $filename = "newpdffile";
  //    $output = $dompdf->output();
  //    file_put_contents("file.pdf", $output);
  //    $pdf->stream($filename+'.pdf');
      $pdf->stream($ventaTable,array("Attachment"=>0));

    }
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
      <div class="col-lg-4"><h3 class="texto">Ventas de Empresas</h3></div>
      <div class="col-lg-7"></div>
    </div>

    <?php echo $ven_toast; ?>

    <div class="row mt-3">
      <div class="d-flex flex-column col-lg-2"></div>
      <div class="d-flex flex-column col-lg-8">
        <h3 class="texto">Ventas</h3>
        <div class="table-responsive overflow_sticky">
          <table id="ven_vigentesTabla" class="table table-hover table-sm">
            <tr class="table-secondary text-center">
              <th scope="col">Venta Nº</th>
              <th scope="col">Fecha</th>
              <th scope="col">Cliente</th>
              <th scope="col">RUT</th>
              <th scope="col">Acción</th>
            </tr>
            <tbody>
              <?php echo $ven_vigentes; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="d-flex flex-column col-lg-2"></div>
    </div>

    <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $parteModificadoControl; ?>" data-cliente="<?php echo $codigo; ?>" data-action="partes/read">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">VER venta</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div id="MyModalText" class="modal-body"></div>
          <div class="modal-footer <?php echo ($parteModificadoControl)? 'mostrar':'ocultar'; ?>">
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
  <script src="../js/inputmask/jquery.inputmask.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

</body>
</html>

