<?php
  require 'database.php';
  date_default_timezone_set('America/Santiago');
  // search for CLIENTES data
  $clientes = "";
  $rutclientes = "";
  $num = 1;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM clientes ORDER BY nom_cliente";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
//    $clientes = $clientes."<span>".$row['nom_cliente']."<p style='display:none;'>/".$row['contac_cliente']."/".$row['rut_cliente']."</p></span><br>";
    $clientes = $clientes."<tr><td width='80px'>".$row['nom_cliente']."</td><td width='50px'>".$row['rut_cliente']."</td><td width='80px'>".$row['contac_cliente']."</td></tr>";
  }
  Database::disconnect();
// End of search for CLIENTES data
?>
<!DOCTYPE html>
  <html lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Waldo Venn" />
    <title>IMPORTADORA REPO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font/all.css">
    <link href="../css/header.css" rel="stylesheet" />

		<script type="text/javascript">


		</script>

  </head>
  <body>

  <div class="hola" id="FormClientes">
    <form action="#" class="clientes-container">
      <div id="clientes" class="right bg-light">
        <input id="clientesSearch" type='text' placeholder='Buscar clientes...''>
        <table id="listaClientes" class="table table-hover table-sm">
          <tr class="table-secondary">
            <th scope="col">Cliente</th>
            <th scope="col">RUT</th>
            <th scope="col">Contacto</th>
          </tr>
          <?php echo $clientes; ?>
        </table>
      </div>
      <button type="button" class="btn cancel" onclick="closeClientes()">Cerrar</button>
    </form>
  </div>
<!-- footer -->
    <footer>
      <hr />
      <div class="container">
        <p class="text-right">hola</p>
      </div>
    </footer>
<!-- /footer --> 
  <script src="../js/jquery-3.4.0.min.js"</script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../js/main.js"></script>
  </body>
</html>

