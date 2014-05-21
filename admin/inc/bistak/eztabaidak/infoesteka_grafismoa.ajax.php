<?php
    define ("IRUDIEN_PATH", "img/eztabaidak/infoesteka_grafismoak/");
	
	// hh:mm:ss formatuko kate bat jaso eta kate horretako segundo kopurua itzultzen du.
	function kalkulatuSegundoak($denbora) {
		$zatiak = preg_split('/:/', $denbora);
        
		// Pasatako katean zenbat segundo dauden eskuratu
        $zatiak[0] = (int) $zatiak[0];
        $zatiak[1] = (int) $zatiak[1];
        $zatiak[2] = (int) $zatiak[2];
        
		$segundoak = $zatiak[0] * 60 * 60 + $zatiak[1] * 60 + $zatiak[2];
		
		return $segundoak;
	}
    
    // hh:mm:ss formatuko kate bat jaso eta kate horretako segundoak * 10 itzultzen du.
	function egokituDenboraHHMMSStik($denbora) {
		// Segundo kopurua * 10 egingo dugu, hori baita hitz bakoitzaren denbora definitzeko erabiltzen dugun formatua.
		return kalkulatuSegundoak($denbora) * 10;
	}
    
    // Erabiltzaileak infoesteka grafismo bat editatu eta gorde botoia sakatu badu.
    if (isset($_POST["gorde"])) {
        $id_eztabaida = isset($_POST["editatu_infoesteka_grafismoa_id_eztabaida"]) ? (int) $_POST["editatu_infoesteka_grafismoa_id_eztabaida"] : 0;
        $id_infoesteka_grafismoa = isset($_POST["editatu_infoesteka_grafismoa_id"]) ? (int) $_POST["editatu_infoesteka_grafismoa_id"] : 0;
		
        $hasiera = isset($_POST["editatu_infoesteka_grafismoa_hasiera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_hasiera"])) : "";
        $bukaera = isset($_POST["editatu_infoesteka_grafismoa_bukaera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_bukaera"])) : "";
		$id_infoesteka_mota = isset($_POST["editatu_infoesteka_grafismoa_mota"]) ? $_POST["editatu_infoesteka_grafismoa_mota"] : "";
		
        $erantzuna = new stdClass();
		
        // Infoesteka grafismo berri bat txertatu behar dugu DBan.
        if ($id_infoesteka_grafismoa == 0) {
			
            $sql = "INSERT INTO eztabaidak_infoak (hasiera, amaiera, fk_info_mota, fk_elem)
                    VALUES ('$hasiera', '$bukaera', '$id_infoesteka_mota', '$id_eztabaida')";
            
            if ($dbo->query($sql)) {
				
                // Recogemos el id recien creado
				$id_infoesteka_grafismoa = db_taula_azken_id("eztabaidak_infoak");
                
				// Oraingoz euskarazko testuak bakarrik. Aurrerako hainbat hizkuntza erabili ahal izateko egokitu behar litzateke. Begiratu hizlaria.ajax.php.
				$izenburua = isset($_POST["editatu_infoesteka_grafismoa_izenburua"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_izenburua"]) : "";
                $azalpena = isset($_POST["editatu_infoesteka_grafismoa_azalpena"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_azalpena"]) : "";
				$esteka = isset($_POST["editatu_infoesteka_grafismoa_esteka"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_esteka"]) : "";
				
				$sql = "INSERT INTO eztabaidak_infoak_hizkuntzak (izenburua, azalpena, esteka, fk_elem, fk_hizkuntza)
						VALUES ('$izenburua', '$azalpena', '$esteka', '$id_infoesteka_grafismoa', '1')";
				
				$dbo->query($sql);
				
                // Infoesteka grafismo berriaren id-a itzuliko dugu.
				$erantzuna->arrakasta = true;
                $erantzuna->id_infoesteka_grafismo_berria = $id_infoesteka_grafismoa;
				
            } else {
				
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
				
            }
        } else {
            $sql = "UPDATE eztabaidak_infoak
                    SET hasiera = '$hasiera', amaiera = '$bukaera', fk_info_mota = '$id_infoesteka_mota'
                    WHERE id = '$id_infoesteka_grafismoa'";
            
            if ($dbo->query($sql)) {
                
				// Oraingoz euskarazko testuak bakarrik. Aurrerako hainbat hizkuntza erabili ahal izateko egokitu behar litzateke. Begiratu hizlaria.ajax.php.
				$izenburua = isset($_POST["editatu_infoesteka_grafismoa_izenburua"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_izenburua"]) : "";
                $azalpena = isset($_POST["editatu_infoesteka_grafismoa_azalpena"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_azalpena"]) : "";
				$esteka = isset($_POST["editatu_infoesteka_grafismoa_esteka"]) ? testu_formatua_sql($_POST["editatu_infoesteka_grafismoa_esteka"]) : "";
				
				$sql = "UPDATE eztabaidak_infoak_hizkuntzak
						SET izenburua='$izenburua', azalpena='$azalpena', esteka='$esteka'
						WHERE fk_elem='$id_infoesteka_grafismoa' AND fk_hizkuntza = '1'";
				
				$dbo->query($sql);
				
				$erantzuna->arrakasta = true;
                $erantzuna->id_infoesteka_grafismoa = $id_infoesteka_grafismoa;
				
            } else {
				
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
				
            }
        }
        
        echo json_encode($erantzuna);
		
    }
    
    exit();
?>