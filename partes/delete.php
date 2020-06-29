<?php
  define('BASE_DIR', '..');
  // Initialize the session
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
  $userid = $_SESSION["id"];
  $rol = $_SESSION["rol"];
  } else {
    header("location: ../user/login.php");
  }
  
  require 'database.php';
  $id = 0;
  $parteBorrar = false;
  $parteBorrarControl = "0";

  if ( !empty($_GET['codigo'])) {
    $codigo = $_REQUEST['codigo'];
    $marca = $_REQUEST['marca'];
    $modelo = $_REQUEST['modelo'];
  }
   
  if ( !empty($_POST)) {
      // keep track post values
      $codigo = $_POST['codigo'];
      $marca = $_POST['marca'];
      $modelo = $_POST['modelo'];

      // delete data
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "DELETE FROM partes WHERE codigo = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($codigo));
      Database::disconnect();
      $parteBorrar = true;
      $codigoControl = 'Codigo de parte '.$codigo.' eliminado exitosamente...';
      $parteBorrarControl = "1";
//        header("Location: ../clientes/read.php");
    }
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
  </head>
  <body>
<!-- box1 -->
    <div id="box1" class="home">
      <div class="container-fluid">
        <div class="row d-lg-inline-flex align-items-center d-sm-block">
          <div class="col-lg-1"></div>
          <div class="col-lg-2 col-3">
            <a href="http://www.repo.cl">
              <img src="../img/repo.png" alt="" width="100%" >
            </a>
          </div>
          <div class="col-lg-1"></div>
          <div class="col-lg-1 col-3">
            <img class="rounded mx-auto d-block" src="../img/toyota.png" alt="" width="140%">
          </div>
          <div class="col-lg-1 col-3">
            <img class="rounded mx-auto d-block" src="../img/mitsubishi.png" alt="" width="120%">
          </div>
          <div class="col-lg-1"></div>
          <div class="col-lg-2 fa_phone">
            <i class="fas fa-phone" ></i>
            <a href="tel:56232503046"><b>+56 232503046</b></a>
          </div>  
          <div class="col-lg-2 mt-3">
            <div class="fa_whatsapp">
              <i class="fab fa-whatsapp" style="margin-bottom: 0.1rem;"></i>
              <a href="https://api.whatsapp.com/send?phone=56949673721"><b style="font-size: 0.8em;"> +56 949673721</b></a>
            </div>
            <div class="fa_envelop">
              <i class="far fa-envelope fa-lg"></i>
              <a href="mailto:respuestos@repo.cl"> <b style="font-size: 1em;"> respuestos@repo.cl</b></a>
            </div>
          </div>
          <div class="col-lg-1"></div>
        </div>

      </div>
    </div>
    
<!-- /box1 --> 

    <nav class="navbar navbar-dark sticky-top navbar-expand-sm bg-dark">
      <a class="navbar-brand" href="#"><img src="./img/repo.png" width="70%" alt="logo"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbar-list">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Repuestos
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="../partes/read.php">Visualizar</a>
              <a class="dropdown-item" href="../partes/create.php">Crear</a>
              <a class="dropdown-item" href="../partes/read_ud.php?accion=update">Modificar</a>
              <a class="dropdown-item" href="../partes/read_ud.php?accion=delete">Eliminar</a>
            </div>
          </li> 
          <li class="nav-item">
            <a class="nav-link" href="#">Cotizacion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Ventas</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Clientes
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="../clientes/read.php">Visualizar</a>
              <a class="dropdown-item" href="../clientes/create.php">Crear</a>
              <a class="dropdown-item" href="../clientes/read_ud.php?accion='update'">Modificar</a>
              <a class="dropdown-item" href="../clientes/read_ud.php?accion='delete'">Eliminar</a>
            </div>
          </li> 
        </ul>
      </div>
    </nav>
<!-- /navbar --> 

    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 mt-3"></div>
        <div class="col-lg-6 mt-3">
          <div class="row mb-3 ">
            <h3>Eliminar Repuesto</h3>
          </div>
          <table class="table table-hover table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Marca</th>
                <th scope="col">Modelo</th>
                <th scope="col">Codigo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#</th>
                <td><?php echo $marca; ?></th>
                <td><?php echo $modelo; ?></th>
                <td><?php echo $codigo; ?></th>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-3 mt-3"></div>
      </div>

      <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $parteBorrarControl; ?>" data-cliente="<?php echo $codigo; ?>" data-action="partes/read">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">ELIMINAR REPUESTO</h4>
<!--              <button type="button" class="close" data-dismiss="modal">&times;</button>  -->
            </div>
            <div class="modal-body"><?php echo  $codigoControl; ?></div>
            <div class="modal-footer <?php echo ($parteBorrar)? 'mostrar':'ocultar'; ?>">
              <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 mt-3"></div>
        <div class="col-lg-4 mt-3">
          <form class="form-horizontal" action="./delete.php?codigo=<?php echo $codigo; ?>&marca=<?php echo $marca; ?>&modelo=<?php echo $modelo; ?>" method="post">
            <input type="hidden" name="codigo" value="<?php echo $codigo;?>"/>
            <input type="hidden" name="marca" value="<?php echo $marca;?>"/>
            <input type="hidden" name="modelo" value="<?php echo $modelo;?>"/>
            <div class="d-flex flex-column mt-3 <?php echo ($parteBorrar)?'ocultar':'mostrar'; ?>">            
              <p class="alert alert-secondary" role="alert">Esta seguro de eliminar a <?php echo $codigo; ?> ?</p>
              <div class="d-flex flex-row justify-content-center mt-3">
                <button type="submit" class="btn btn-danger mr-3">Eliminar</button>
                <a class="btn btn-outline-danger" href="../partes/read_ud.php?accion=delete" role="button">Atras</a>
              </div>
            </div>

          </form>
        </div>
        <div class="col-lg-4 mt-3"></div>
      </div>

    </div>  

<!-- footer -->
    <footer>
      <div class="container">
        <p class="text-right"></p>
      </div>
    </footer>
<!-- /footer --> 


    <script src="../js/jquery-3.4.0.min.js"</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/main.js"></script>

  </body>
</html>
