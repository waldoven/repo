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
  header("location: ./login.php");
}

require 'database.php';
// Define variables and initialize with empty values
$username = $password = $rol = $confirm_password = $options = "";
$username_msgerr = $password_msgerr = $confirm_password_msgerr = "";
$username_err = $password_err = $confirm_password_err = false;
$options =  $options."<option>"."Vendedor"."</option>"."<option>"."Administrador"."</option>";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Validate username
	if (empty(trim($_POST["username"]))) {
		$username_err = true;
		$username_msgerr = "Introduzca un nombre de usuario.";
	} else{
// Prepare a select statement
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT id FROM usuarios WHERE username = :username";

		if ($stmt = $pdo->prepare($sql)) {
// Bind variables to the prepared statement as parameters
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

// Set parameters
			$param_username = trim($_POST["username"]);
			$param_rol = trim($_POST["rol"]);
// Attempt to execute the prepared statement
			if ($stmt->execute()) {
				if ($stmt->rowCount() == 1){
					$username_msgerr = "Usuario existe.";
				} else{
					$username = trim($_POST["username"]);
					$rol = trim($_POST["rol"]);
				}
			} else{
				echo "Algo salió mal. Intente mas tarde.";
			}
		}

// Close statement
		unset($stmt);
	}

// Validate password
	if (empty(trim($_POST["password"]))) {
		$password_err = true;
		$password_msgerr = "Introduzca una clave de acceso.";     
	} elseif (strlen(trim($_POST["password"])) < 6) {
		$password_err = true;
		$password_msgerr = "Clave de acceso debe tener al menos 6 caracteres.";
	} else {
		$password = trim($_POST["password"]);
	}

// Validate confirm password
	if (empty(trim($_POST["confirm_password"]))) {
		$confirm_password_err = true;
		$confirm_password_msgerr = "Confirme su clave de acceso.";     
	} else {
		$confirm_password = trim($_POST["confirm_password"]);
		if (empty($password_msgerr) && ($password != $confirm_password)) {
			$confirm_password_err = true;
			$confirm_password_msgerr = "Clave de acceso no concuerda.";
		}
	}

// Check input errors before inserting in database
	if (empty($username_msgerr) && empty($password_msgerr) && empty($confirm_password_msgerr)) {

// Prepare an insert statement
		$sql = "INSERT INTO usuarios (username, password, rol) VALUES (:username, :password, :rol)";

		if ($stmt = $pdo->prepare($sql)) {
// Bind variables to the prepared statement as parameters
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
			$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
			$stmt->bindParam(":rol", $param_rol, PDO::PARAM_STR);
// Set parameters
			$param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
      $param_rol = $rol;      
// Attempt to execute the prepared statement
      if($stmt->execute()){
        $user_msg = "Usuario creado exitosamente ...";
// Redirect to login page
        header("location: login.php");
      } else{
      	echo "Algo salió mal. Intente mas tarde.";
      }
    }
    $usermsgControl = 1;
// Close statement
    unset($stmt);
  }
// Close connection
  unset($pdo);
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
<!-- box1 -->
  <div id="box1" class="home">
    <?php require( BASE_DIR."/header.php");  ?>
  </div>
<!-- /box1 --> 
<!-- navbar --> 
  <div id="navigation">
    <?php require( BASE_DIR."/navigation.php" ); ?>
  </div>
<!-- /navbar --> 
	<div class="container-fluid">

		<div class="row mt-3">
      <div class="col-lg-4"></div>
		  <div class="col-lg-4">

				<div class="usuario">
					<h2>Crear Usuario</h2>
					<p>Introduzca los datos solicitados para crear un usuario.</p>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
						<div class="form-group <?php echo (!empty($username_msgerr)) ? 'has-error' : ''; ?>">
							<label>Usuario</label>
							<input class="form-control <?php echo ($username_err)?'is-invalid':''; ?>" type="text" name="username" value="<?php echo $username; ?>" onkeyup="this.value = this.value.toUpperCase();">
							<span class="text-danger"><?php echo $username_msgerr; ?></span>
						</div>
						<div class="form-group <?php echo (!empty($username_msgerr)) ? 'has-error' : ''; ?>">
							<label>Rol</label>
              <select class="form-control" id="rolSelect" name="rol">
                <?php echo $options;?>
              </select>
						</div>  
						<div class="form-group <?php echo (!empty($password_msgerr)) ? 'has-error' : ''; ?>">
							<label>Clave de acceso</label>
							<input class="form-control <?php echo ($password_err)?'is-invalid':''; ?>" type="password" name="password" value="<?php echo $password; ?>">
							<span class="text-danger"><?php echo $password_msgerr; ?></span>
						</div>
						<div class="form-group <?php echo (!empty($confirm_password_msgerr)) ? 'has-error' : ''; ?>">
							<label>Confirme Clave</label>
							<input class="form-control <?php echo ($confirm_password_err)?'is-invalid':''; ?>" type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
							<span class="text-danger"><?php echo $confirm_password_msgerr; ?></span>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-dark" value="Ingresar">
							<input type="reset" class="btn btn-default" value="Borrar">
						</div>
						<p>Ya está registrado?   <a href="login.php">Ingrese aquí</a>.</p>
					</form>
				</div>

			</div>
		</div>

    <div id="MyModal" class="modal fade" role="dialog" data-control="<?php echo $usermsgControl; ?>" data-cliente="" data-action="partes/read">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">CREAR USUARIO</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div id="MyModalText" class="modal-body"><?php echo $user_msg; ?></div>
          <div class="modal-footer <?php echo ($parteModificadoControl)? 'mostrar':'ocultar'; ?>">
            <input type="button" id="btnClosePopup" value="Cerrar" class="btn btn-danger" />
          </div>
        </div>
      </div>
    </div>

	</div>

  <script src="../js/jquery-3.4.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

</body>
</html>