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
  $fecha_5atras = $fecha_now->modify("-5 day");
  $fecha_5a = $fecha_5atras->format("Ymd");
  $x = $fecha_5atras->format("Ymd");
  $x = $x + "0";
  $fecha_5 = (int) $x;
//  echo $fecha_5;

  $num_cotiza = null;
  $fecha_cotiza = null;
  $nom_cliente = null;
  $rut_cliente = null;
  $correo_cliente = null;
  $data_cotiza = null;
  $cot_toast = null;
  $cotizaTable = null;
  $descuento = 0;
  $neto = 0.00;
  $iva = 0.00;
  $total_ac = 0.00;
  if ( !empty($_GET)) {
    // keep track post values
    $num_cotiza = $_REQUEST['numcot'];
    $fecha_cotiza = $_REQUEST['fechacot'];
    $fecha_cotiza_p = new DateTime($fecha_cotiza);
    $fecha_cotiza_p = $fecha_cotiza_p->format('d-m-Y');
    $rut_cliente = $_REQUEST['rut'];

  // search for CLIENTES data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM cotizaciones_part WHERE num_cotizacion = $num_cotiza";
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
        }
        $total = $valor*$row['cantidad'];
        $data_cotiza .= "<tr>".
                          "<td>".$row['codigo_parte']."</td>".
                          "<td>".$descripcion."</td>".
                          "<td class='text-center'>".$row['cantidad']."</td>".
                          "<td class='text-right'>".number_format($valor, 2, '.', ',')."</td>".
                          "<td class='text-right'>".number_format($total, 2, '.', ',')."</td>".
                        "</tr>";
        $neto = $total+$neto;
      } else {
        $data_cotiza .= "<tr>".
                          "<td colspan=5>Repuesto eliminado de base de datos....</td>".
                        "</tr>";
        }
      }
      $iva = $neto * 0.19;
      $total_ac = $neto + $iva;
      $data_cotiza .= "<tr>".
                        "<td colspan='3'></td>".
                        "<td class='text-right table-secondary'>Total Neto</td>".
                        "<td class='text-right'>".number_format($neto, 2, '.', ',')."</td>".
                      "</tr>";
      $data_cotiza .= "<tr>".
                        "<td colspan='3'>Plazo de entrega en su bodega : 24 a 48 horas una vez recepcionada la O/C</td>".
                        "<td class='text-right table-secondary'>IVA 19%</td>".
                        "<td class='text-right'>".number_format($iva, 2, '.', ',')."</td>".
                      "</tr>";
      $data_cotiza .= "<tr>".
                        "<td colspan='3'></td>".
                        "<td class='text-right table-secondary font-weight-bold'>Total</td>".
                        "<td class='text-right font-weight-bold'>".number_format($total_ac, 2, '.', ',')."</td>".
                      "</tr>";
      $data_cotiza .= "</tbody></table>";
      Database::disconnect();

    }

  $clientes = "";
  $rutclientes = "";
  $cot_vigentes = "";
  $num = 1;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT DISTINCT num_cotizacion, rut_cliente, nombre, correo, fecha_cotizacion FROM cotizaciones_part WHERE fecha_cotizacion >= $fecha_5a ORDER BY fecha_cotizacion";
//echo $sql;
  $stmt = $pdo->query($sql);
  if ( $stmt->rowCount() > 0 ) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $rut_cliente = $row['rut_cliente'];
      $nom_cliente = $row['nombre'];
      $correo_cliente = $row['correo'];
//echo "correo  ".$correo_cliente;
      $fecha_cotizacion_ver = new DateTime($row['fecha_cotizacion']);
      $fecha_cotizacion_ver = $fecha_cotizacion_ver->format('d-m-Y');

      $cot_vigentes .= '<tr>'.
                          '<td class="text-center">'.$row['num_cotizacion'].'</td>'.
                          '<td class="text-center">'.$fecha_cotizacion_ver.'</td>'.
                          '<td class="text-center">'.$nom_cliente.'</td>'.
                          '<td class="text-center">'.$row['rut_cliente'].'</td>'.
                          '<td class="text-center">'.
                            '<a title="VER" class="btn btn-outline-dark btn-sm" href="read_particular.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><i class="fas fa-eye"></i></a>'.
                            '<a title="MODIFICAR" class="btn btn-outline-danger btn-sm ml-2" href="update_particular.php?numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&desc='.$descuento.'&nomcliente='.$nom_cliente.'"><i class="fas fa-pencil-alt"></i></a>'.
                            '<a title="ELIMINAR" class="btn btn-outline-dark btn-sm ml-2" href="delete_particular.php?accion=delete&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'&nomcliente='.$nom_cliente.'"><i class="fas fa-trash"></i></a>'.
                          '</td>'.
                        '</tr>';
      if ($num_cotiza == $row['num_cotizacion']) {
        $cot_toast = '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="position: relative;">
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
                                  <div class="col"><strong>Cotización Nº </strong></div>
                                  <div class="col"><strong>'.$num_cotiza.'</strong></div>
                                </div>
                                <div class="row">
                                  <div class="col"><strong>Fecha :  </strong></div>
                                  <div class="col"><strong>'.$fecha_cotizacion_ver.'</strong></div>
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
                                  <div class="col"><strong>Correo : </strong></div>
                                  <div class="col"><strong>'.$correo_cliente.'</strong></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="toast-body">'.$data_cotiza.'</div>
                          <div class="row">
                            <div class="col-lg-9"></div>
                            <div class="col-lg-3">
                              <form action="read.php" method="get">
                                <input type="hidden" name="action" value="pdf">
                                <input type="hidden" name="numcot" value="'.$num_cotiza.'">
                                <input type="hidden" name="fechacot" value="'.$fecha_cotiza.'">
                                <input type="hidden" name="rut" value="'.$rut_cliente.'">
                                <button class="btn btn-secondary btn-lg mb-3" type="submit" formaction="read_particular.php"> 
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
    $fecha_cotizacion_ver = null;
  }
  Database::disconnect();

  $cotizaTable = '<!DOCTYPE html>
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
                    <table width="100vw">
                      <tr>
                        <td width="25vw"><strong>IMPORTADORA REPO SPA</strong></td>
                        <td rowspan="5" width="30vw"><img src="'.BASE_DIR.'/img/repo20.png"></td>
                        <td width="15vw"><strong>Cotizaci&oacute;n N&ordm; </strong></td>
                        <td width="30vw"><strong>'.$num_cotiza.'</strong></td>
                      </tr>
                      <tr>
                        <td><strong>RUT : 76.451.381-9</strong></td>
                        <td><strong>Fecha :  </strong></td>
                        <td><strong>'.$fecha_cotizacion_ver.'</strong></td>
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
                        <td><strong>Correo : </strong></td>
                        <td><strong>'.$correo_cliente.'</strong></td>
                      </tr>
                      <tr>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                      </tr>
                    </table>
                  </div>
                  <table id="tabla" width="100vw">
                  <div class="row">'.$data_cotiza.'</div>
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
      $pdf->load_html(utf8_decode($cotizaTable));
      $pdf->render();
      $filename = "newpdffile";
  //    $output = $dompdf->output();
  //    file_put_contents("file.pdf", $output);
  //    $pdf->stream($filename+'.pdf');
      $pdf->stream($cotizaTable,array("compress"=>0, "Attachment"=>0));

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
      <div class="col-lg-4"><h3 class="texto">Cotizaciones de Particulares</h3></div>
      <div class="col-lg-7"></div>
    </div>

    <?php echo $cot_toast; ?>

    <div class="row mt-3">
      <div class="d-flex flex-column col-lg-2"></div>
      <div class="d-flex flex-column col-lg-8">
        <h3 class="texto">Cotizaciones vigentes</h3>
        <div class="table-responsive overflow_sticky">
          <table id="cot_vigentesTabla" class="table table-hover table-sm">
            <tr class="table-secondary text-center">
              <th scope="col">Cotizacion Nº</th>
              <th scope="col">Fecha</th>
              <th scope="col">Cliente</th>
              <th scope="col">RUT</th>
              <th scope="col">Ver</th>
            </tr>
            <tbody>
              <?php echo $cot_vigentes; ?>
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
            <h4 class="modal-title">VER COTIZACION</h4>
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

