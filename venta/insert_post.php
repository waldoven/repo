<?php
  require 'database.php';

  if($_POST["rut_cliente"]) {
    $userid = $_POST["userid"];
    $rut_cliente = $_POST["rut_cliente"];
    $num_venta = $_POST["num_venta"];
    $cod_parte = $_POST["cod_parte"];
    $cantidad = $_POST["cantidad"];
    $fecha_venta = $_POST["fecha_venta"];
    $movil = $_POST["movil"];
    $solcompra = $_POST["solcompra"];
    $desc = $_POST["desc"];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO ventas (rut_cliente,num_venta,fecha_venta,codigo_parte,cantidad,descuento,movil,solcompra,userid) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    echo $sql;
    echo "<script>console.dir(<?php echo $sql; ?>);</script>";
    $q = $pdo->prepare($sql);
    $q->execute(array($rut_cliente,$num_venta,$fecha_venta,$cod_parte,$cantidad,$desc,$movil,$solcompra,$userid));
    Database::disconnect();
    header("Location: ../venta/create.php");
  } else {
    header("Location: ../index.php");
  }
?>

<html lang="en" >
  <body>

  </body>
</html>

