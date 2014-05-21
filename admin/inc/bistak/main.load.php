<?php
	switch ($url->hurrengoa()) {
		case "eztabaidak":
			require("inc/bistak/eztabaidak/eztabaidak.load.php");
			break;
		case "konfigurazioa":
			require ("inc/bistak/konfigurazioa/konfigurazioa.load.php");
			break;
		case "administrazioa":
			require ("inc/bistak/administrazioa/administrazioa.load.php");
			break;
		case "literalak":
			require ("inc/bistak/literalak/literalak.load.php");
			break;
		case "logout":
			require ("inc/bistak/logout/logout.load.php");
			break;
		default:
			$content = "inc/bistak/hasiera/hasiera.php";
			break;
	}
	
	require ("templates/itxura.php");
?>
