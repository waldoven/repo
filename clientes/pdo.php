<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>My Example</title>       
	<style>         
	
		.container { width: 900px;margin: 0 auto; }
		.container ul li { border: 1px solid #000;border-radius: 5px;list-style: none outside none;margin-bottom: 10px;margin-right: 30px;padding: 5px; }
		.container ul li:hover { background-color: #F2FFF2;cursor: pointer; }
		div.left { border:1px solid #000;border-radius: 5px;width: 350px;margin-right:15px;float:left;min-height:250px; }
		div.right { border:1px solid #000;border-radius: 5px;width: 350px;margin-right:15px;float:left;min-height:250px; }
	
	body {font-family: Arial, Helvetica, sans-serif;}
	* {box-sizing: border-box;}
	/* Button used to open the contact form - fixed at the bottom of the page */
	.open-button {
	  background-color: #555;
	  color: white;
	  padding: 16px 20px;
	  border: none;
	  cursor: pointer;
	  opacity: 0.8;
/*	  position: fixed;
	  bottom: 23px;
	  right: 28px;
*/
	  width: 280px;
	}
	/* The popup form - hidden by default */
	.form-popup {
	  display: none;
	  position: fixed;
	  bottom: 0;
	  right: 15px;
	  border: 3px solid #f1f1f1;
	  z-index: 9;
	}
	/* Add styles to the form container */
	.form-container {
	  max-width: 300px;
	  padding: 10px;
	  background-color: white;
	}
	/* Full-width input fields */
	.form-container input[type=text], .form-container input[type=password] {
	  width: 100%;
	  padding: 15px;
	  margin: 5px 0 22px 0;
	  border: none;
	  background: #f1f1f1;
	}
	/* When the inputs get focus, do something */
	.form-container input[type=text]:focus, .form-container input[type=password]:focus {
	  background-color: #ddd;
	  outline: none;
	}
	/* Set a style for the submit/login button */
	.form-container .btn {
	  background-color: #4CAF50;
	  color: white;
	  padding: 16px 20px;
	  border: none;
	  cursor: pointer;
	  width: 100%;
	  margin-bottom:10px;
	  opacity: 0.8;
	}
	/* Add a red background color to the cancel button */
	.form-container .cancel {
	  background-color: red;
	}
	/* Add some hover effects to buttons */
	.form-container .btn:hover, .open-button:hover {
	  opacity: 1;
	}
	</style>
</head>
<body>
	<div class="container">
<button class="open-button" onclick="openForm()">Open Form</button>	
		<div class="left">
			<ul id="x"></ul>
		</div>
		<div style="clear:both;"></div>



<div class="form-popup" id="myForm">
  <form action="/action_page.php" class="form-container">
		<div class="right">
			<ul>
				<li data-order="1" >Item 1</li>
				<li data-order="2" >Item 2</li>
				<li data-order="3" >Item 3</li>
				<li data-order="4" >Item 4</li>
				<li data-order="5" >Item 5</li>
				<li data-order="6" >Item 6</li>
				<li data-order="7" >Item 7</li>
				<li data-order="8" >Item 8</li>
				<li data-order="9" >Item 9</li>
				<li data-order="10" >Item 10</li>
			</ul>
		</div>

    <button type="submit" class="btn">Login</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>


	</div>

  <script src="../js/jquery-3.4.0.min.js"</script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	<script type="text/javascript">
		$(document).ready(function() { 
			$("div.right").on("click", "", function() {
				var a = $(this).clone().appendTo("#x"); 
			});         
		});
	</script>

</body>
</html>