<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
  <div class="container">
    <div class="row">
      <h3>SISTEMA PHP CRUD</h3>
    </div>
      <div class="row">
        <p>
            <a href="create.php" class="btn btn-success">Create</a>
        </p>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Nombre y Apellido</th>
              <th>Correo Electronico</th>
              <th>Telefono</th>
              <th> Accion </th>
            </tr>
          </thead>
          <tbody>
          <?php
             include 'database.php';
             $pdo = Database::connect();
             $sql = 'SELECT * FROM customers ORDER BY name ASC';
             foreach ($pdo->query($sql) as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['name'] . '</td>';
                    echo '<td>'. $row['email'] . '</td>';
                    echo '<td>'. $row['mobile'] . '</td>';
                    echo '<td width=300>';
                    echo '<a class="btn" href="read.php?id='.$row['id'].'">Leer</a>';
                    echo ' ';
                    echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Actualizar</a>';
                    echo ' ';
                    echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Borrar</a>';
                    echo '</tr>';
               }
            $pdo =  Database::disconnect();
          ?>
          </tbody>
      </table>
    </div>
  </div> <!-- /container -->
  </body>
</html>