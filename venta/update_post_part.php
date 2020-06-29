<?php
  require 'database.php';

  if($_POST["num_venta"]) {
    $index = $_POST["index"];
    $userid = $_POST["userid"];
    $rut = $_POST["rut"];
    $nom = $_POST["nombre"];
    $correo = $_POST["correo"];
    $num_venta = $_POST["num_venta"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $fecha_venta = $_POST["fecha_venta"];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($index == 0) {
      $sql = "DELETE FROM ventas_part WHERE num_venta = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($num_venta));
    }

    $sql = "INSERT INTO ventas_part (rut_cliente,nombre,correo,num_venta,fecha_venta,codigo_parte,cantidad,userid) values(?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut,$nom,$correo,$num_venta,$fecha_venta,$cod_parte,$cantidad,$userid));
    Database::disconnect();
    header("Location: ../venta/read_particular.php");
  } else {
    header("Location: ../index.php");
  }

?>

<html lang="en" >
  <body>

  </body>
</html>

