<?php

	$url_base = URL_BASE . "eztabaidak/";

	define ("IRUDIEN_PATH", "img/eztabaidak/");
	define ("GRAFISMOEN_PATH", IRUDIEN_PATH . "grafismoak/");
	define ("MOMENTUEN_IRUDIEN_PATH", IRUDIEN_PATH . "momentuak/");
	
	$menu_aktibo = "eztabaidak";
	
	$p = isset ($_GET["p"]) ? (int) $_GET["p"] : 1;
	$url_param = "?p=$p";
	
	$hurrengoa = $url->hurrengoa();
	
	// Segundoak * 10 jaso eta hh:mm:ss formatura bihurtzen du.
	function egokituDenboraHHMMSSra($denbora) {
		$katea = "";
		$segundoak = $denbora / 10;
        
		// Zenbat ordu daude segundo horietan?
		$h = floor($segundoak / 3600);
        
		// Itzuliko dugun kateari orduak gehitu
		if ($h > 9) {
			$katea = $katea . $h . ":";
		} else {
			$katea = $katea . '0' . $h . ":";
		}
        
		// Ordu horiek kenduta geratzen diren segundoak
		$segundoak = $segundoak - $h * 3600;
		
		// Zenbat minutu daude geratzen diren segundoetan?
		$m = floor($segundoak / 60);
		
		// Itzuliko dugun kateari minutuak gehitu
		if ($m > 9) {
			$katea = $katea . $m . ':';
		} else {
			$katea = $katea . '0' . $m . ':';
		}
		
		// Minutu horiek kenduta geratzen diren segundoak
		$s = $segundoak - $m * 60;
		
		// Itzuliko dugun kateari segundoak gehitu
		if ($s > 9) {
			$katea = $katea . $s;
		} else {
			$katea = $katea . '0' . $s;
		}
		
		return $katea;
	}
	
	if ($hurrengoa == "form") {
		$edit_id = isset ($_GET["edit_id"]) ? (int) $_GET["edit_id"] : 0;
		
		// Eztabaida bat ezabatu behar da
		if (isset($_GET["ezab_id"])){
			$ezab_id = testu_formatua_sql($_GET["ezab_id"]);

			//Borramos los datos de los diferentes idiomas
			$sql = "DELETE FROM eztabaidak_hizkuntzak WHERE fk_elem='$ezab_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());

			//Borramos el elemento
			$sql = "DELETE FROM eztabaidak WHERE id='$ezab_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());

			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Hizlari bat ezabatu behar da
		if (isset($_GET["ezab_hizlaria_id"])) {
			$ezab_hizlaria_id = testu_formatua_sql($_GET["ezab_hizlaria_id"]);
			
			// Hizlariaren grafismoaren irudia ezabatu fitxategi-sistematik
			$path_hizlaria_grafismoa_irudia = fitxategia_path("eztabaidak_hizlariak", "path_grafismoa_irudia", $ezab_hizlaria_id);
			fitxategia_ezabatu("eztabaidak_hizlariak", "grafismoa_irudia", $ezab_hizlaria_id, "../" . $path_hizlaria_grafismoa_irudia);
			
			//Borramos los datos de los diferentes idiomas
			$sql = "DELETE FROM eztabaidak_hizlariak_hizkuntzak WHERE fk_elem='$ezab_hizlaria_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			//Borramos el elemento
			$sql = "DELETE FROM eztabaidak_hizlariak WHERE id='$ezab_hizlaria_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}

		// Momentu bat ezabatu behar da
		if (isset($_GET["ezab_momentua_id"])) {
			$ezab_momentua_id = testu_formatua_sql($_GET["ezab_momentua_id"]);
			
			// Momentuaren irudia ezabatu fitxategi-sistematik
			$path_momentua_irudia = fitxategia_path("eztabaidak_momentuak", "path_irudia", $ezab_momentua_id);
			fitxategia_ezabatu("eztabaidak_momentuak", "irudia", $ezab_momentua_id, "../" . $path_momentua_irudia);
			
			//Borramos los datos de los diferentes idiomas
			$sql = "DELETE FROM eztabaidak_momentuak_hizkuntzak WHERE fk_elem='$ezab_momentua_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			//Borramos el elemento
			$sql = "DELETE FROM eztabaidak_momentuak WHERE id='$ezab_momentua_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Grafismo bat ezabatu behar da
		if (isset($_GET["ezab_grafismoa_id"])) {
			$ezab_grafismoa_id = testu_formatua_sql($_GET["ezab_grafismoa_id"]);
			
			// Grafismoaren errenkada ezabatuko dugu.
			$sql = "DELETE FROM eztabaidak_grafismoak WHERE id='$ezab_grafismoa_id'";
			$dbo->query($sql) or die ($dbo->ShowError());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Infoesteka grafismo bat ezabatu behar da.
		if (isset($_GET["ezab_infoesteka_grafismoa_id"])) {
			$ezab_infoesteka_grafismoa_id = testu_formatua_sql($_GET["ezab_infoesteka_grafismoa_id"]);
			
			// Infoesteka grafismoaren hizkuntza desberdinetako balioak ezabatzen ditugu.
			$sql = "DELETE FROM eztabaidak_infoak_hizkuntzak WHERE fk_elem='$ezab_infoesteka_grafismoa_id'";
			$dbo->query($sql) or die($dbo->ShowError());
			
			// Infoesteka grafismoaren errenkada ezabatuko dugu.
			$sql = "DELETE FROM eztabaidak_infoak WHERE id='$ezab_infoesteka_grafismoa_id'";
			$dbo->query($sql) or die ($dbo->ShowError());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			// Berbideratu.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Bideo bat ezabatu behar da
		/*if (isset($_GET["ezab_bideoa_id"])) {
			$ezab_bideoa_id = testu_formatua_sql($_GET["ezab_bideoa_id"]);
			
			// Bideoa ezabatu fitxategi-sistematik
			$path_bideoa = fitxategia_path("eztabaidak_bideoak", "path", $ezab_bideoa_id);
			fitxategia_ezabatu("eztabaidak_bideoak", "bideoa", $ezab_bideoa_id, "../" . $path_bideoa);
			
			// Bideoa ezabatu DBtik
			$sql = "DELETE FROM eztabaidak_bideoak WHERE id='$ezab_bideoa_id'";
			$dbo->query ($sql) or die ($dbo->ShowError());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}*/
		
		// Momentu baten irudia ezabatu behar da baina ez momentua bera
		if (isset($_GET["ezab_momentua_irudia_id"])) {
			$ezab_momentua_irudia_id = testu_formatua_sql($_GET["ezab_momentua_irudia_id"]);
			
			// Irudia ezabatu fitxategi-sistematik
			$path_momentua_irudia = fitxategia_path("eztabaidak_momentuak", "path_irudia", $ezab_momentua_irudia_id);
			fitxategia_ezabatu("eztabaidak_momentuak", "irudia", $ezab_momentua_irudia_id, "../" . $path_momentua_irudia);
			
			// fitxategia_ezabatu funtzioak DBko irudia eremua garbitzen du baina path_irudia eremua ez:
			$sql = "UPDATE eztabaidak_momentuak SET path_irudia = '' WHERE id='$ezab_momentua_irudia_id'";
			$dbo->query($sql) or die($dbo->ShowError());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Hizlari baten grafismoaren irudia ezabatu behar da baina ez hizlaria bera
		if (isset($_GET["ezab_hizlaria_grafismoa_irudia_id"])) {
			
			$ezab_hizlaria_grafismoa_irudia_id = testu_formatua_sql($_GET["ezab_hizlaria_grafismoa_irudia_id"]);
			
			// Irudia ezabatu fitxategi-sistematik
			$path_hizlaria_grafismoa_irudia = fitxategia_path("eztabaidak_hizlariak", "path_grafismoa_irudia", $ezab_hizlaria_grafismoa_irudia_id);
			fitxategia_ezabatu("eztabaidak_hizlariak", "grafismoa_irudia", $ezab_hizlaria_grafismoa_irudia_id, "../" . $path_hizlaria_grafismoa_irudia);
			
			// fitxategia_ezabatu funtzioak DBko irudia eremua garbitzen du baina path_irudia eremua ez:
			$sql = "UPDATE eztabaidak_hizlariak SET path_grafismoa_irudia = '' WHERE id='$ezab_hizlaria_grafismoa_irudia_id'";
			$dbo->query($sql) or die($dbo->ShowError());
			
			// Berbideratu aurretik helbidea egokituko dugu.
			$url_base = $url_base . "form";
			$url_param = $url_param . "&edit_id=" . $edit_id;
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		// Inserciones o modificaciones
		if (isset ($_POST["gorde"])) {
			//Recogemos todos los datos del formulario.
			$edit_id = testu_formatua_sql($_POST["edit_id"]);
			$koloreak_gazta_testua = isset($_POST["koloreak_gazta_testua"]) ? testu_formatua_sql($_POST["koloreak_gazta_testua"]) : "";
			$koloreak_gazta_marra = isset($_POST["koloreak_gazta_marra"]) ? testu_formatua_sql($_POST["koloreak_gazta_marra"]) : "";
			$koloreak_barrak_testua = isset($_POST["koloreak_barrak_testua"]) ? testu_formatua_sql($_POST["koloreak_barrak_testua"]) : "";
			//$kalitate_lehenetsia = isset($_POST["bideoak_kalitateak"]) ? testu_formatua_sql($_POST["bideoak_kalitateak"]) : "";
			$url_bideoa = isset($_POST["url_bideoa"]) ? testu_formatua_sql($_POST["url_bideoa"]) : "";
			
			$zutabea_non = isset($_POST["zutabea_non"]) ? testu_formatua_sql($_POST["zutabea_non"]) : "1";
			$momenturik_onenak_bai = isset($_POST["momenturik_onenak_bai"]) ? testu_formatua_sql($_POST["momenturik_onenak_bai"]) : "0";
			$bilaketa_bai = isset($_POST["bilaketa_bai"]) ? testu_formatua_sql($_POST["bilaketa_bai"]) : "0";
			$gazta_bai = isset($_POST["gazta_bai"]) ? testu_formatua_sql($_POST["gazta_bai"]) : "0";
			$partekatu_bai = isset($_POST["partekatu_bai"]) ? testu_formatua_sql($_POST["partekatu_bai"]) : "0";
			$txertatu_bai = isset($_POST["txertatu_bai"]) ? testu_formatua_sql($_POST["txertatu_bai"]) : "0";
			$lizentzia_bai = isset($_POST["lizentzia_bai"]) ? testu_formatua_sql($_POST["lizentzia_bai"]) : "0";
			$barrak_bai = isset($_POST["barrak_bai"]) ? testu_formatua_sql($_POST["barrak_bai"]) : "0";
			
			$nice_name = nice_name_hizkuntzak ("eztabaidak", "izenburua", $edit_id);
			
			// Irudiak kargatu
			$posterra = fitxategia_igo("posterra", "../" . IRUDIEN_PATH);
			$lizentzia = fitxategia_igo("lizentzia", "../" . IRUDIEN_PATH);
			
			if (trim ($posterra) != ""){
				$path_posterra = fitxategia_path ("eztabaidak", "path_posterra", $edit_id);
				fitxategia_ezabatu("eztabaidak", "posterra", $edit_id, "../" . $path_posterra);
				
				$sql = "UPDATE eztabaidak SET posterra='$posterra', path_posterra='" . IRUDIEN_PATH . "' WHERE id='$edit_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			
			if (trim ($lizentzia) != ""){
				$path_lizentzia = fitxategia_path("eztabaidak", "path_lizentzia", $edit_id);
				fitxategia_ezabatu("eztabaidak", "lizentzia", $edit_id, "../" . $path_lizentzia);
				
				$sql = "UPDATE eztabaidak SET lizentzia = '$lizentzia', path_lizentzia = '" . IRUDIEN_PATH . "' WHERE id = '$edit_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			
			
			$sql = "UPDATE eztabaidak SET momenturik_onenak_bai = '$momenturik_onenak_bai', bilaketa_bai = '$bilaketa_bai', gazta_bai = '$gazta_bai', partekatu_bai = '$partekatu_bai', txertatu_bai = '$txertatu_bai', lizentzia_bai = '$lizentzia_bai', barrak_bai = '$barrak_bai', url_bideoa = '$url_bideoa', zutabea_non = '$zutabea_non', gazta_testu_kolorea = '$koloreak_gazta_testua', gazta_marra_kolorea = '$koloreak_gazta_marra', barrak_testu_kolorea = '$koloreak_barrak_testua' WHERE id = '$edit_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Guardamos los datos en cada idioma
			foreach (hizkuntza_idak() as $h_id) {
				$izenburua = testu_formatua_sql($_POST["izenburua_$h_id"]);
				$nice = $nice_name[$h_id];
				$hash_tag = testu_formatua_sql($_POST["hash_tag_$h_id"]);
				$fb_izenburua = testu_formatua_sql($_POST["fb_izenburua_$h_id"]);
				$bilaketa_kaxa_testua = testu_formatua_sql($_POST["bilaketa_kaxa_testua_$h_id"]);
				$url_lizentzia = testu_formatua_sql($_POST["url_lizentzia_$h_id"]);
				
				// Comprobamos si existe o no, para hacer una insercion o una modificacion
				$sql = "SELECT * FROM eztabaidak_hizkuntzak WHERE fk_elem='$edit_id' AND fk_hizkuntza='$h_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
				
				if ($dbo->emaitza_kopurua () == 0){
					
					$sql = "INSERT INTO eztabaidak_hizkuntzak (nice_name, izenburua, hash_tag, fb_izenburua, bilaketa_kaxa_testua, url_lizentzia,
							fk_elem, fk_hizkuntza)
							VALUES ('$nice', '$izenburua', '$hash_tag', '$fb_izenburua', '$bilaketa_kaxa_testua', '$url_lizentzia',
							'$edit_id', '$h_id')";
				} else {
					
					$sql = "UPDATE eztabaidak_hizkuntzak SET nice_name='$nice', izenburua='$izenburua', hash_tag='$hash_tag',
							fb_izenburua='$fb_izenburua', bilaketa_kaxa_testua='$bilaketa_kaxa_testua', url_lizentzia = '$url_lizentzia'
							WHERE fk_elem='$edit_id' AND fk_hizkuntza='$h_id'";
				}
				
				$dbo->query ($sql) or die ($dbo->ShowError ());
			}
			
			//Redireccionamos.
			header ("Location: " . $url_base . $url_param);
			exit;
		}
		
		if (isset ($_GET["ezabatu"])){
			switch ($_GET["ezabatu"]){
				case "POSTERRA":
					$path_posterra = fitxategia_path ("eztabaidak", "path_posterra", $edit_id);
					fitxategia_ezabatu ("eztabaidak", "posterra", $edit_id, "../" . $path_posterra);
					
					$mezua = "Posterra egokiro ezabatu da.";
					break;
				
				case "LIZENTZIA":
					$path_lizentzia = fitxategia_path ("eztabaidak", "path_lizentzia", $edit_id);
					fitxategia_ezabatu ("eztabaidak", "lizentzia", $edit_id, "../" . $path_lizentzia);
					
					$mezua = "Lizentziaren logoa egokiro ezabatu da.";
					break;
				
				case "AZPITITULUA":
					$path_azpititulua = fitxategia_path_hizkuntza("eztabaidak", "path_azpitituluak", $edit_id, $_GET["h_id"]);
					fitxategia_ezabatu_hizkuntza("eztabaidak_hizkuntzak", "azpitituluak", $edit_id, $_GET["h_id"], "../" . $path_azpititulua);
					
					$mezua = "Azpititulua egokiro ezabatu da.";
					break;
			}
		}
		
		// Eztabaidaren eztabaidak taulako balioak eskuratuko ditugu
		$sql = "SELECT url_bideoa, momenturik_onenak_bai, bilaketa_bai, gazta_bai, partekatu_bai, txertatu_bai, lizentzia_bai, barrak_bai, zutabea_non, azpitituluak_non,
				path_posterra, posterra, path_lizentzia, lizentzia, kalitate_lehenetsia, barrak_testu_kolorea, gazta_testu_kolorea, gazta_marra_kolorea
			    FROM eztabaidak
			    WHERE id = '$edit_id'";
		
		$dbo->query ($sql) or die ($dbo->ShowError());
		
		if ($dbo->emaitza_kopurua () == 1) {
			$row = $dbo->emaitza ();
			
			$eztabaida = new stdClass();
			$eztabaida->url_bideoa = $row["url_bideoa"];
			$eztabaida->momenturik_onenak_bai = $row["momenturik_onenak_bai"];
			$eztabaida->bilaketa_bai = $row["bilaketa_bai"];
			$eztabaida->gazta_bai = $row["gazta_bai"];
			$eztabaida->partekatu_bai = $row["partekatu_bai"];
			$eztabaida->txertatu_bai = $row["txertatu_bai"];
			$eztabaida->lizentzia_bai = $row["lizentzia_bai"];
			$eztabaida->barrak_bai = $row["barrak_bai"];
			$eztabaida->zutabea_non = $row["zutabea_non"];
			$eztabaida->azpitituluak_non = $row["azpitituluak_non"];
			$eztabaida->posterra = $row["path_posterra"] . $row["posterra"];
			$eztabaida->lizentzia = $row["path_lizentzia"] . $row["lizentzia"];
			
			$eztabaida->kalitate_lehenetsia = $row["kalitate_lehenetsia"];
			$eztabaida->barrak_testu_kolorea = $row["barrak_testu_kolorea"];
			$eztabaida->gazta_testu_kolorea = $row["gazta_testu_kolorea"];
			$eztabaida->gazta_marra_kolorea = $row["gazta_marra_kolorea"];
			
			$eztabaida->hizlariak = array();
			
			// TODO: Hizkuntza bakoitzean desberdinak izan daitezkeen datuak aparte gorde behar lirateke. Ez orain bezala hizkuntza bakarrarenak.
			$emaitza = get_query("SELECT A.id, A.kolorea, A.orden, B.izena, B.aurrizkia
								  FROM eztabaidak_hizlariak AS A
								  JOIN eztabaidak_hizlariak_hizkuntzak AS B ON A.id = B.fk_elem
								  WHERE A.fk_elem = '$edit_id'
								  ORDER BY orden");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$eztabaida->hizlariak[$emaitza[$i]["id"]] = new stdClass();
				
				$eztabaida->hizlariak[$emaitza[$i]["id"]]->id = $emaitza[$i]["id"];
				$eztabaida->hizlariak[$emaitza[$i]["id"]]->izena = $emaitza[$i]["izena"];
				$eztabaida->hizlariak[$emaitza[$i]["id"]]->aurrizkia = $emaitza[$i]["aurrizkia"];
				$eztabaida->hizlariak[$emaitza[$i]["id"]]->kolorea = $emaitza[$i]["kolorea"];
				$eztabaida->hizlariak[$emaitza[$i]["id"]]->orden = $emaitza[$i]["orden"];
			}
			
			/*
			 * Kalitate desberdinetako bideoen kodea iruzkindu egingo dut, ez baitugu momentuz behar
			 *
			 *
			
			// Kalitate guztien id-ak eskuratuko ditugu.
			$emaitza = get_query("SELECT id FROM eztabaidak_bideoak_kalitateak ORDER BY orden");
			
			// Kontuz! Ez nahastu $eztabaida->bideo_kalitateak-ekin.
			// Honetan kalitate guztiak daude. Bestean, eztabaida honetako bideoei dagozkienak bakarrik.
			$eztabaida->bideo_kalitate_guztien_idak = array();
			
			foreach ($emaitza as $e) {
				array_push($eztabaida->bideo_kalitate_guztien_idak, $e["id"]);
			}
			
			$emaitza = get_query("SELECT id, path, bideoa, mota, kalitatea FROM eztabaidak_bideoak WHERE fk_elem = " . $edit_id . " ORDER BY kalitatea");
			
			$eztabaida->bideoak = array();
			
			$eztabaida->bideoak[0] = array();
			$eztabaida->bideoak[1] = array();
			$eztabaida->bideoak[2] = array();
			$eztabaida->bideoak[3] = array();
			
			$eztabaida->bideo_motak = array();
			$eztabaida->bideo_kalitateak = array();
			
			foreach ($emaitza as $e) {
				$tmp_bideoa = new stdClass();
				
				$tmp_bideoa->id = $e["id"];
				$tmp_bideoa->path = $e["path"];
				$tmp_bideoa->bideoa = $e["bideoa"];
				$tmp_bideoa->mota = $e["mota"];
				$tmp_bideoa->kalitatea = $e["kalitatea"];
				
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
			*
			*
			*/
			
			$eztabaida->momentuak = array();
			
			$emaitza = get_query("SELECT A.id, A.path_irudia, A.irudia, A.orden, B.testua " .
				   "FROM eztabaidak_momentuak AS A " .
				   "JOIN eztabaidak_momentuak_hizkuntzak AS B ON A.id = B.fk_elem " .
				   "WHERE A.fk_elem = '$edit_id' " .
				   "ORDER BY orden");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$eztabaida->momentuak[$i] = new stdClass();
				
				$eztabaida->momentuak[$i]->id = $emaitza[$i]["id"];
				$eztabaida->momentuak[$i]->testua = $emaitza[$i]["testua"];
				$eztabaida->momentuak[$i]->path_irudia = $emaitza[$i]["path_irudia"];
				$eztabaida->momentuak[$i]->irudia = $emaitza[$i]["irudia"];
				$eztabaida->momentuak[$i]->orden = $emaitza[$i]["orden"];
			}
			
			$eztabaida->hizkuntzak = array();
			
			foreach (hizkuntza_idak() as $h_id) {
				$sql = "SELECT * FROM eztabaidak_hizkuntzak WHERE fk_elem = '$edit_id' AND fk_hizkuntza = '$h_id'";
				$dbo->query ($sql) or die ($dbo->ShowError ());
				
				$rowHizk = $dbo->emaitza();
				
				$eztabaida->hizkuntzak[$h_id] = new stdClass();
				$eztabaida->hizkuntzak[$h_id]->izenburua = $rowHizk["izenburua"];
				$eztabaida->hizkuntzak[$h_id]->hash_tag = $rowHizk["hash_tag"];
				$eztabaida->hizkuntzak[$h_id]->fb_izenburua = $rowHizk["fb_izenburua"];
				$eztabaida->hizkuntzak[$h_id]->bilaketa_kaxa_testua = $rowHizk["bilaketa_kaxa_testua"];
				$eztabaida->hizkuntzak[$h_id]->url_lizentzia = $rowHizk["url_lizentzia"];
				
				$eztabaida->hizkuntzak[$h_id]->azpitituluak = $rowHizk["path_azpitituluak"] . $rowHizk["azpitituluak"];
				$eztabaida->hizkuntzak[$h_id]->azpitituluak_botoia = $rowHizk["azpitituluak_botoia"];
				$eztabaida->hizkuntzak[$h_id]->azpitituluak_bistaratu = $rowHizk["azpitituluak_bistaratu"];
			}
			
			$eztabaida->grafismoak = array();
			
			$emaitza = get_query("SELECT A.id, A.hasiera_ms, A.amaiera_ms, A.fk_hizlaria, A.orden 
					FROM eztabaidak_grafismoak AS A
					WHERE A.fk_elem = '$edit_id'
					ORDER BY orden");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$eztabaida->grafismoak[$i] = new stdClass();
				
				$eztabaida->grafismoak[$i]->id = $emaitza[$i]["id"];
				$eztabaida->grafismoak[$i]->hasiera_ms = egokituDenboraHHMMSSra($emaitza[$i]["hasiera_ms"]);
				$eztabaida->grafismoak[$i]->amaiera_ms = egokituDenboraHHMMSSra($emaitza[$i]["amaiera_ms"]);
				$eztabaida->grafismoak[$i]->id_hizlaria = $emaitza[$i]["fk_hizlaria"];
				$eztabaida->grafismoak[$i]->orden = $emaitza[$i]["orden"];
			}
			
			$eztabaida->infoesteka_grafismoak = array();
			
			$emaitza = get_query("SELECT A.id, A.hasiera, A.amaiera, A.fk_info_mota
								 FROM eztabaidak_infoak AS A
								 WHERE A.fk_elem = '$edit_id'
								 ORDER BY hasiera");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$eztabaida->infoesteka_grafismoak[$i] = new stdClass();
				
				$eztabaida->infoesteka_grafismoak[$i]->id = $emaitza[$i]["id"];
				$eztabaida->infoesteka_grafismoak[$i]->hasiera_ms = egokituDenboraHHMMSSra($emaitza[$i]["hasiera"]);
				$eztabaida->infoesteka_grafismoak[$i]->amaiera_ms = egokituDenboraHHMMSSra($emaitza[$i]["amaiera"]);
				$eztabaida->infoesteka_grafismoak[$i]->id_mota = $emaitza[$i]["fk_info_mota"];
				
				$eztabaida->infoesteka_grafismoak[$i]->hizkuntzak = array();
				
				foreach (hizkuntza_idak() as $h_id) {
					$sql = "SELECT esteka
					        FROM eztabaidak_infoak_hizkuntzak
							WHERE fk_elem = '" . $emaitza[$i]["id"] . "' AND fk_hizkuntza = '$h_id'";
					
					$dbo->query($sql) or die($dbo->ShowError());
					
					$rowHizk = $dbo->emaitza();
					
					$eztabaida->infoesteka_grafismoak[$i]->hizkuntzak[$h_id] = new stdClass();
					$eztabaida->infoesteka_grafismoak[$i]->hizkuntzak[$h_id]->esteka = $rowHizk["esteka"];
				}
			}
			
			$eztabaida->infoesteka_motak = array();
			
			$emaitza = get_query("SELECT id, izena
								  FROM eztabaidak_infoak_motak");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$eztabaida->infoesteka_motak[$emaitza[$i]["id"]] = new stdClass();
				
				$eztabaida->infoesteka_motak[$emaitza[$i]["id"]]->id = $emaitza[$i]["id"];
				$eztabaida->infoesteka_motak[$emaitza[$i]["id"]]->izena = $emaitza[$i]["izena"];
			}
		}
		
		$content = "inc/bistak/eztabaidak/eztabaida.php";
		
	} else if ($hurrengoa == "editatu_hizlaria") {
		require("inc/bistak/eztabaidak/hizlaria.ajax.php");
	} else if ($hurrengoa == "gorde_azpitituluak_fitxa") {
		require("inc/bistak/eztabaidak/gorde_azpitituluak_fitxa.php");
	} else if ($hurrengoa == "editatu_momentua") {
		require("inc/bistak/eztabaidak/momentua.ajax.php");
	} else if ($hurrengoa == "gorde_laguntza") {
		require("inc/bistak/eztabaidak/gorde_laguntza.php");
	} else if ($hurrengoa == "editatu_grafismoa") {
		require("inc/bistak/eztabaidak/grafismoa.ajax.php");
	} else if ($hurrengoa == "editatu_infoesteka_grafismoa") {
		require("inc/bistak/eztabaidak/infoesteka_grafismoa.ajax.php");
	} else if ($hurrengoa == "itzuli_hizlariak") {
		require("inc/bistak/eztabaidak/itzuli_hizlariak.ajax.php");
	} else if ($hurrengoa == "itzuli_infoesteka") {
		require("inc/bistak/eztabaidak/itzuli_infoesteka.ajax.php");
	} else if ($hurrengoa == "itzuli_infoesteka_motak") {
		require("inc/bistak/eztabaidak/itzuli_infoesteka_motak.ajax.php");
	} else if ($hurrengoa == "gehitu_eztabaida") {
		require("inc/bistak/eztabaidak/gehitu_eztabaida.php");
	} else if ($hurrengoa == "laguntza") {
		
		$menu_aktibo = "eztabaidak-laguntza";
		
		$laguntza = new stdClass();
		
		$emaitza = get_query("SELECT testua
							  FROM eztabaidak_laguntza
							  WHERE gakoa = 'laguntza'");
			
		$laguntza->testua = $emaitza[0]["testua"];
		
		$content = "inc/bistak/eztabaidak/laguntza.php";
		
	} else if ($hurrengoa == "editatu-hipertranskribapena") {
		if (isset ($_POST["gorde"])) {
			$id_eztabaida = isset($_POST["id_eztabaida"]) ? (int) $_POST["id_eztabaida"] : 0;
			$id_hizkuntza = isset($_POST["id_hizkuntza"]) ? (int) $_POST["id_hizkuntza"] : 0;
			$hipertranskribapena = isset($_POST["hipertranskribapena"]) ? $_POST["hipertranskribapena"] : "";
			$tarteak = isset($_POST["tarteak"]) ? $_POST["tarteak"] : "";
			$parrafo_hasierak = isset($_POST["parrafo_hasierak"]) ? $_POST["parrafo_hasierak"] : "";
			
			// Hipertranskribapenaren testua eguneratu.
			$sql = "UPDATE eztabaidak_hizkuntzak " .
				   "SET hipertranskribapena_testua = '$hipertranskribapena' " .
				   "WHERE eztabaidak_hizkuntzak.fk_elem = '$id_eztabaida' " .
				   "AND eztabaidak_hizkuntzak.fk_hizkuntza = '$id_hizkuntza'";
			
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Tarte zaharrak ezabatuko ditugu lehenik.
			$sql = "DELETE FROM eztabaidak_tarteak " .
				   "WHERE fk_elem = " . $id_eztabaida . " AND fk_hizkuntza = " . $id_hizkuntza;
			
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Ondoren, erabiltzaileak sortutako tarteak gordeko ditugu.
			foreach($tarteak as $tartea) {
				$sql ="INSERT INTO eztabaidak_tarteak (indizea_hasiera, indizea_amaiera, fk_hizlaria, fk_elem, fk_hizkuntza) " .
					  "VALUES (" . $tartea['hasiera'] . ", " . $tartea['amaiera'] . ", " . $tartea['id_hizlaria'] . ", " . $id_eztabaida . ", " . $id_hizkuntza . ")";
				$dbo->query($sql) or die ($dbo->ShowError());
			}
			
			// Parrafo hasiera zaharrak ezabatuko ditugu lehenik.
			$sql = "DELETE FROM eztabaidak_parrafo_hasierak
					WHERE fk_elem = " . $id_eztabaida . " AND fk_hizkuntza = " . $id_hizkuntza;
			
			$dbo->query ($sql) or die ($dbo->ShowError ());
			
			// Ondoren, erabiltzaileak sortutako parrafo hasierak gordeko ditugu.
			foreach($parrafo_hasierak as $parrafo_hasiera) {
				$sql ="INSERT INTO eztabaidak_parrafo_hasierak (indizea_hasiera, fk_elem, fk_hizkuntza) " .
					  "VALUES (" . $parrafo_hasiera . ", " . $id_eztabaida . ", " . $id_hizkuntza . ")";
				
				$dbo->query($sql) or die ($dbo->ShowError());
			}
			
			$erantzuna = new stdClass();
			$erantzuna->arrakasta = true;
			
			echo json_encode($erantzuna);
			exit();
		} else {
			$editatu_hipertranskribapena = new stdClass();
			
			$editatu_hipertranskribapena->id_eztabaida = $_GET["edit_id"];
			$editatu_hipertranskribapena->eztabaidaren_izenburua = elementuaren_testua("eztabaidak", "izenburua", $_GET["edit_id"], $hizkuntzak["id"]);
			$editatu_hipertranskribapena->h_id = $_GET["h_id"];
			
			$editatu_hipertranskribapena->hizlariak = array();
			
			$emaitza = get_query("SELECT A.id, A.kolorea, A.orden, B.izena, B.aurrizkia " .
								 "FROM eztabaidak_hizlariak AS A " .
								 "JOIN eztabaidak_hizlariak_hizkuntzak AS B ON A.id = B.fk_elem " .
								 "WHERE A.fk_elem = '$editatu_hipertranskribapena->id_eztabaida' " .
								 "ORDER BY orden");
			
			for($i = 0; $i < count($emaitza); $i++) {
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]] = new stdClass();
				
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]]->id = $emaitza[$i]["id"];
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]]->izena = $emaitza[$i]["izena"];
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]]->aurrizkia = $emaitza[$i]["aurrizkia"];
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]]->orden = $emaitza[$i]["orden"];
				$editatu_hipertranskribapena->hizlariak[$emaitza[$i]["id"]]->kolorea = $emaitza[$i]["kolorea"];
			}
			
			$sql = "SELECT path_azpitituluak, azpitituluak FROM eztabaidak_hizkuntzak WHERE fk_elem = '$editatu_hipertranskribapena->id_eztabaida' AND fk_hizkuntza = '$editatu_hipertranskribapena->h_id'";
			$dbo->query ($sql) or die ($dbo->ShowError ());
					
			$rowHizk = $dbo->emaitza();
			
			$editatu_hipertranskribapena->azpitituluak = $rowHizk["path_azpitituluak"] . $rowHizk["azpitituluak"];
					
			if (is_file ("../" . $editatu_hipertranskribapena->azpitituluak)) {
				$editatu_hipertranskribapena->azpitituluak_testua = json_encode(file_get_contents("../" . $editatu_hipertranskribapena->azpitituluak));
			}
			
			// Transkribapeneko tarteak kargatu
			$emaitza = get_query("SELECT indizea_hasiera, indizea_amaiera, fk_hizlaria " .
								 "FROM eztabaidak_tarteak " .
								 "WHERE fk_elem = '" . $editatu_hipertranskribapena->id_eztabaida . "' AND fk_hizkuntza = '" . $editatu_hipertranskribapena->h_id . "'");
			
			$editatu_hipertranskribapena->tarteak = array();
			
			foreach ($emaitza as $e) {
				$tmp_tartea = new stdClass();
				$tmp_tartea->indizea_hasiera = $e["indizea_hasiera"];
				$tmp_tartea->indizea_amaiera = $e["indizea_amaiera"];
				$tmp_tartea->id_hizlaria = $e["fk_hizlaria"];
				
				array_push($editatu_hipertranskribapena->tarteak, $tmp_tartea);
			}
			
			// Transkribapeneko parrafo hasierak kargatu
			$emaitza = get_query("SELECT indizea_hasiera
								  FROM eztabaidak_parrafo_hasierak
								  WHERE fk_elem = '" . $editatu_hipertranskribapena->id_eztabaida . "' AND fk_hizkuntza = '" . $editatu_hipertranskribapena->h_id . "'");
			
			$editatu_hipertranskribapena->parrafo_hasierak = array();
			
			foreach ($emaitza as $e) {
				array_push($editatu_hipertranskribapena->parrafo_hasierak, $e["indizea_hasiera"]);
			}
			
			$content = "inc/bistak/eztabaidak/editatu_hipertranskribapena.php";
		}
	} else {
		
		if (isset ($_GET["oid_hizlaria"])){
			$id = $_GET["oid_hizlaria"];
			$bal = $_GET["bal"];
			$edit_id = $_GET["edit_id"];
			
			orden_automatiko ("eztabaidak_hizlariak", $id, $bal);
			
			header ("Location: " . $url_base . "form/" . $url_param . "&edit_id=" . $edit_id);
			exit;
		} else if (isset ($_GET["oid_momentua"])){
			$id = $_GET["oid_momentua"];
			$bal = $_GET["bal"];
			$edit_id = $_GET["edit_id"];
				  
			orden_automatiko ("eztabaidak_momentuak", $id, $bal);

			header ("Location: " . $url_base . "form/" . $url_param . "&edit_id=" . $edit_id);
			exit;
		} else if (isset ($_GET["oid_grafismoa"])){
			$id = $_GET["oid_grafismoa"];
			$bal = $_GET["bal"];
			$edit_id = $_GET["edit_id"];
			
			orden_automatiko ("eztabaidak_grafismoak", $id, $bal);
			
			header ("Location: " . $url_base . "form/" . $url_param . "&edit_id=" . $edit_id);
			exit;
		}
		
		// Erabiltzaileak eztabaida baten egoera aldatu badu (pribatutik publikora edo alderantziz).
		if(isset ($_GET["aldatu_egoera_id"])) {
			
			// Eztabaidaren id-a eskuratu.
			$id_eztabaida = $_GET["aldatu_egoera_id"];
			
			// Balio berria eskuratu.
			$bal = $_GET["bal"];
			
			// eztabaidaren egoeraren balio berria DBan gorde.
			$sql = "UPDATE eztabaidak
					SET publiko = '" . $bal . "'
					WHERE id = '" . $id_eztabaida . "'";
			
			$dbo->query($sql) or die($dbo->ShowError());
			
			// Dagokion orrira berbideratu.
			header("Location: " . $url_base . $url_param);
		}
		
		// Erabiltzaileak eztabaida baten data aldatu badu.
		if (isset($_GET["aldatu_data_id"])) {
			
			// Eztabaidaren id-a eskuratu.
			$id_eztabaida = $_GET["aldatu_data_id"];
			
			// Balio berria eskuratu.
			$data = $_GET["data"];
			
			// eztabaidaren dataren balio berria DBan gorde.
			$sql = "UPDATE eztabaidak
					SET data = '" . $data . "'
					WHERE id = '" . $id_eztabaida . "'";
			
			$dbo->query($sql) or die($dbo->ShowError());
			
			// Dagokion orrira berbideratu.
			header("Location: " . $url_base . $url_param);
		}
		
		$sql = "SELECT * FROM eztabaidak ORDER BY data DESC";
		
		$orrikapena = orrikapen_datuak ($sql, $p);
		$sql .= " LIMIT " . $orrikapena["limitInf"] . "," . $orrikapena["tamPag"];

		$elementuak = get_query ($sql);
		
		$content = "inc/bistak/eztabaidak/eztabaidak.php";
	}

?>
