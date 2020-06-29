<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
	header("location: ../index.php");
	exit;
} else {
  $username = "";
}

// config vars
  define('BASE_DIR', '..');
  require 'database.php';

// Define variables and initialize with empty values
$username = $password = "";
$username_msgerr = $password_msgerr = "";
$username_err = $password_err = false;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Check if username is empty
	if(empty(trim($_POST["username"]))){
		$username_msgerr = "Introduzca un nombre de usuario.";
		$username_err = true;
	} else{
		$username = trim($_POST["username"]);
	}

// Check if password is empty
	if(empty(trim($_POST["password"]))){
		$password_msgerr = "Introduzca su clave de acceso.";
		$password_err = true;
	} else{
		$password = trim($_POST["password"]);
	}

// Validate credentials
	if(empty($username_err) && empty($password_err)){
// Prepare a select statement
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT id, username, password, rol FROM usuarios WHERE username = :username";

		if($stmt = $pdo->prepare($sql)){
// Bind variables to the prepared statement as parameters
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

// Set parameters
			$param_username = trim($_POST["username"]);

// Attempt to execute the prepared statement
			if($stmt->execute()){
// Check if username exists, if yes then verify password
				if($stmt->rowCount() == 1){
					if($row = $stmt->fetch()){
            $rol = $row["rol"];
						$id = $row["id"];
						$username = $row["username"];
						$hashed_password = $row["password"];
						if(password_verify($password, $hashed_password)){
// Password is correct, so start a new session
							session_start();

/// Store data in session variables
							$_SESSION["loggedin"] = true;
              $_SESSION["rol"] = $rol;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;                            

// Redirect user to welcome page
							header("location: ../index.php");
						} else{
// Display an error message if password is not valid
							$password_err = "Clave de acceso no válida.";
						}
					}
				} else{
// Display an error message if username doesn't exist
					$username_err = "Usuario no existe.";
				}
			} else{
				echo "Algo salió mal. Intente mas tarde.";
			}
		}

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
  <link rel="stylesheet" href="../css/font/all.css">
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
					<h2>Ingreso</h2>
					<p>Introduzca los datos solicitados para ingresar al sistema</p>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
						<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
							<label>Usuario</label>
							<input type="text" name="username" class="form-control <?php echo ($username_err)?'is-invalid':''; ?>"" value="<?php echo $username; ?>">
							<span class="text-danger"><?php echo $username_msgerr; ?></span>
						</div>    
						<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
							<label>Clave de acceso</label>
							<input type="password" name="password" class="form-control <?php echo ($password_err)?'is-invalid':''; ?>">
							<span class="text-danger"><?php echo $password_msgerr; ?></span>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-dark" value="Ingresar">
						</div>
						<p>No está registrado? <a href="registro.php">Registrese aquí</a>.</p>
					</form>
				</div>
			</div>
		</div>
	</div>  

  <script src="../js/jquery-3.4.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../js/main.js"></script>

</body>
</html>