<?php
	include"resources/scripts/dbConnection.php";
	echo'<div class="navWrap">
				<div id="cssmenu">
					<ul>
						<li><a href="index.php"><span>Home</span></a></li>
						<li><a href="productpagina.php?page=1"><span>Producten</span></a></li>
						<li class="has-sub"><a href="spelregels.php"><span>Spelregels</span></a></li>
							<li><a href="#"><span>Wie wij zijn</span></a>
							<ul>
								<li><a href="contact.php"><span>Contact</span></a></li>
								<li><a href="overons.php"><span>Over ons</span></a></li>
							</ul>
							</li>
						';
							if(isset($_SESSION['username'])){
								echo '<li class="has-sub last"><a href="#"><span>Account</span></a>
											<ul>
												<li><a href="?uitloggen=true"><span>Uitloggen</span></a></li>
												<li><a href="profiel.php"><span>Profiel</span></a></li>';
								$sql2 =	"select Verkoper
											from Gebruiker
											where Gebruikersnaam='".$_SESSION['username']."' AND Verkoper='ja'";
								$stmt2 = sqlsrv_query($conn, $sql2);
								$row2 = sqlsrv_has_rows($stmt2);
								if($row2==true){
									echo '<li><a href="veilingtoevoegen.php"><span>Nieuwe veiling</span></a></li>';
								}
						}
							else{
								echo '
								<li class="has-sub last"><a href="#"><span>Account</span></a>
											<ul>
								<li><a href="inloggen.php"><span>Log in</span></a></li>
								<li class="last"><a href="registratie.php"><span>Registreer</span></a></li>';
							}
							echo '
					</ul>
					</li>
				</ul>
			</div>
		</div>';
 ?> 