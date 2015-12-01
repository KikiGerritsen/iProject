<!DOCTYPE html>
<?php session_start(); ?>
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
				include"resources/scripts/dbConnection.php";
				include"resources/scripts/class.timer.php";
			?>
			<div id="pagina">
				<div id="content">
					<?php 
						include 'resources/scripts/productdetail.php';
					?>
					<div class="pageExtender">
						&nbsp;
					</div>
				</div>
			&nbsp;
			</div>
		</div>
	</body>
</html>