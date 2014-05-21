<?php
    // Erabiltzaileak hizlari bat editatu eta gorde botoia sakatu badu.
    if (isset($_POST["gorde"])) {
        $id_eztabaida = isset($_POST["editatu_hizlaria_id_eztabaida"]) ? (int) $_POST["editatu_hizlaria_id_eztabaida"] : 0;
        $id_hizlaria = isset($_POST["editatu_hizlaria_id"]) ? (int) $_POST["editatu_hizlaria_id"] : 0;
        $bilagarria = isset($_POST["editatu_hizlaria_bilagarria"]) ? (int) $_POST["editatu_hizlaria_bilagarria"] : 0;
        $kolorea = isset($_POST["editatu_hizlaria_kolorea"]) ? testu_formatua_sql($_POST["editatu_hizlaria_kolorea"]) : "";        
        
        $erantzuna = new stdClass();
		
        if ($id_hizlaria == 0) {
            $sql = "INSERT INTO eztabaidak_hizlariak (kolorea, bilagarria, fk_elem) " .
                   "VALUES ('$kolorea', $bilagarria, '$id_eztabaida')";
            
            if ($dbo->query($sql)) {
				
                // Recogemos el id recien creado
				$id_hizlaria = db_taula_azken_id ("eztabaidak_hizlariak");
                
				// Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id) {
					$izena = isset($_POST["editatu_hizlaria_izena_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_izena_$h_id"]) : "";
					$izen_laburra = isset($_POST["editatu_hizlaria_izen_laburra_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_izen_laburra_$h_id"]) : "";
					$aurrizkia = isset($_POST["editatu_hizlaria_aurrizkia_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_aurrizkia_$h_id"]) : "";
					$gazta_etiketa = isset($_POST["editatu_hizlaria_gazta_etiketa_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_gazta_etiketa_$h_id"]) : "";
					$grafismoa_deskribapena = isset($_POST["editatu_hizlaria_grafismoa_deskribapena_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_grafismoa_deskribapena_$h_id"]) : "";
					$grafismoa_esteka = isset($_POST["editatu_hizlaria_grafismoa_esteka_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_grafismoa_esteka_$h_id"]) : "";
					
					$sql = "INSERT INTO eztabaidak_hizlariak_hizkuntzak (izena, izen_laburra, aurrizkia, gazta_etiketa, grafismoa_deskribapena, grafismoa_esteka, fk_elem, fk_hizkuntza) " .
						   "VALUES ('$izena', '$izen_laburra', '$aurrizkia', '$gazta_etiketa', '$grafismoa_deskribapena', '$grafismoa_esteka', '$id_hizlaria', '$h_id')";
					
					if ($dbo->query($sql)) {
						$erantzuna->arrakasta = true;
						
						// Hizlari berriaren id-a itzuliko dugu.
						$erantzuna->id_hizlari_berria = $id_hizlaria;
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
            $sql = "UPDATE eztabaidak_hizlariak
                    SET kolorea = '$kolorea', bilagarria = $bilagarria
                    WHERE id = '$id_hizlaria'";
				
            if ($dbo->query($sql)) {
                // Hizkuntza bakoitzaren testua gordeko dugu
                foreach (hizkuntza_idak() as $h_id){
                    $izena = isset($_POST["editatu_hizlaria_izena_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_izena_$h_id"]) : "";
                    $izen_laburra = isset($_POST["editatu_hizlaria_izen_laburra_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_izen_laburra_$h_id"]) : "";
					$aurrizkia = isset($_POST["editatu_hizlaria_aurrizkia_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_aurrizkia_$h_id"]) : "";
					$gazta_etiketa = isset($_POST["editatu_hizlaria_gazta_etiketa_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_gazta_etiketa_$h_id"]) : "";
					$grafismoa_deskribapena = isset($_POST["editatu_hizlaria_grafismoa_deskribapena_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_grafismoa_deskribapena_$h_id"]) : "";
					$grafismoa_esteka = isset($_POST["editatu_hizlaria_grafismoa_esteka_$h_id"]) ? testu_formatua_sql($_POST["editatu_hizlaria_grafismoa_esteka_$h_id"]) : "";
					
                    $sql = "UPDATE eztabaidak_hizlariak_hizkuntzak
                            SET izena = '$izena', izen_laburra = '$izen_laburra', aurrizkia = '$aurrizkia', gazta_etiketa = '$gazta_etiketa', grafismoa_deskribapena = '$grafismoa_deskribapena', grafismoa_esteka = '$grafismoa_esteka'
                            WHERE eztabaidak_hizlariak_hizkuntzak.fk_elem = '$id_hizlaria'
                            AND eztabaidak_hizlariak_hizkuntzak.fk_hizkuntza = '$h_id'";
                    
                    if ($dbo->query($sql)) {
                        $erantzuna->arrakasta = true;
                        $erantzuna->id_hizlaria = $id_hizlaria;
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
        
		$grafismoa_irudia = fitxategia_igo("editatu_hizlaria_grafismoa_irudia", "../" . GRAFISMOEN_PATH);
		
        if (trim ($grafismoa_irudia) != ""){
            $path_grafismoa_irudia = fitxategia_path("eztabaidak_hizlariak", "path_grafismoa_irudia", $id_hizlaria);
            fitxategia_ezabatu("eztabaidak_hizlariak", "grafismoa_irudia", $id_hizlaria, "../" . $path_grafismoa_irudia);
            $sql = "UPDATE eztabaidak_hizlariak SET grafismoa_irudia = '$grafismoa_irudia', path_grafismoa_irudia='" . GRAFISMOEN_PATH . "' WHERE id='$id_hizlaria'";
            $dbo->query ($sql) or die ($dbo->ShowError ());
        }
		
        echo json_encode($erantzuna);
        
    // Bestela formulario modala bete behar dugu hautatutako hizlariaren datuekin.
    } else {
        $id_hizlaria = isset($_POST["id_hizlaria"]) ? (int) $_POST["id_hizlaria"] : 0;
		
		$sql = "SELECT id, path_grafismoa_irudia, grafismoa_irudia, bilagarria, kolorea, orden
				FROM eztabaidak_hizlariak
				WHERE id = $id_hizlaria";
		
        $erantzuna = new stdClass();
        
		$dbo->query($sql);
		
        if ($row = $dbo->emaitza()) {
            $erantzuna->arrakasta = true;
            
            $erantzuna->bilagarria = $row["bilagarria"];
            $erantzuna->kolorea = $row["kolorea"];
			$erantzuna->path_grafismoa_irudia = $row["path_grafismoa_irudia"];
            $erantzuna->grafismoa_irudia = $row["grafismoa_irudia"];
			
			// Hizlari horri hizkuntza desberdinetan dagozkion testuak eskuratuko ditugu.
            $erantzuna->hizkuntzak = array();
            
            foreach (hizkuntza_idak() as $h_id) {
				$sql = "SELECT * FROM eztabaidak_hizlariak_hizkuntzak WHERE fk_elem = '$id_hizlaria' AND fk_hizkuntza = '$h_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
				
				$rowHizk = $dbo->emaitza();
				
                $tmp_hizkuntza = new stdClass();
                
                $tmp_hizkuntza->h_id = $h_id;
                $tmp_hizkuntza->hizkuntza = get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id);
				
				$tmp_hizkuntza->izena = $rowHizk["izena"];
				$tmp_hizkuntza->izen_laburra = $rowHizk["izen_laburra"];
				$tmp_hizkuntza->aurrizkia = $rowHizk["aurrizkia"];
				$tmp_hizkuntza->gazta_etiketa = $rowHizk["gazta_etiketa"];
				$tmp_hizkuntza->grafismoa_deskribapena = $rowHizk["grafismoa_deskribapena"];
                $tmp_hizkuntza->grafismoa_esteka = $rowHizk["grafismoa_esteka"];
				
                array_push($erantzuna->hizkuntzak, $tmp_hizkuntza);
			}
        } else {
            // Hizlaria ez da existitzen. Hizkuntzen datuak bidaliko ditugu bueltan, behar adina fieldset sortzeko.
            $erantzuna->arrakasta = true;
            
            // Hizlari horri hizkuntza desberdinetan dagozkion testuak eskuratuko ditugu.
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