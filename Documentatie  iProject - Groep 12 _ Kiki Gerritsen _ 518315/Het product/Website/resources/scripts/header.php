<?php
	$_GET['uitloggen'] = false;
	session_start();
		
	if(isset($_SESSION['username'])){
		$welkom = "Welkom, <a href='profiel.php'>".$_SESSION['username']."</a>";
	} else {
		$welkom = "U bent niet ingelogd.";
	}
	
	function curPageURL() {
		$pageURL = 'http';
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	$end = end(explode('?', curPageURL()));
	
	if($end == "uitloggen=true"){
		echo $end;
		$_SESSION['username'] = null;
		header ('location: index.php');
	}
?>

<div class="behindMenuBar">
				&nbsp;
			</div>
			<div id="header">
			<div id="headerContent">
				<div id="logo">
					<a href="index.php"><img src="resources/images/logo.png" alt="logo"></a>
				</div>
				<div id="login">
					<p><?php echo $welkom; ?></p>
				</div>
			</div>
		</div>