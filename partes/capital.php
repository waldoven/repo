<?php

  require 'database.php';

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM tiporepuesto";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
  foreach($rows as $row) {
    $tipo_rep  = strtoupper($row['tipo_rep']);
    $cod_tipo_rep  = strtoupper($row['cod_tipo_rep']);
    $sql1 = "UPDATE tiporepuesto 
              SET tipo_rep = '$tipo_rep'
              WHERE cod_tipo_rep = '$cod_tipo_rep'";

    $filas = $pdo->exec($sql1);
  }

  Database::disconnect();



?>
