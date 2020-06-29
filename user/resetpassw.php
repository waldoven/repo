<?php
define('BASE_DIR', '..');
require 'database.php';

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
 
// Include config file

 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Introduzca su clave de acceso.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Clave de acceso debe tener al menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirme su clave de acceso.";  
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Clave de acceso no concuerda.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
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
  <link rel="stylesheet" href="../css/fonts/all.css">
  <link href="../css/header.css" rel="stylesheet" />
	<style type="text/css">
	    .wrapper{ width: 450px; padding: 20px; }
	</style>
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
      <div class="col-lg-8">

				<div class="wrapper">
				    <h2>Cambiar Clave de Acceso</h2>
				    <p>Introduzca los datos solicitados para crear un usuario.</p>
				    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
				        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
				            <label>Clave de acceso</label>
				            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
				            <span class="help-block"><?php echo $new_password_err; ?></span>
				        </div>
				        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
				            <label>Confirme Clave</label>
				            <input type="password" name="confirm_password" class="form-control">
				            <span class="help-block"><?php echo $confirm_password_err; ?></span>
				        </div>
				        <div class="form-group">
				            <input type="submit" class="btn btn-dark" value="Cambiar Clave">
				            <a class="btn btn-link" href="welcome.php">Salir</a>
				        </div>
				    </form>
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
