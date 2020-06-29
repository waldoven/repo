<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
  $userid = $_SESSION["id"];
  $rol = $_SESSION["rol"];
} else {
  header("location: ./login.php");
}

// config vars
define('BASE_DIR','..');
require 'database.php';
$data_usuarios = "";
$num_row = 0;
// Prepare a select statement
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT id, username, rol, created FROM usuarios WHERE rol != 'Master' ";

if($stmt = $pdo->query($sql)){
//  execute the statement
	if ( $stmt->rowCount() > 0 ) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      ++$num_row;
			$data_usuarios .= '<tr>
  		                    <td>'.$row["id"].'</td>
  		                    <td>'.$row["username"].'</td>
  		                    <td>'.$row["rol"].'</td>
  		                    <td>'.$row["created"].'</td>
                          <td class="text-center">
                            <button name="userid" value="'.$row["id"].'" type="submit" class="btn btn-sm btn-outline-dark">
                              <i id="del'.$num_row.'" class="fas fa-trash-alt"></i>
                            </button></td>
  											</tr>';
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["userid"]))) {
    $usernameid_err = true;
    $userid_msgerr = "Usuario no seleccionado.";
  } else{
// Prepare a select statement
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM usuarios WHERE id = :userid";

    if ($stmt = $pdo->prepare($sql)) {
// Bind variables to the prepared statement as parameters
      $stmt->bindParam(":userid", $param_userid, PDO::PARAM_STR);

// Set parameters
      $param_userid= trim($_POST["userid"]);
// Attempt to execute the prepared statement
      if ($stmt->execute()) {
        if ($stmt->rowCount() == 1){
          $userid_msgerr = "Usuario eliminado exitosamente...";
        } else{
          $userid_msgerr = "Usuario no puede ser eliminado...";
        }
      } else{
        echo "Algo salió mal. Intente mas tarde.";
      }
    }
    $usermsgControl = 1;
    // Close statement
    unset($stmt);
    // Close connection
    unset($pdo);
  }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="Waldo Venn" />
  <title>IMPORTADORA REPO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/fonts/all.css">
  <link href="../css/header.css" rel="stylesheet" />
</head>
<body>
  <?php require BASE_DIR."/header.php";  ?>
  <?php require BASE_DIR."/navigation.php"; ?>
	<div class="container-fluid">
    <div class="row mt-3 no-gutters">
      <div class="col-lg-3"></div>
      <div class="col-lg-4"><h3 class="texto">Usuarios Registrados</h3></div>
    </div>
		<div class="row mt-3">
      <div class="col-lg-3"></div>
		  <div class="col-lg-6">
        <form class="form-horizontal needs-validation" novalidate action="delete.php" method="post">
  				<table id="eliminarUser" class="table table-hover table-sm">
  				  <thead>
  						<tr>
  						  <th scope="col">Id</th>
  						  <th scope="col">Usuario</th>
  						  <th scope="col">Rol</th>
  						  <th scope="col">Fecha Creación</th>
                <th scope="col" class='text-center'>Eliminar</th>
  						</tr>
  				  </thead>
  				  <tbody>
  				  <?php echo $data_usuarios; ?>
            </tbody>
          </table>
        </form>
			</div>
			<div class="col-lg-3"></div>
		</div>

    <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $usermsgControl; ?>" data-cliente="" data-action="partes/read">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">ELIMINAR USUARIO</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div id="MyModalText" class="modal-body"><?php echo $userid_msgerr; ?></div>
          <div class="modal-footer <?php echo ($parteModificadoControl)? 'mostrar':'ocultar'; ?>">
            <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
          </div>
        </div>
      </div>
    </div>

	</div> 

  <script src="../js/jquery-3.4.0.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

</body>
</html>