<?php
	// Protegemos el archivo del "acceso directo"
	if (!isset ($url)) header ("Location: /");

	// Activamos el menu
	$menu_aktibo = 'eztabaida';
	
	$nice_name = $url->hurrengoa();
	
	if ($nice_name == "laguntza") {
		require ("inc/bistak/eztabaida/laguntza.load.php");
	} else {
	
		$sql = "SELECT A.id, A.url_bideoa, A.momenturik_onenak_bai, A.bilaketa_bai, A.gazta_bai, A.partekatu_bai, A.txertatu_bai, A.lizentzia_bai, A.barrak_bai,
				A.zutabea_non, A.azpitituluak_non, A.path_posterra, A.posterra, A.path_lizentzia, A.lizentzia, A.path_swf, A.kalitate_lehenetsia, A.barrak_testu_kolorea,
				A.gazta_testu_kolorea, A.gazta_marra_kolorea, B.nice_name, B.izenburua, B.path_hipertranskribapena, B.hipertranskribapena,
				B.hipertranskribapena_testua, B.path_azpitituluak, B.azpitituluak, B.hash_tag, B.fb_izenburua, B.bilaketa_kaxa_testua, B.url_lizentzia,
				B.azpitituluak_bistaratu, B.azpitituluak_botoia
				FROM eztabaidak AS A INNER JOIN eztabaidak_hizkuntzak AS B ON A.id = B.fk_elem
				WHERE B.nice_name<>'' AND B.nice_name='" . $nice_name . "'";
		$dbo->query($sql) or die ($dbo->ShowError());
		
		$eztabaida = new stdClass();
		
		if($row = $dbo->emaitza()){
			$eztabaida->id = $row["id"];
			
			$eztabaida->url_bideoa = $row["url_bideoa"];
			$eztabaida->momenturik_onenak_bai = $row["momenturik_onenak_bai"];
			
			$eztabaida->bilaketa_bai = $row["bilaketa_bai"];
			$eztabaida->gazta_bai = $row["gazta_bai"];
			$eztabaida->partekatu_bai = $row["partekatu_bai"];
			$eztabaida->txertatu_bai = $row["txertatu_bai"];
			$eztabaida->lizentzia_bai = $row["lizentzia_bai"];
			$eztabaida->barrak_bai = $row["barrak_bai"];
			$eztabaida->zutabea_non = $row["zutabea_non"];
			
			$eztabaida->path_posterra = $row["path_posterra"];
			$eztabaida->posterra = $row["posterra"];
			
			$eztabaida->path_lizentzia = $row["path_lizentzia"];
			$eztabaida->lizentzia = $row["lizentzia"];
			$eztabaida->url_lizentzia = $row["url_lizentzia"];
			
			$eztabaida->path_swf = $row["path_swf"];
			$eztabaida->kalitate_lehenetsia = $row["kalitate_lehenetsia"];
			$eztabaida->nice_name = $row["nice_name"];
			$eztabaida->izenburua = $row["izenburua"];
			$eztabaida->path_hipertranskribapena = $row["path_hipertranskribapena"];
			$eztabaida->hipertranskribapena = $row["hipertranskribapena"];
			$eztabaida->hipertranskribapena_testua = json_encode($row["hipertranskribapena_testua"]);
			$eztabaida->path_azpitituluak = $row["path_azpitituluak"];
			$eztabaida->azpitituluak = $row["azpitituluak"];
			$eztabaida->azpitituluak_bistaratu = $row["azpitituluak_bistaratu"];
			$eztabaida->azpitituluak_botoia = $row["azpitituluak_botoia"];
			$eztabaida->azpitituluak_non = $row["azpitituluak_non"];
			
			$eztabaida->hash_tag = $row["hash_tag"];
			$eztabaida->fb_izenburua = $row["fb_izenburua"];
			$eztabaida->bilaketa_kaxa_testua = $row["bilaketa_kaxa_testua"];
			
			$eztabaida->barrak_testu_kolorea = $row["barrak_testu_kolorea"];
			$eztabaida->gazta_testu_kolorea = $row["gazta_testu_kolorea"];
			$eztabaida->gazta_marra_kolorea = $row["gazta_marra_kolorea"];
			
			$emaitza = get_query("SELECT * FROM eztabaidak_momentuak AS A INNER JOIN eztabaidak_momentuak_hizkuntzak AS B ON A.id = B.fk_elem WHERE A.orden<>'0' AND A.fk_elem=" . $row["id"] . "");
		
			$eztabaida->momentuak = array();
			
			foreach ($emaitza as $e) {
				$tmp_momentua = new stdClass();
				
				$tmp_momentua->path_irudia = $e["path_irudia"];
				$tmp_momentua->irudia = $e["irudia"];
				$tmp_momentua->testua = $e["testua"];
				$tmp_momentua->start_ms = $e["start_ms"];
				$tmp_momentua->end_ms = $e["end_ms"];
				$tmp_momentua->iraupena = $e["iraupena"];
				
				array_push($eztabaida->momentuak, $tmp_momentua);
			}
			
			$emaitza = get_query("SELECT A.id, A.bilagarria, A.kolorea, A.path_grafismoa_irudia, A.grafismoa_irudia, A.orden, B.izena, B.izen_laburra, B.aurrizkia,
								 B.gazta_etiketa, B.grafismoa_deskribapena, B.grafismoa_esteka
								 FROM eztabaidak_hizlariak AS A INNER JOIN eztabaidak_hizlariak_hizkuntzak AS B
								 ON A.id = B.fk_elem
								 WHERE A.orden<>'0' AND A.fk_elem=" . $row["id"] . "");
		
			$eztabaida->hizlariak = array();
			
			$eztabaida->hizlari_bilagarriak = array();
			
			$zenbagarren_hizlaria = 0;
			
			foreach ($emaitza as $e) {
				$tmp_hizlaria = new stdClass();
				
				$tmp_hizlaria->id = $e["id"];
				$tmp_hizlaria->bilagarria = $e["bilagarria"];
				$tmp_hizlaria->kolorea = $e["kolorea"];
				$tmp_hizlaria->orden = $e["orden"];
				$tmp_hizlaria->izena = $e["izena"];
				$tmp_hizlaria->izen_laburra = $e["izen_laburra"];
				$tmp_hizlaria->aurrizkia = $e["aurrizkia"];+
				$tmp_hizlaria->gazta_etiketa = $e["gazta_etiketa"];
				$tmp_hizlaria->grafismoa_deskribapena = $e["grafismoa_deskribapena"];
				$tmp_hizlaria->grafismoa_esteka = $e["grafismoa_esteka"];
				$tmp_hizlaria->path_grafismoa_irudia = $e["path_grafismoa_irudia"];
				$tmp_hizlaria->grafismoa_irudia = $e["grafismoa_irudia"];
				
				// Hizlari bilagarriek $eztabaida->hizlariak arrayan duten indizeekin array bat osatuko dugu
				if ($tmp_hizlaria->bilagarria ==  1) {
					array_push($eztabaida->hizlari_bilagarriak, $zenbagarren_hizlaria);
				}
				
				$zenbagarren_hizlaria = $zenbagarren_hizlaria + 1;
				
				array_push($eztabaida->hizlariak, $tmp_hizlaria);
			}
			
			// Grafismoak
			$emaitza = get_query("SELECT A.id, A.hasiera_ms, A.amaiera_ms, A.fk_hizlaria, A.orden
								 FROM eztabaidak_grafismoak AS A
								 WHERE A.orden <> '0' AND A.fk_elem=" . $row["id"] . "");
		
			$eztabaida->grafismoak = array();
			
			foreach ($emaitza as $e) {
				$tmp_grafismoa = new stdClass();
				
				$tmp_grafismoa->id = $e["id"];
				$tmp_grafismoa->hasiera = $e["hasiera_ms"] / 10; // lowerThird-aren hasierako eta bukaerako denborek segundotan egon behar dute.
				$tmp_grafismoa->amaiera = $e["amaiera_ms"] / 10; // lowerThird-aren hasierako eta bukaerako denborek segundotan egon behar dute.
				
				for ($i = 0; $i < count($eztabaida->hizlariak); $i++) {
					if ($eztabaida->hizlariak[$i]->id == $e["fk_hizlaria"]) {
						$tmp_grafismoa->indizea_hizlaria = $i;
						break;	
					}
				}
				
				$tmp_grafismoa->orden = $e["orden"];
				
				array_push($eztabaida->grafismoak, $tmp_grafismoa);
			}
			
			// Infoak
			$sql = "SELECT A.id, A.hasiera, A.amaiera, B.izenburua, B.azalpena, B.esteka, C.path_irudia
					FROM eztabaidak_infoak AS A
					INNER JOIN eztabaidak_infoak_hizkuntzak AS B
					ON A.id = B.fk_elem
					INNER JOIN eztabaidak_infoak_motak AS C
					ON A.fk_info_mota = C.id
					WHERE A.fk_elem=" . $row["id"] . " AND B.fk_hizkuntza = " . $hizkuntza['id'] . "";;
			
			$dbo->query($sql) or die($dbo->ShowError());
			
			$eztabaida->infoak = array();
			
			while ($row = $dbo->emaitza()) {
				
				$tmp_infoa = new stdClass();
				
				$tmp_infoa->id = $row["id"];
				$tmp_infoa->hasiera = $row["hasiera"] / 10; 	// Infoen hasierako eta bukaerako denborek segundotan egon behar dute.
				$tmp_infoa->amaiera = $row["amaiera"] / 10;	// Infoen hasierako eta bukaerako denborek segundotan egon behar dute.
				$tmp_infoa->izenburua = $row["izenburua"];
				$tmp_infoa->azalpena = $row["azalpena"];
				$tmp_infoa->esteka = $row["esteka"];
				$tmp_infoa->path_irudia = $row["path_irudia"];
				
				array_push($eztabaida->infoak, $tmp_infoa);
			}
			
			$eztabaida->laguntza = new stdClass();
			
			$emaitza = get_query("SELECT testua
								  FROM eztabaidak_laguntza
								  WHERE gakoa = 'laguntza_momenturik_onenak'");
				
			$eztabaida->laguntza->momenturik_onenak = $emaitza[0]["testua"];
			
			$emaitza = get_query("SELECT testua
								  FROM eztabaidak_laguntza
								  WHERE gakoa = 'laguntza_bilaketa'");
				
			$eztabaida->laguntza->bilaketa = $emaitza[0]["testua"];
			
			$emaitza = get_query("SELECT testua
								  FROM eztabaidak_laguntza
								  WHERE gakoa = 'laguntza_partekatu'");
				
			$eztabaida->laguntza->partekatu = $emaitza[0]["testua"];
			
			/*$emaitza = get_query("SELECT path, bideoa, mota, kalitatea FROM eztabaidak_bideoak WHERE fk_elem=" . $row["id"] . " ORDER BY kalitatea");
		
			$eztabaida->bideoak = array();
			
			$eztabaida->bideoak[0] = array();
			$eztabaida->bideoak[1] = array();
			$eztabaida->bideoak[2] = array();
			$eztabaida->bideoak[3] = array();
			
			$eztabaida->bideo_motak = array();
			$eztabaida->bideo_kalitateak = array();
			
			foreach ($emaitza as $e) {
				$tmp_bideoa = new stdClass();
				
				$tmp_bideoa->path = $e["path"];
				$tmp_bideoa->bideoa = $e["bideoa"];
				$tmp_bideoa->mota = $e["mota"];
				
				array_push($eztabaida->bideoak[$e["kalitatea"]], $tmp_bideoa);
				
				// Mota ez badago arrayan gehitu
				if (in_array($e["mota"], $eztabaida->bideo_motak) === false) {
					$eztabaida->bideo_motak[] = $e["mota"];
				}
				
				// Kalitate-maila ez badago arrayan gehitu
				if (in_array($e["kalitatea"], $eztabaida->bideo_kalitateak) === false) {
					$eztabaida->bideo_kalitateak[] = $e["kalitatea"];
				}
			}
			
			$eztabaida->bideo_motak = implode(",", $eztabaida->bideo_motak);
			*/
			
			// Cargamos la plantilla
			require ("inc/bistak/eztabaida/eztabaida.txa.php");
		}/*else{
			// Cargamos la plantilla
			require ("inc/bistak/eztabaida/eztabaidak.txa.php");
		}*/
	}
?>
