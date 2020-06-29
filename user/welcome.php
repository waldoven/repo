<?php
define('BASE_DIR', '..');
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMPORTADORA REPO</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenido a IMPORTADORA REPO.</h1>
    </div>
    <p>
        <a href="resetpassw.php" class="btn btn-warning">Cambie su Clave de Acceso</a>
        <a href="logout.php" class="btn btn-danger">Salir de su cuenta</a>
    </p>
</body>
</html>