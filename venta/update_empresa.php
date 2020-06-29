<?php
  define('BASE_DIR', '..');
  // Initialize the session
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $username = $_SESSION["username"];
    $userid = $_SESSION["id"];
    $rol = $_SESSION["rol"];  } else {
    header("location: ../user/login.php");
  }
  
  require 'database.php';
  date_default_timezone_set('America/Santiago');

  // search for CLIENTES data
  $num_venta = $data_venta = null;
  $num_row = 0;
// search for PARTES data
  $partes = "";
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM partes";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $partes = '<table id="listaPartes" class="table table-bordered table-hover table-sm">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Marca</th>
                  <th scope="col">Modelo</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Descripcion</th>
                  <th scope="col">.</th>
                  <th scope="col">Valor</th>
                  <th scope="col">.</th>
                  <th scope="col">.</th>
                  <th scope="col">.</th>
                  <th scope="col">.</th>
                </tr>
              </thead>
              <tbody>';
  foreach($rows as $row) { 
    $partes = $partes.
              '<tr>'.
                '<td>'.$row['marca'].'</td>'.
                '<td>'.$row['modelo'].'</td>'.
                '<td>'.$row['tipo_rep'].'</td>'.
                '<td>'.$row['codigo'].'</td>'.
                '<td>'.$row['descripcion'].'</td>'.
                '<td> </td>'.
                '<td>'.$row['valor'].'</td>'.
                '<td> </td>'.
                '<td> </td>'.
                '<td> </td>'.
                '<td> </td>'.
              '</tr>';
  }
  $partes = $partes.'</tbody></table>';
  Database::disconnect();
// End of search for PARTES data

  if (!empty($_REQUEST['numven'])) {
    $num_venta = $_REQUEST['numven'];
    $fecha_venta = $_REQUEST['fechaven'];
    $fecha_venta_p = new DateTime($fecha_venta);
    $fecha_venta_p = $fecha_venta_p->format('d/m/Y');
    $rut_cliente = $_REQUEST['rut'];
    $nom_cliente = $_REQUEST['nomcliente'];
    $desc = 0;      //$_REQUEST['desc'];

  // search for CLIENTES data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM ventas WHERE num_venta = $num_venta";
  //echo $sql;
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $codigo_parte = $row['codigo_parte'];
      $movil = $row['movil'];
      $solcompra = $row['solcompra'];
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
        $descuento = $row['descuento'];
        $valorcondesc = $valor * (1 - $descuento/100);
        $total = $valorcondesc * $row['cantidad'];        
        $data_venta = $data_venta.
                  "<tr id='row".$num_row."'>".
                    "<td>".$marca."</td>".
                    "<td>".$modelo."</td>".
                    "<td>".$tipo_rep."</td>".
                    "<td>".$row['codigo_parte']."</td>".
                    "<td>".$descripcion."</td>".
                    "<td>"."<input id='inp".$num_row."' class='text-right' type='text' name='cantidad' size='3' value='".$row['cantidad']."'>"."</td>".
                    "<td id='val".$num_row."' class='text-right'>".number_format($valor,2,'.','')."</td>".
                    "<td id='des".$num_row."' class='text-center'>".$descuento."</td>".
                    "<td id='vcd".$num_row."' class='text-right'>".number_format($valorcondesc,2,'.','')."</td>".
                    "<td id='tot".$num_row."' class='text-right'>".number_format($total,2,'.','')."</td>".
                    "<td id='del".$num_row."' class='text-center'>"."<i class='fas fa-trash-alt'></i>"."</td>".
                  "</tr>";
      } else {
        $data_venta = $data_venta.
                  "<tr id='row".$num_row."'>".
                    "<td colspan='8'>Repuesto eliminado de base de datos....</td>".
                    "<td id='del".$num_row."'>"."<i class='fas fa-trash-alt'></i>"."</td>".
                  "</tr>";
        }
        ++$num_row;
      }
//      $data_venta = $data_venta."</tbody></table>";
      Database::disconnect();

    } else {
      if ( null==$rut_cliente  ) {
          header("Location: ../index.php");
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
      <div class="col-lg-5"><h3 class="texto">Modificar Venta de Empresa</h3></div>
      <div id="cliente" class="col-1 d-none">empresa</div>
      <div class="d-flex flex-row col-lg-3"></div>
      <div class="col-lg-2"><h3>Venta Nº </h3></div>
      <div id="num_venta" class="col-lg-2"><h3><?php echo $num_venta; ?></h3></div>
    </div>

    <div class="row mt-3">
      <div class="d-flex flex-row col-lg-12">
        <div class="d-flex flex-column col-lg-2"></div>
        <div class="d-flex flex-column col-lg-4 pr-2">
          <label class="control-label">Razón Social</label>
          <div id="nomcliente" class="form-control form-control-sm"><?php echo $nom_cliente;?></div>
        </div>
        <div class="d-flex flex-column col-lg-1 px-0">
          <label class="control-label">RUT</label>
          <div id="rutcliente" class="form-control form-control-sm"><?php echo $rut_cliente;?></div>
        </div>

        <div id="divdesc" class="col-lg-1 d-flex flex-column px-2">
          <label for="descuento" class="control-label">Descuento</label>
          <div class="input-group input-group-sm">
            <input id="descuento" class="form-control form-control-sm text-right w-25" type="text" name="descuent" value="<?php echo $desc;?>" min="0" max="60" maxlength="2" onkeypress="return isNumeric(event)" />
            <div class="input-group-append">
              <span class="input-group-text">%</span>
            </div>
          </div>
        </div>

        <div class="d-flex flex-column col-lg-1 px-2">
          <label class="control-label">Fecha</label>
          <div id="fechaventa" class="form-control form-control-sm"><?php echo $fecha_venta_p;?></div>
        </div>
        <div class="d-flex flex-column col-lg-1"></div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-lg-2 d-flex flex-column ml-3"></div>
      <div id="divmovil" class="col-lg-3 d-flex flex-row ml-0 pr-2">
        <label for="movil" class="control-label pr-3">Movil</label>
        <input id="movil" class="form-control form-control-sm" type="text" name="movil" value="<?php echo $movil;?>" onkeyup="this.value = this.value.toUpperCase();" />
      </div>
      <div id="divsolcompra" class="col-lg-4 d-flex flex-row px-0">
        <label for="solcompra" class="col-lg-4 control-label pr-0 mr-2">Solicitud Compra</label>
        <input id="solcompra" class="form-control form-control-sm pl-0 ml-0" type="text" name="solcompra" value="<?php echo $solcompra;?>" onkeyup="this.value = this.value.toUpperCase();" />
      </div>
      <div id="divselitems" class="col-lg-2 d-flex flex-column ml-4">
        <button id="botonPartes" type="submit" class="btn btn-dark btn-sm shadow sm" onclick="openPartes()">Seleccionar Items</button>
      </div>
    </div>

    <div class="row mt-3 table-responsive-sm">
      <div class="col-lg-12 ">
        <table id="ventaTabla" class="table table-hover table-sm">
          <thead>
            <tr class="table-secondary">
              <th scope="col" class="align-middle">Marca</th>
              <th scope="col" class="align-middle">Modelo</th>
              <th scope="col" class="align-middle">Tipo</th>
              <th scope="col" class="align-middle">Codigo</th>
              <th scope="col" class="align-middle">Descripcion</th>
              <th scope="col" class="align-middle">Cantidad</th>
              <th scope="col" class="align-middle text-center">Valor</th>
              <th scope="col" class="align-middle text-center">Desc.%</th>
              <th scope="col" class="align-middle text-center">Valor <br>c/desc</th>
              <th scope="col" class="align-middle text-center">Total</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php echo $data_venta;?>
          </tbody>
          <tfoot></tfoot>
        </table>
      </div>
    </div>

    <div class="mt-3">
      <input id="calcular" type="button" class="btn btn-dark modify" value="Calcular">
      <input id="finalizar" type="button" class="btn btn-danger modify" value="Finalizar">
    </div>

  </div> <!-- /container -->

<!-- /PopUP partes -->
  <div class="partes-popup" id="FormPartes">
    <form action="#" class="partes-container">
      <div id="partes" class="right bg-light">
        <input id="partesSearch" type='text' placeholder='Buscar repuestos...''>
        <?php echo $partes; ?>
      </div>
      <button type="button" class="btn cancel" onclick="closePartes()">Cerrar</button>
    </form>
  </div>
<!-- /PopUP partes -->

  <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $ventaEliminarControl; ?>" data-action="venta/update">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">ACTUALIZAR VENTA</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div id="MyModalText" class="modal-body"><?php echo  $codigoControl; ?></div>
        <div id="MyModalFooter" class="modal-footer">
          <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
        </div>
      </div>
    </div>
  </div>

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
  <script src="../js/venupdate.js"></script>

</body>
</html>

