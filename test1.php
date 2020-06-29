<?php 
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
// Introducimos HTML de prueba
$html = '<h1>Hola mundo!</h1>';
// Instanciamos un objeto de la clase DOMPDF.
$pdf = new DOMPDF();
// Definimos el tamaño y orientación del papel que queremos.
$pdf->set_paper("A4", "portrait");
$pdf->setPaper('A4', 'Landscape');
// Cargamos el contenido HTML.
$pdf->load_html(utf8_decode($html));
// Renderizamos el documento PDF.
$pdf->render();
// Enviamos el fichero PDF al navegador.
$filename = "newpdffile";
//$pdf->stream($filename+'.pdf');
//$pdf->stream($filename,array("Attachment"=>0));

require_once "config.php";
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $username = $_SESSION["username"];
  $userid = $_SESSION["id"];
  $rol = $_SESSION["rol"];
} else {
  header("location: ./user/login.php");
}

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

  <div id="divdesc" class="col-lg-1 d-flex flex-column px-2 mt-2">
    <label for="descuento" class="control-label">Descuento</label>
    <div class="input-group input-group-sm">
      <input id="cuento" class="form-control form-control-sm text-right w-25" type="text" name="descuent" value="" min="0" max="60" maxlength="2" />
    </div>
  </div>

  <input id="calcular" type="button" class="btn btn-dark create" value="Calcular">

  <div class="d-flex flex-column col-lg-2">
    <h5 class="texto ml-4 mr-3 pt-2">Tipo de Cliente</h5>
    <div class="btn-group btn-group-toggle col-lg-1 ml-1" data-toggle="buttons">
      <label for="particular" class="btn btn-outline-dark btn-sm">
        <input id="particular" type="radio" name="tipo_cliente" autocomplete="off"> Particular
      </label>
      <label for="empresa" class="btn btn-outline-dark btn-sm active">
        <input id="empresa" type="radio" name="tipo_cliente" autocomplete="off"> Empresa
      </label>
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

    <script src="./js/jquery-3.4.0.min.js"</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"</script>
    <script src="./js/main.js"></script>
<script>
$(document).ready(function(){
  $('.toast').toast('show');
    var desc_val;
  $("#calcular").on("click", function() {

    desc_val = $("#cuento").val();
    console.dir("valord "+ desc_val);

  }); 

});
</script>
  </body>
</html>