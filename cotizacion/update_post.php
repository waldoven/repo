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
    $num_cotizacion = $_POST["num_cotizacion"];
    $fecha_cotizacion = $_POST["fecha_cotizacion"];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($index == 0) {
      $sql = "DELETE FROM cotizaciones WHERE num_cotizacion = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($num_cotizacion));
    }

    $sql = "INSERT INTO cotizaciones (rut_cliente,num_cotizacion,fecha_cotizacion,movil,solcompra,codigo_parte,cantidad,descuento,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut_cliente,$num_cotizacion,$fecha_cotizacion,$movil,$solcompra,$cod_parte,$cantidad,$desc,$userid));
    Database::disconnect();
    header("Location: ../cotizacion/read_empresa.php");
  } else {
    header("Location: ../index.php");
  }

?>

<html lang="en" >
  <body>
  </body>
</html>

