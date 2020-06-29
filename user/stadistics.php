<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
  $userid = $_SESSION["id"];
  $rol = $_SESSION["rol"];
} else {
  header("location: ./login.php");
}

// config vars
  define('BASE_DIR', '..');
  require 'database.php';
  $fecha_hoy = date("Y-m-d");
  list($ano_hoy, $mes_hoy, $dia_hoy) = explode("-",$fecha_hoy);
  $users = array(null);
  $mes_string = array("","ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
  $mes_search = $mes_hoy;
  $ano_search = $ano_hoy;
  for ($i=0; $i<=12; $i++){
    $mes_search = (is_string($mes_search)) ? (int)$mes_search : $mes_search;
//echo $mes_search." / ".$ano_search."<br>";
    $search[$i][0] = $mes_search;       // mes
    $search[$i][1] = $ano_search;       // año
    $mes_search = ($mes_search > 1) ? --$mes_search : 12 ;
    $ano_search = ($mes_search == 12) ? $ano_search-1 : $ano_search;
//echo $mes_search." / ".$ano_search."<br>";
  }

  $user_index = 0;

// Prepare a select statement
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT id, username, rol FROM usuarios WHERE rol = 'Vendedor'";
	if($stmt = $pdo->query($sql)){

		if ( $stmt->rowCount() > 0 ) {
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach($rows as $row){

        for ($i=0; $i<12; $i++){
          $users[$user_index][$i][0] = $row["username"];
          $users[$user_index][$i][1] = $row["id"];
          $userid = $users[$user_index][$i][1];
          $mes_search = $search[$i][0];
          $ano_search = $search[$i][1];

/***  Count number of Cotizaciones Empresas   **************************************************************************/

          $sql1 = "SELECT count(DISTINCT num_cotizacion) AS cotizas_user FROM cotizaciones WHERE month(fecha_cotizacion)=$mes_search AND year(fecha_cotizacion)=$ano_search AND userid=$userid";
          $stmt1 = $pdo->query($sql1);
          $rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
//echo "i : $i ".$sql1."<br>";
          foreach($rows1 as $row1){
            $users[$user_index][$i][2] = ($row1["cotizas_user"] != 0) ? $row1["cotizas_user"] : '-';     //  cantidad de cotizaciones
          }
/***  Sum amount of Cotizaciones Empresas   **************************************************************************/

          $monto_total = 0.0;
          $sql11 = "SELECT DISTINCT num_cotizacion AS num_coti FROM cotizaciones WHERE month(fecha_cotizacion)=$mes_search AND year(fecha_cotizacion)=$ano_search AND userid=$userid";
          $stmt11 = $pdo->query($sql11);
          $rows11 = $stmt11->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows11 as $row11){
//echo $row11["num_coti"]."<br>" ;
            $neto = 0.0;
            $sqlcot = "SELECT * FROM cotizaciones WHERE num_cotizacion = ".$row11['num_coti']."";
//echo $sqlcot."<br>" ;
            $stmtcot = $pdo->query($sqlcot);
            $rowscot = $stmtcot->fetchAll(PDO::FETCH_ASSOC);
            foreach($rowscot as $rowcot){
              $codigo_parte = $rowcot['codigo_parte'];
              $sqlcot1 = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";
              $stmtcot1 = $pdo->query($sqlcot1);

              if ( $stmtcot1->rowCount() > 0 ) {
                $row1p = $stmtcot1->fetchAll(PDO::FETCH_ASSOC);
                foreach($row1p as $rowp){
                  $valor = $rowp['valor'];
                  $descuento = $rowcot['descuento'];
                  $valordesc = $valor * (1.0 - ($descuento * 0.01));
                }
                $total = $valordesc*$rowcot['cantidad'];
                $neto = $total+$neto;
              }
              $iva = $neto * 0.19;
              $total_ac = $neto + $iva;
//echo $row11["num_coti"]."--->".$total_ac."<br>" ;
            }
            $monto_total += $total_ac;
          }
          $users[$user_index][$i][11] = ($monto_total != 0) ? number_format($monto_total, 0, ',', '.') : '-';     //  cantidad de cotizaciones

/***  Count number of Cotizaciones Particulares   *******************************************************************************/

          $sql2= "SELECT count(DISTINCT num_cotizacion) AS cotizas_user FROM cotizaciones_part WHERE month(fecha_cotizacion)=$mes_search AND year(fecha_cotizacion)=$ano_search AND userid=$userid";
          $stmt2 = $pdo->query($sql2);
          $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows2 as $row2){
            $users[$user_index][$i][3] = ($row2["cotizas_user"] != 0) ? $row2["cotizas_user"] : '-';     //  cantidad de cotizaciones
          }

/*** Sum amount of Cotizaciones Particulares    *******************************************************************************/

          $monto_total = 0.0;
          $sql11 = "SELECT DISTINCT num_cotizacion AS num_coti FROM cotizaciones_part WHERE month(fecha_cotizacion)=$mes_search AND year(fecha_cotizacion)=$ano_search AND userid=$userid";
          $stmt11 = $pdo->query($sql11);
          $rows11 = $stmt11->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows11 as $row11){
//echo $row11["num_coti"]."<br>" ;
            $neto = 0.0;
            $sqlcot = "SELECT * FROM cotizaciones_part WHERE num_cotizacion = ".$row11['num_coti']."";
//echo $sqlcot."<br>" ;
            $stmtcot = $pdo->query($sqlcot);
            $rowscot = $stmtcot->fetchAll(PDO::FETCH_ASSOC);
            foreach($rowscot as $rowcot){
              $codigo_parte = $rowcot['codigo_parte'];
              $sqlcot1 = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";
              $stmtcot1 = $pdo->query($sqlcot1);

              if ( $stmtcot1->rowCount() > 0 ) {
                $row1p = $stmtcot1->fetchAll(PDO::FETCH_ASSOC);
                foreach($row1p as $rowp){
                  $valor = $rowp['valor'];
                }
                $total = $valor * $rowcot['cantidad'];
                $neto = $total + $neto;
              }
              $iva = $neto * 0.19;
              $total_ac = $neto + $iva;
//echo $row11["num_coti"]."--->".$total_ac."<br>" ;
            }
            $monto_total += $total_ac;
          }
          $users[$user_index][$i][12] = ($monto_total != 0) ? number_format($monto_total, 0, ',', '.') : '-';     //  cantidad de cotizaciones

/***  Count number of Ventas Empresas   *******************************************************************************/

          $sql3 = "SELECT count(DISTINCT num_venta) AS ventas_user FROM ventas WHERE userid=$userid AND month(fecha_venta)=$mes_search AND year(fecha_venta)=$ano_search";
          $stmt3 = $pdo->query($sql3);
          $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows3 as $row3){
            $users[$user_index][$i][4] = ($row3["ventas_user"] != 0) ? $row3["ventas_user"] : '-';     //  cantidad de ventas
          }

/***  Sum amount of Ventas Empresas   **************************************************************************/

          $monto_total = 0.0;
          $sql11 = "SELECT DISTINCT num_venta AS num_venta FROM ventas WHERE month(fecha_venta)=$mes_search AND year(fecha_venta)=$ano_search AND userid=$userid";
          $stmt11 = $pdo->query($sql11);
          $rows11 = $stmt11->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows11 as $row11){
//echo $row11["num_coti"]."<br>" ;
            $neto = 0.0;
            $sqlven = "SELECT * FROM ventas WHERE num_venta = ".$row11['num_venta']."";
//echo $sqlcot."<br>" ;
            $stmtven = $pdo->query($sqlven);
            $rowsven = $stmtven->fetchAll(PDO::FETCH_ASSOC);
            foreach($rowsven as $rowven){
              $codigo_parte = $rowven['codigo_parte'];
              $sqlven1 = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";
              $stmtven1 = $pdo->query($sqlven1);

              if ( $stmtven1->rowCount() > 0 ) {
                $row1p = $stmtven1->fetchAll(PDO::FETCH_ASSOC);
                foreach($row1p as $rowp){
                  $valor = $rowp['valor'];
                  $descuento = $rowven['descuento'];
                  $valordesc = $valor * (1.0 - ($descuento * 0.01));
                }
                $total = $valordesc*$rowven['cantidad'];
                $neto = $total+$neto;
              }
              $iva = $neto * 0.19;
              $total_ac = $neto + $iva;
//echo $row11["num_coti"]."--->".$total_ac."<br>" ;
            }
            $monto_total += $total_ac;
          }
          $users[$user_index][$i][13] = ($monto_total != 0) ? number_format($monto_total, 0, ',', '.') : '-';     //  cantidad de cotizaciones

/***  Sum amount of Ventas Particulares   **************************************************************************/

          $sql4 = "SELECT count(DISTINCT num_venta) AS ventas_user FROM ventas_part WHERE userid=$userid AND month(fecha_venta)=$mes_search AND year(fecha_venta)=$ano_search";
          $stmt4 = $pdo->query($sql4);
          $rows4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows4 as $row4){
            $users[$user_index][$i][5] = ($row4["ventas_user"] != 0) ? $row4["ventas_user"] : '-';     //  cantidad de ventas
          }
/***  Sum amount of Ventas Particulares    **************************************************************************/

          $monto_total = 0.0;
          $sql11 = "SELECT DISTINCT num_venta AS num_venta FROM ventas_part WHERE month(fecha_venta)=$mes_search AND year(fecha_venta)=$ano_search AND userid=$userid";
          $stmt11 = $pdo->query($sql11);
          $rows11 = $stmt11->fetchAll(PDO::FETCH_ASSOC);
          foreach($rows11 as $row11){
//echo $row11["num_coti"]."<br>" ;
            $neto = 0.0;
            $sqlven = "SELECT * FROM ventas_part WHERE num_venta = ".$row11['num_venta']."";
//echo $sqlcot."<br>" ;
            $stmtven = $pdo->query($sqlven);
            $rowsven = $stmtven->fetchAll(PDO::FETCH_ASSOC);
            foreach($rowsven as $rowven){
              $codigo_parte = $rowven['codigo_parte'];
              $sqlven1 = "SELECT * FROM partes WHERE codigo = '$codigo_parte'";
              $stmtven1 = $pdo->query($sqlven1);

              if ( $stmtven1->rowCount() > 0 ) {
                $row1p = $stmtven1->fetchAll(PDO::FETCH_ASSOC);
                foreach($row1p as $rowp){
                  $valor = $rowp['valor'];
                  $descuento = $rowven['descuento'];
                  $valordesc = $valor * (1.0 - ($descuento * 0.01));
                }
                $total = $valordesc*$rowven['cantidad'];
                $neto = $total+$neto;
              }
              $iva = $neto * 0.19;
              $total_ac = $neto + $iva;
//echo $row11["num_coti"]."--->".$total_ac."<br>" ;
            }
            $monto_total += $total_ac;
          }
          $users[$user_index][$i][14] = ($monto_total != 0) ? number_format($monto_total, 0, ',', '.') : '-';     //  cantidad de cotizaciones

        }
        $max_user = $user_index;
        $user_index += 1;
      }
		}
  }

  $data_users = null;
  for ($user=0; $user<=$max_user; $user++){
    $data_users .='<thead>'.
                  '<tr class="thead-dark">'.
                    '<th colspan="13">'.$users[$user][0][0].'</th>'.
                  '</tr>';
//
    $data_users .='<tr class="table-primary">'.
                    '<th></th>'.
                    '<th>'.$mes_string[$search[0][0]].' / '.$search[0][1].'</th>'.
                    '<th>'.$mes_string[$search[1][0]].' / '.$search[1][1].'</th>'.
                    '<th>'.$mes_string[$search[2][0]].' / '.$search[2][1].'</th>'.
                    '<th>'.$mes_string[$search[3][0]].' / '.$search[3][1].'</th>'.
                    '<th>'.$mes_string[$search[4][0]].' / '.$search[4][1].'</th>'.
                    '<th>'.$mes_string[$search[5][0]].' / '.$search[5][1].'</th>'.
                    '<th>'.$mes_string[$search[6][0]].' / '.$search[6][1].'</th>'.
                    '<th>'.$mes_string[$search[7][0]].' / '.$search[7][1].'</th>'.
                    '<th>'.$mes_string[$search[8][0]].' / '.$search[8][1].'</th>'.
                    '<th>'.$mes_string[$search[9][0]].' / '.$search[9][1].'</th>'.
                    '<th>'.$mes_string[$search[10][0]].' / '.$search[10][1].'</th>'.
                    '<th>'.$mes_string[$search[11][0]].' / '.$search[11][1].'</th>'.
                  '</tr>'.
                  '</thead>';
    $data_users .='<tbody>'.
                  '<tr>'.
                    '<td class="pink1 text-left font-weight-bold">Cotizaciones</td>'.
                    '<td>'.$users[$user][0][2].'</td>'.
                    '<td>'.$users[$user][1][2].'</td>'.
                    '<td>'.$users[$user][2][2].'</td>'.
                    '<td>'.$users[$user][3][2].'</td>'.
                    '<td>'.$users[$user][4][2].'</td>'.
                    '<td>'.$users[$user][5][2].'</td>'.
                    '<td>'.$users[$user][6][2].'</td>'.
                    '<td>'.$users[$user][7][2].'</td>'.
                    '<td>'.$users[$user][8][2].'</td>'.
                    '<td>'.$users[$user][9][2].'</td>'.
                    '<td>'.$users[$user][10][2].'</td>'.
                    '<td>'.$users[$user][11][2].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="pink1 text-left font-weight-bold">Monto</td>'.
                    '<td>'.$users[$user][0][11].'</td>'.  
                    '<td>'.$users[$user][1][11].'</td>'.
                    '<td>'.$users[$user][2][11].'</td>'.
                    '<td>'.$users[$user][3][11].'</td>'.
                    '<td>'.$users[$user][4][11].'</td>'.
                    '<td>'.$users[$user][5][11].'</td>'.
                    '<td>'.$users[$user][6][11].'</td>'.
                    '<td>'.$users[$user][7][11].'</td>'.
                    '<td>'.$users[$user][8][11].'</td>'.
                    '<td>'.$users[$user][9][11].'</td>'.
                    '<td>'.$users[$user][10][11].'</td>'.
                    '<td>'.$users[$user][11][11].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="yellow1 text-left font-weight-bold">Cotizaciones<br>Particulares</td>'.
                    '<td class="align-middle">'.$users[$user][0][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][1][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][2][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][3][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][4][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][5][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][6][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][7][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][8][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][9][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][10][3].'</td>'.
                    '<td class="align-middle">'.$users[$user][11][3].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="yellow1 text-left font-weight-bold">Monto</td>'.
                    '<td class="align-middle">'.$users[$user][0][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][1][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][2][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][3][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][4][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][5][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][6][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][7][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][8][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][9][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][10][12].'</td>'.
                    '<td class="align-middle">'.$users[$user][11][12].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="pink1 text-left font-weight-bold">Ventas</td>'.
                    '<td>'.$users[$user][0][4].'</td>'.
                    '<td>'.$users[$user][1][4].'</td>'.
                    '<td>'.$users[$user][2][4].'</td>'.
                    '<td>'.$users[$user][3][4].'</td>'.
                    '<td>'.$users[$user][4][4].'</td>'.
                    '<td>'.$users[$user][5][4].'</td>'.
                    '<td>'.$users[$user][6][4].'</td>'.
                    '<td>'.$users[$user][7][4].'</td>'.
                    '<td>'.$users[$user][8][4].'</td>'.
                    '<td>'.$users[$user][9][4].'</td>'.
                    '<td>'.$users[$user][10][4].'</td>'.
                    '<td>'.$users[$user][11][4].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="pink1 text-left font-weight-bold">Monto de<br>Ventas</td>'.
                    '<td class="align-middle">'.$users[$user][0][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][1][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][2][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][3][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][4][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][5][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][6][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][7][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][8][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][9][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][10][13].'</td>'.
                    '<td class="align-middle">'.$users[$user][11][13].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="yellow1 text-left font-weight-bold">Ventas<br>Particulares</td>'.
                    '<td class="align-middle">'.$users[$user][0][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][1][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][2][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][3][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][4][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][5][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][6][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][7][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][8][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][9][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][10][5].'</td>'.
                    '<td class="align-middle">'.$users[$user][11][5].'</td>'.
                  '</tr>';
    $data_users .='<tr>'.
                    '<td class="yellow1 text-left font-weight-bold">Monto de<br>Ventas<br>Particulares</td>'.
                    '<td class="align-middle">'.$users[$user][0][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][1][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][2][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][3][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][4][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][5][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][6][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][7][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][8][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][9][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][10][14].'</td>'.
                    '<td class="align-middle">'.$users[$user][11][14].'</td>'.
                  '</tr>'.
                  '<tr></tr>'.
                  '</tbody>';

  } 

// Close statement
	unset($stmt);
// Close connection
	unset($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="Waldo Venn" />
  <title>IMPORTADORA REPO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/fonts/all.css">
  <link href="../css/header.css" rel="stylesheet" />
  <link href="../css/user.css" rel="stylesheet" />
</head>
<body>
<!-- box1 -->
  <div id="box1" class="home">
    <?php require( BASE_DIR."/header.php");  ?>
  </div>
<!-- /box1 --> 
<!-- navbar --> 
  <div id="navigation">
    <?php require( BASE_DIR."/navigation.php" ); ?>
  </div>
<!-- /navbar --> 
	<div class="container-fluid">

    <div class="row mt-3 no-gutters">
      <div class="col-lg-4"></div>
      <div class="col-lg-4"><h3 class="texto">Cotizaciones y Ventas por Usuarios</h3></div>
      <div class="col-lg-4"></div>
    </div>

		<div class="row mt-3">
		  <div class="col-lg-12">
				<table class="table table-bordered table-striped table-sm text-center">
			    <?php echo $data_users; ?>
        </table>
			</div>
		</div>

	</div>  

  <script src="../js/jquery-3.4.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

</body>
</html>