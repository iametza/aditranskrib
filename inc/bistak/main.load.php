<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");
	
	// Comprobamos en que apartado estamos
	switch ($url->hurrengoa()) {
		case "eztabaida":
			require("inc/bistak/eztabaida/eztabaida.load.php");
			break;
		default:
			require("inc/bistak/hasiera/hasiera.load.php");
			break;
	}
?>
