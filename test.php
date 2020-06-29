<?php
  define('BASE_DIR', '.');
// Initialize the session
  session_start();

  require 'database.php';
  date_default_timezone_set('America/Santiago');
// Calculate number of CONSECUTIVO
  $fecha_now = new DateTime();
  $fecha_5atras = $fecha_now->modify("-5 day");
  $x = $fecha_5atras->format("Ymd");
  $x = $x + "0";
  $fecha_5 = (int) $x;
//  echo $fecha_5;

///////Configuración email/////
$mail_destinatario = 'aldoven@gmail.com';
///////Fin configuración//

///// Funciones necesarias////
function form_mail($sPara, $sAsunto, $sTexto, $sDe){
	$bHayFicheros = 0;
	$sCabeceraTexto = "";
	$sAdjuntos = "";
	if ($sDe)$sCabeceras = "From:".$sDe."\n";
	else $sCabeceras = "";
	$sCabeceras .= "MIME-version: 1.0\n";
	foreach ($_POST as $sNombre => $sValor) $sTexto = $sTexto."\n".$sNombre." = ".$sValor;
	foreach ($_FILES as $vAdjunto){
		if ($bHayFicheros == 0){
			$bHayFicheros = 1;
			$sCabeceras .= "Content-type: multipart/mixed;";
			$sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";
			$sCabeceraTexto = "----_Separador-de-mensajes_--\n";
			$sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n";
			$sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";
			$sTexto = $sCabeceraTexto.$sTexto;
		}
		if ($vAdjunto["size"] > 0){
			$sAdjuntos .= "\n\n----_Separador-de-mensajes_--\n";
		 	$sAdjuntos .= "Content-type: ".$vAdjunto["type"].";name=\"".$vAdjunto["name"]."\"\n";;
			$sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
			$sAdjuntos .= "Content-disposition: attachment;filename=\"".$vAdjunto["name"]."\"\n\n";
			$oFichero = fopen($vAdjunto["tmp_name"], 'r');
			$sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"]));
			$sAdjuntos .= chunk_split(base64_encode($sContenido));
			fclose($oFichero);
		}
	}
	if ($bHayFicheros)	$sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

	return(mail($sPara, $sAsunto, $sTexto, $sCabeceras));
}

if (isset ($_POST['enviar'])) {
	if (form_mail($mail_destinatario, $_POST['asunto'],	"Los datos introducidos son:\n\n", $_POST['email']))
		echo 'Su mensaje a sido enviado correctamente.';
	else 
		echo ' Error al enviar el formulario. '; 
}

echo '
<form id="formulario" action="?" enctype="multipart/form-data" method="post">
	<label for="nombre">Nombre y apellidos : </label> <input maxlength="80" name="nombre" size="50" type="text" /><br>
	<label for="email">Email : </label> <input maxlength="60" name="email" size="50" type="text" /><br>
	<label for="asunto">Asunto : </label><input maxlength="60" name="asunto" size="50" type="text" /><br>
	<label for="mensaje">Mensaje : </label> <textarea cols="31" rows="5" name="mensaje"></textarea><br>
	<label for="archivo">Adjuntar archivo: <input id="archivo" name="archivo" type="file" /><br>
	</label><label for="enviar"> <input name="enviar" type="submit" value="Enviar consulta" /></label><br>
</form>

';

  Database::disconnect();

?>
<!DOCTYPE html>
  <html lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Waldo Venn" />
    <title>IMPORTADORA REPO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/fonts/all.css">
    <link href="./css/header.css" rel="stylesheet" />
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
  <h3>Toast Example</h3>

  <div class="toast" data-autohide="false">
    <div class="toast-header">
      <strong class="mr-auto text-primary">Toast Header</strong>
      <small class="text-muted">5 mins ago</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body">
      Some text inside the toast body
    </div>
  </div>

</div>

<!-- footer -->
    <footer>
      <hr />
      <div class="container">
        <p id="footer" class="text-right"></p>
      </div>
    </footer>
<!-- /footer --> 

    <script src="./js/jquery-3.4.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/main.js"></script>
<script>

</script>
  </body>
</html>