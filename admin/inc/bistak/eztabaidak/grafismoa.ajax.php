<?php
    define ("IRUDIEN_PATH", "img/eztabaidak/grafismoak/");
    
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
    
    // Erabiltzaileak grafismo bat editatu eta gorde botoia sakatu badu.
    if (isset($_POST["gorde"])) {
        $id_eztabaida = isset($_POST["editatu_grafismoa_id_eztabaida"]) ? (int) $_POST["editatu_grafismoa_id_eztabaida"] : 0;
        $id_grafismoa = isset($_POST["editatu_grafismoa_id"]) ? (int) $_POST["editatu_grafismoa_id"] : 0;
        $id_hizlaria = isset($_POST["editatu_grafismoa_id_hizlaria"]) ? (int) $_POST["editatu_grafismoa_id_hizlaria"] : 0;
		
        $hasiera = isset($_POST["editatu_grafismoa_hasiera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_grafismoa_hasiera"])) : "";
        $bukaera = isset($_POST["editatu_grafismoa_bukaera"]) ? egokituDenboraHHMMSStik(testu_formatua_sql($_POST["editatu_grafismoa_bukaera"])) : "";
		
        $erantzuna = new stdClass();
		
        // Grafismo berri bat txertatu behar dugu DBan.
        if ($id_grafismoa == 0) {
			
            $sql = "INSERT INTO eztabaidak_grafismoak (hasiera_ms, amaiera_ms, fk_hizlaria, fk_elem)
                    VALUES ('$hasiera', '$bukaera', '$id_hizlaria', '$id_eztabaida')";
            
            if ($dbo->query($sql)) {
				
                // Recogemos el id recien creado
				$id_grafismoa = db_taula_azken_id("eztabaidak_grafismoak");
                
                // Momentu berriaren id-a itzuliko dugu.
				$erantzuna->arrakasta = true;
                $erantzuna->id_grafismo_berria = $id_grafismoa;
				
            } else {
				
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
				
            }
        } else {
            $sql = "UPDATE eztabaidak_grafismoak
                    SET hasiera_ms = '$hasiera', amaiera_ms = '$bukaera', fk_hizlaria = '$id_hizlaria'
                    WHERE id = '$id_grafismoa'";
            
            if ($dbo->query($sql)) {
                
				$erantzuna->arrakasta = true;
                $erantzuna->id_grafismoa = $id_grafismoa;
				
            } else {
				
                $erantzuna->arrakasta = false;
                $erantzuna->mezua = $dbo->ShowError();
				
            }
        }
        
        echo json_encode($erantzuna);
		
    }
    
    exit();
?>