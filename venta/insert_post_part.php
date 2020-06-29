<?php
  require 'database.php';

  if($_POST["userid"]) {
    $userid = $_POST["userid"];
    $rut = (!isset($_POST["rut"])) ? 'NO INDICA' : $_POST["rut"];
    $nom = (!isset($_POST["nombre"])) ? 'NO INDICA' : $_POST["nombre"];
    $correo = (!isset($_POST["correo"])) ? 'NO INDICA' : $_POST["correo"];
    $num_venta = $_POST["num_venta"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $fecha_venta = $_POST["fecha_venta"];
    $desc = 0;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO ventas_part (rut_cliente,nombre,correo,num_venta,fecha_venta,codigo_parte,cantidad,descuento,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $q = $pdo->prepare($sql);
    $q->execute(array($rut,$nom,$correo,$num_venta,$fecha_venta,$cod_parte,$cantidad,$desc,$userid));
    Database::disconnect();
//    header("Location: ../venta/create.php");
  } else {
//    header("Location: ../index.php");
  }
?>

<html lang="en" >
  <body>
    <?php echo $sql; ?>
  </body>
</html>
