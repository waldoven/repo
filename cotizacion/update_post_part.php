<?php
  require 'database.php';

  if($_POST["num_cotizacion"]) {
    $index = $_POST["index"];
    $userid = $_POST["userid"];
    $rut = $_POST["rut"];
    $nom = $_POST["nombre"];
    $correo = $_POST["correo"];
    $num_cotizacion = $_POST["num_cotizacion"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $fecha_cotizacion = $_POST["fecha_cotizacion"];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($index == 0) {
      $sql = "DELETE FROM cotizaciones_part WHERE num_cotizacion = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($num_cotizacion));
    }

    $sql = "INSERT INTO cotizaciones_part (rut_cliente,nombre,correo,num_cotizacion,fecha_cotizacion,codigo_parte,cantidad,userid) values(?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut,$nom,$correo,$num_cotizacion,$fecha_cotizacion,$cod_parte,$cantidad,$userid));
    Database::disconnect();
    header("Location: ../cotizacion/read_particular.php");
  } else {
    header("Location: ../index.php");
  }

?>

<html lang="en" >
  <body>

  </body>
</html>

