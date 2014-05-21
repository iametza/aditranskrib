<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aditranskrib</title>
	</head>

	<body>
		<h1>Aditranskrib</h1>
		
		<h2>Eztabaidak</h2>
		<?php
			foreach($eztabaidak as $eztabaida) {
				echo("<p><a href='" . URL_BASE . "eztabaida/" . $eztabaida['nice_name'] . "'>" . $eztabaida['izenburua'] . "</a></p>");
			} 
		?>
	</body>
</html>
