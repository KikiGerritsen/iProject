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
		include"resources/scripts/dbConnection.php";
		include"resources/scripts/productDisplay.php";
		include"resources/scripts/class.timer.php";
	?>
		<div id="pagina">
			<?php
				include"resources/scripts/zoeken.php";
			?>
			<div id="content">
				<div class="homepageBlok">
					<h1>Veilingen van de dag</h1>
					<?php
						include"resources/scripts/productenvandedag.php";
					?>
				</div>
				<div class="subProductsBlok">
					<h1>Uitgelichte veilingen</h1>
					<?php
						include"resources/scripts/highlights.php";
					?>
				</div>
					<div class="subProductsBlok">
						<h1>Meest geboden veilingen</h1>
						<?php
							include"resources/scripts/populaireProducten.php";
						?>
					</div>
					<div class="pageExtender">
						&nbsp;
					</div>
				</div>
				&nbsp;
			</div>
		</div>
	</body>
</html>
					