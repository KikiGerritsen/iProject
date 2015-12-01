<?php
	session_start();
	include 'resources/scripts/dbConnection.php';
	if($conn) {
			header ('location: index.php');
	}

?>
<!DOCTYPE html>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
    <meta charset="utf-8">
    <title>Eenmaal Andermaal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
	<div id="container">
		<?php
			include"resources/scripts/header.php";
			include"resources/scripts/menu.php";
		?>
		<div id="pagina">
			<?php
				include"resources/scripts/zoeken.php";
			?>
			<div id="content">
			
				<div class="homepageBlokLogin">
					<h1>Registratie voltooid</h1>
					<p>Uw registratie is voltooid. U kunt hier inloggen.</p>
				</div>
				&nbsp;
				<div id="pageextender">
				&nbsp;
				</div>
				
			</div>
			&nbsp;
		</div>
	</div>
  </body>
</html>