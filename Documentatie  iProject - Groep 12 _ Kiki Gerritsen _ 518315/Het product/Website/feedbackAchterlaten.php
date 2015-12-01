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
		if(isset($_POST['feedbackSoort'])){
			$feedbackSoort=$_POST['feedbackSoort'];
		}
		$extraBeschrijving='';
		if(isset($_POST['extraBeschrijving'])){
			$extraBeschrijving=$_POST['extraBeschrijving'];
		}
		
		$voorwerp=$_GET['voorwerp'];				///MOET WORDEN MEEGEGEVEN IN DE EMAIL
		$gebruiker=$_GET['gebruiker'];			///MOET WORDEN MEEGEGEVEN IN DE EMAIL
		
		$magNietMeer=false;
		$sqlCheckFeedback='select *
										from Feedback
										where Voorwerp='.$voorwerp.'';
		$stmtCheckFeedback = sqlsrv_query($conn, $sqlCheckFeedback);
		$rowCheckFeedback = sqlsrv_fetch_array($stmtCheckFeedback, SQLSRV_FETCH_ASSOC);
		if($rowCheckFeedback['Voorwerp']==$voorwerp){
			$magNietMeer=true;
		}
		$sql="select *
				from Gebruiker
				where Gebruikersnaam='".$gebruiker."'";
		$stmt = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		if($row['Verkoper']=='nee'){
			$soortGebruiker='Koper';
		}
		else{
			$soortGebruiker='Verkoper';
		}
		$huidigDag = date('d');
		$huidigMaand = date('m');
		$huidigJaar = date('Y');
		$huidigUur = date("G");
		$huidigMinuut = date("i");
		if(isset($_POST['feedbackSoort']) && $magNietMeer==false){
			$insertSQL="insert into Feedback VALUES ('".$voorwerp."', '".$soortGebruiker."', '".$feedbackSoort."', '".$huidigDag."-".$huidigMaand."-".$huidigJaar."', '".$huidigUur.":".$huidigMinuut."', '".$extraBeschrijving."')";
			$stmtINSERT = sqlsrv_query($conn, $insertSQL);
			$insert = sqlsrv_fetch_array($stmtINSERT, SQLSRV_FETCH_ASSOC);
		}
	?>
		<div id="pagina">
			<div id="content">
				<div id="contactBlok">
					<h1>Feedback</h1>
					<?php
							if(isset($_POST['feedbackSoort']) && $magNietMeer==false){
								echo 'Uw feedback is achtergelaten! Bedankt voor uw moeite.';
							}
							else{
								echo '
									<form METHOD="post" action="feedbackAchterlaten.php?voorwerp='.$voorwerp.'&gebruiker='.$gebruiker.'">
									<select name="feedbackSoort">
										<option value ="neutraal"> Ik ben voldoende tevreden met de verkoper.</option>
										<option value ="negatief"> Ik ben onvoldoende tevreden met de verkoper</option>
										<option value ="positief"> Ik ben zeer tevreden met de verkoper.</option>
									</select>
									<p>Extra commentaar</p>
									<textarea id="extraBeschrijving" rows="10" cols="1" name="extraCommentaar"></textarea>
									<input TYPE="submit" NAME="Verstuur" value="Verstuur">	
									</form>';
							}
					?>
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