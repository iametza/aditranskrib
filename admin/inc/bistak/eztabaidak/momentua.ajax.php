<?php
    
    // Momentuaren hasiera eta bukaerako segundoak * 10 jaso eta bere iraupena kalkulatzen du (hh h) (mm min) ss seg formatuan.
	function kalkulatuMomentuarenIraupena($hasiera, $bukaera) {
		$katea = "";
		$segundoak = $bukaera / 10 - $hasiera / 10;
        
		// Zenbat ordu daude segundo horietan?
		$h = floor($segundoak / 3600);
        
		// Itzuliko dugun kateari orduak gehitu
		if ($h > 0) {
			$katea = $katea . $h . " h ";
		}
        
		// Ordu horiek kenduta geratzen diren segundoak
		$segundoak = $segundoak - $h * 3600;
		
		// Zenbat minutu daude geratzen diren segundoetan?
		$m = floor($segundoak / 60);
		
		// Itzuliko dugun kateari minutuak gehitu
		if ($m > 0) {
			$katea = $katea . ' ' . $m . ' min ';
		}
		
		// Minutu horiek kenduta geratzen diren segundoak
		$s = $segundoak - $m * 60;
		
		// Itzuliko dugun kateari segundoak gehitu
		if ($s > 0) {
			$katea = $katea . $s . ' seg';
		}
		
		return $katea;
	}
	
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
    
    // Erabiltzaileak hizlari bat editatu eta gorde botoia sakatu badu.
    if (isset($_POST["gorde"])) {
        $id_eztabaida = isset($_POST["editatu_momentua_id_eztabaida"]) ? (int) $_POST["editatu_momentua_id_eztabaida"] : 0;
        $id_momentua = isset($_POST["editatu_momentua_id"]) ? (int) $_POST["editatu_momentua_id"] : 0;
        $irudia = isset($_POST["irudia"]) ? $_POST["irudia"] : "";
        $hasiera = isset($_POST["editatu_momentua_hasiera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_momentua_hasiera"])) : "";
        $bukaera = isset($_POST["editatu_momentua_bukaera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_momentua_bukaera"])) : "";
        $iraupena = kalkulatuMomentuarenIraupena($hasiera, $bukaera);
        
        $erantzuna = new stdClass();
        
        if ($id_momentua == 0) {
            $sql = "INSERT INTO eztabaidak_momentuak (start_ms, end_ms, iraupena, fk_elem) " .
                   "VALUES ('$hasiera', '$bukaera', '$iraupena', '$id_eztabaida')";
            
            if ($dbo->query($sql)) {
                // Recogemos el id recien creado
				$id_momentua = db_taula_azken_id("eztabaidak_momentuak");
                
                // Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id){
                    $tmp_testua = isset($_POST["editatu_momentua_testua_$h_id"]) ? testu_formatua_sql($_POST["editatu_momentua_testua_$h_id"]) : "";
                    
                    $sql = "INSERT INTO eztabaidak_momentuak_hizkuntzak (testua, fk_elem, fk_hizkuntza) " .
                           "VALUES ('$tmp_testua', '$id_momentua', $h_id)";
                
                    if ($dbo->query($sql)) {
                        $erantzuna->arrakasta = true;
                        
                        // Momentu berriaren id-a itzuliko dugu.
                        $erantzuna->id_momentu_berria = $id_momentua;
                    } else {
                        $erantzuna->arrakasta = false;
                        $erantzuna->mezua = $dbo->ShowError();
                    }
                }
            } else {
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
            }
        } else {
            $sql = "UPDATE eztabaidak_momentuak " .
                   "SET start_ms = '$hasiera', end_ms = '$bukaera', iraupena = '$iraupena' " .
                   "WHERE id = '$id_momentua'";
            
            if ($dbo->query($sql)) {
                // Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id){
                    $tmp_testua = isset($_POST["editatu_momentua_testua_$h_id"]) ? testu_formatua_sql($_POST["editatu_momentua_testua_$h_id"]) : "";
                    
                    $sql = "UPDATE eztabaidak_momentuak_hizkuntzak " .
                           "SET testua = '$tmp_testua' " .
                           "WHERE eztabaidak_momentuak_hizkuntzak.fk_elem = '$id_momentua' " .
                           "AND eztabaidak_momentuak_hizkuntzak.fk_hizkuntza = '$h_id'";
                    
                    if ($dbo->query($sql)) {
                        $erantzuna->arrakasta = true;
                        $erantzuna->id_momentua = $id_momentua;
                    } else {
                        $erantzuna->arrakasta = false;
                        $erantzuna->mezua = $dbo->ShowError();
                    }
                }
            } else {
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
            }
        }
        
        $irudia = fitxategia_igo("editatu_momentua_irudia", "../" . MOMENTUEN_IRUDIEN_PATH);
        
        if (trim ($irudia) != ""){
            $path_irudia = fitxategia_path("eztabaidak_momentuak", "path_irudia", $id_momentua);
            fitxategia_ezabatu("eztabaidak_momentuak", "irudia", $id_momentua, "../" . $path_irudia);
            $sql = "UPDATE eztabaidak_momentuak SET irudia='$irudia', path_irudia='" . MOMENTUEN_IRUDIEN_PATH . "' WHERE id='$id_momentua'";
            $dbo->query ($sql) or die ($dbo->ShowError ());
        }
        
        echo json_encode($erantzuna);
        
    // Bestela formulario modala bete behar dugu hautatutako momentuaren datuekin.
    } else {
        $id_momentua = isset($_POST["id_momentua"]) ? (int) $_POST["id_momentua"] : 0;
    
        // Momentuaren datuak eskuratuko ditugu
        $sql = "SELECT id, path_irudia, irudia, start_ms, end_ms, orden FROM eztabaidak_momentuak " .
               "WHERE id = $id_momentua";
        
        $erantzuna = new stdClass();
        
        $dbo->query ($sql) or die ($dbo->ShowError());
        
        if ($dbo->query($sql)) {
            // Momentua dagoeneko existitzen da, bere datuak itzuliko ditugu.
            $row = $dbo->emaitza();
            
            $erantzuna->arrakasta = true;
            
            $erantzuna->path_irudia = $row["path_irudia"];
            $erantzuna->irudia = $row["irudia"];
            $erantzuna->hasiera = $row["start_ms"];
            $erantzuna->bukaera = $row["end_ms"];
            
            // Momentu horri hizkuntza desberdinetan dagozkion testuak eskuratuko ditugu.
            $erantzuna->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
				$sql = "SELECT * FROM eztabaidak_momentuak_hizkuntzak WHERE fk_elem = '$id_momentua' AND fk_hizkuntza = '$h_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
				
				$rowHizk = $dbo->emaitza();
				
                $tmp_hizkuntza = new stdClass();
                
                $tmp_hizkuntza->h_id = $h_id;
                $tmp_hizkuntza->hizkuntza = get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id);
				$tmp_hizkuntza->testua = $rowHizk["testua"];
                
                array_push($erantzuna->hizkuntzak, $tmp_hizkuntza);
			}
        } else {
            // Momentua ez da existitzen. Hizkuntzen datuak bidaliko ditugu bueltan, behar adina fieldset sortzeko.
            $erantzuna->arrakasta = true;
            
            // Momentu horri hizkuntza desberdinetan dagozkion testuak eskuratuko ditugu.
            $erantzuna->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
                $tmp_hizkuntza = new stdClass();
                
                $tmp_hizkuntza->h_id = $h_id;
                $tmp_hizkuntza->hizkuntza = get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id);
                
                array_push($erantzuna->hizkuntzak, $tmp_hizkuntza);
			}
        }
        
        echo json_encode($erantzuna);
    }
    
    exit();
?>