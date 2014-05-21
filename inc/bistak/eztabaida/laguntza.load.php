<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");
    
    // Laguntzako orriaren datuak eskuratuko ditugu
    $laguntza = new stdClass();
    
    $sql = "SELECT testua
            FROM eztabaidak_laguntza
            WHERE gakoa = 'laguntza'";
    
    $dbo->query($sql) or die ($dbo->ShowError());
    $row = $dbo->emaitza();
    
    $laguntza->testua = $row["testua"];
    
	require("inc/bistak/eztabaida/laguntza.php");
?>