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
  $activate = null;
  $tipo = $_GET["tipo"];
  $accion = $_GET['accion'];
//echo $tipo."<br />";
//echo $accion."<br />";

  if($tipo == "empresa") {
    $userid = $_GET["userid"];
    $rut_cliente = $_GET["rut"];
    $num_cotizacion = $_GET["numcot"];
    $fecha_cotizacion = $_GET["fechacot"];

    $iva = 0.00;
    $total_ac = 0.00;
    $neto = 0.00;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//  CONSECUTIVO number for ENTERPRISE 
    $sql = "SELECT num_venta,fecha_venta FROM ventas ORDER BY num_venta DESC LIMIT 1";
//echo $sql;
    $stmt = $pdo->query($sql);
    if ( $stmt->rowCount() > 0 ) {
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows as $row){
        $consec = str_split($row['num_venta'], 8);
      }
      $consecutivo = ($row['fecha_venta'] == date("Y-m-d")) ? $consec[1]+1 : 1 ;
    } else {
      $consecutivo = 1;
    }
    $fecha_venta = date("Ymd");
    $num_venta = $fecha_venta.$consecutivo;

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

    $sql = "SELECT * FROM cotizaciones WHERE num_cotizacion = $num_cotizacion";
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
    Database::disconnect();

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
                              <div class="col"><strong>Venta Nº </strong></div>
                              <div id="num_venta" class="col"><strong>'.$num_venta.'</strong></div>
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
                      </div>
                      <div class="toast-body">'.$data_cotiza.'</div>
                      <div class="row">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-3">
                          <a id="cvsave"class="btn btn-danger btn-sm btn-block mx-1" href="cotiz_venta.php?tipo=empresa&accion=save&userid='.$userid.'&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&rut='.$row['rut_cliente'].'"><strong>Convertir</strong></a>
                        </div>
                      </div>
                    </div>
                  </div>';

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($accion == "save") {
      $sql = "SELECT * FROM cotizaciones WHERE num_cotizacion=$num_cotizacion";
  //  echo $sql;
      $stmt = $pdo->query($sql);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows as $row){
        $cod_parte = $row['codigo_parte'];
        $cantidad = $row['cantidad'];
        $movil = $row['movil'];
        $solcompra = $row['solcompra'];
        $descuento = $row['descuento'];
        $userid = $row['userid'];

        $sql = "INSERT INTO ventas (rut_cliente,num_venta,fecha_venta,codigo_parte,cantidad,descuento,movil,solcompra,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
  //    $sqltest = "INSERT INTO ventas (rut_cliente,num_venta,fecha_venta,codigo_parte,cantidad,descuento,movil,solcompra,userid) values($rut_cliente,$num_venta,$fecha_venta,$codigo_parte,$cantidad,$descuento,$movil,$solcompra,$userid)";
  //    echo $sqltest;
        $q = $pdo->prepare($sql);
        $q->execute(array($rut_cliente,$num_venta,$fecha_venta,$cod_parte,$cantidad,$descuento,$movil,$solcompra,$userid));
        $activate = "saved";
      }
    }
    Database::disconnect();

  }

  if($tipo == "particular") {
    $userid = $_GET["userid"];
    $nom_cliente = $_GET["nombre"];
    $rut_cliente = $_GET["rut"];
    $num_cotizacion = $_GET["numcot"];
    $fecha_cotizacion = $_GET["fechacot"];
    $correo = $_GET["correo"];
    $neto = 0.00;
    $iva = 0.00;
    $total_ac = 0.00;

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//  CONSECUTIVO number for ENTERPRISE 
    $sql = "SELECT num_venta,fecha_venta FROM ventas_part ORDER BY num_venta DESC LIMIT 1";
//echo $sql;
    $stmt = $pdo->query($sql);
    if ( $stmt->rowCount() > 0 ) {
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows as $row){
        $consec = str_split($row['num_venta'], 8);
      }
      $consecutivo = ($row['fecha_venta'] == date("Y-m-d")) ? $consec[1]+1 : 1 ;
    } else {
      $consecutivo = 1;
    }
    $fecha_venta = date("Ymd");
    $num_venta = $fecha_venta.$consecutivo;
//echo $num_venta;
    $sql = "SELECT * FROM cotizaciones_part WHERE num_cotizacion = $num_cotizacion";
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
    Database::disconnect();

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
                              <div class="col"><strong>Venta Nº </strong></div>
                              <div id="num_venta" class="col"><strong>'.$num_venta.'</strong></div>
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
                              <div class="col"><strong>Correo : </strong></div>
                              <div class="col"><strong>'.$correo.'</strong></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="toast-body">'.$data_cotiza.'</div>
                      <div class="row">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-3">
                          <a id="cvsave"class="btn btn-danger btn-sm btn-block mx-1" href="cotiz_venta.php?tipo=particular&accion=save&userid='.$userid.'&numcot='.$row['num_cotizacion'].'&fechacot='.$row['fecha_cotizacion'].'&nombre='.$row['nombre'].'&rut='.$row['rut_cliente'].'&correo='.$correo.'"><strong>Convertir</strong></a>
                        </div>
                      </div>
                    </div>
                  </div>';

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($accion == "save") {
      $sql = "SELECT * FROM cotizaciones_part WHERE num_cotizacion=$num_cotizacion";
  //  echo $sql;
      $stmt = $pdo->query($sql);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows as $row){
        $cod_parte = $row['codigo_parte'];
        $cantidad = $row['cantidad'];
        $userid = $row['userid'];

        $sql = "INSERT INTO ventas_part (rut_cliente,nombre,correo,num_venta,fecha_venta,codigo_parte,cantidad,userid) values(?, ?, ?, ?, ?, ?, ?, ?)";
  //    $sqltest = "INSERT INTO ventas (rut_cliente,num_venta,fecha_venta,codigo_parte,cantidad,descuento,movil,solcompra,userid) values($rut_cliente,$num_venta,$fecha_venta,$codigo_parte,$cantidad,$descuento,$movil,$solcompra,$userid)";
  //    echo $sqltest;
        $q = $pdo->prepare($sql);
        $q->execute(array($rut_cliente,$nom_cliente,$correo,$num_venta,$fecha_venta,$cod_parte,$cantidad,$userid));
        $activate = "saved";
      }
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
        if ($tipo === "empresa"){
          echo "Convertir Cotización de Empresa en Venta";
        }
        if ($tipo == "particular"){
          echo "Convertir Cotización de Particular en Venta";
        }
      ?>
      </h3></div>
      
      <div id="activate" class="col-lg-1 d-none"><?php echo $activate; ?></div>
    </div>

    <?php echo $cot_toast; ?>

    <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $parteModificadoControl; ?>" data-cliente="<?php echo $codigo; ?>" data-action="venta/read_empresa">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">COTIZACION: CONVERTIR A VENTA</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div id="MyModalText" class="modal-body"></div>
          <div class="modal-footer">
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

  <script src="../js/jquery-3.4.0.min.js" type="text/javascript"></script>
  <script src="../js/inputmask/jquery.inputmask.js" type="text/javascript"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js" type="text/javascript"></script>

</body>
</html>

