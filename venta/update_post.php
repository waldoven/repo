<?php
  require 'database.php';

  if($_POST["rut_cliente"]) {
    $index = $_POST["index"];
    $userid = $_POST["userid"];
    $rut_cliente = $_POST["rut_cliente"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $movil = $_POST["movil"];
    $solcompra = $_POST["solcompra"];
    $desc = $_POST["desc"];
    $num_venta = $_POST["num_venta"];
    $fecha_venta = $_POST["fecha_venta"];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($index == 0) {
      $sql = "DELETE FROM ventas WHERE num_venta = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($num_venta));
    }

    $sql = "INSERT INTO ventas (rut_cliente,num_venta,fecha_venta,codigo_parte,cantidad,descuento,movil,solcompra,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut_cliente,$num_venta,$fecha_venta,$cod_parte,$cantidad,$desc,$movil,$solcompra,$userid));
    Database::disconnect();
    header("Location: ../venta/read_empresa.php");
  } else {
    header("Location: ../index.php");
  }

?>

<html lang="en" >
  <body>

  </body>
</html>

