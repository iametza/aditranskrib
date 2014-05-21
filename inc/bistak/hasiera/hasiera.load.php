<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");

	// Activamos el menu
	$menu_aktibo = 'hasiera';
	
	$eztabaidak = get_query("SELECT B.izenburua, B.nice_name
                            FROM eztabaidak A
                            INNER JOIN eztabaidak_hizkuntzak B
                            ON B.fk_elem=A.id
                            WHERE TRIM(B.nice_name) <> '' AND A.publiko = 1
                            ORDER BY A.data DESC");

	// Cargamos la plantilla
	require ("inc/bistak/hasiera/hasiera.txa.php");
?>
