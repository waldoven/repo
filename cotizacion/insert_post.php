<?php
  require 'database.php';

  if($_POST["rut_cliente"]) {
    $userid = $_POST["userid"];
    $rut_cliente = $_POST["rut_cliente"];
    $num_cotizacion = $_POST["num_cotizacion"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $fecha_cotizacion = $_POST["fecha_cotizacion"];
    $movil = $_POST["movil"];
    $solcompra = $_POST["solcompra"];
    $desc = $_POST["desc"];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO cotizaciones (rut_cliente,num_cotizacion,fecha_cotizacion,codigo_parte,cantidad,descuento,movil,solcompra,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut_cliente,$num_cotizacion,$fecha_cotizacion,$cod_parte,$cantidad,$desc,$movil,$solcompra,$userid));
    Database::disconnect();
    header("Location: ../cotizacion/create.php");
  } else {
    header("Location: ../index.php");
  }
?>

<html lang="en" >
  <body>

  </body>
</html>

