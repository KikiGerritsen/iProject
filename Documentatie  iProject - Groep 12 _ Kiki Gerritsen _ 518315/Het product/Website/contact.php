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
	?>
		<div id="pagina">
			<div id="content">
				<div id="contactBlok">
					<h1>Contact</h1>
					<p>Naam</p>
					<input TYPE="text" NAME="Naam">
					<p>E-mail adres</p>
					<input TYPE="text" NAME="E-mailadres">
					<p>Uw vraag:</p>
					<select>
					<?php
							if(isset($_SESSION['username'])){
								echo '	
						<option value ="Vraag3"> De verkoper levert niet zijn product</option>
						<option value ="Vraag4"> Ik wil graag adverteren op u website</option>
						<option value ="Vraag5"> Ik heb geen idee wat ik aan het doen ben</option>
						<option value = "Vraag6"> Anders namelijk...</option>';
							}
							else{
								echo '
						<option value ="Vraag1"> Ik kom niet op mijn account</option>
						<option value ="Vraag2"> Ik ben mijn passwoord vergeten</option>
						<option value ="Vraag3"> De verkoper levert niet zijn product</option>
						<option value ="Vraag4"> Ik wil graag adverteren op u website</option>
						<option value ="Vraag5"> Ik heb geen idee wat ik aan het doen ben</option>
						<option value = "Vraag6"> Anders namelijk...</option>';
							}
						?>
					</select>
					<p>Extra beschrijving</p>
					<textarea id="extraBeschrijving" rows="10" cols="1" name="Details"></textarea>
					<input TYPE="submit" NAME="Verstuur" value="Verstuur">	
				</div>
				<div id="pageextender">
				&nbsp;
				</div>
				
			</div>
			&nbsp;
		</div>
	</div>
  </body>
</html>