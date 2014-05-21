<?php
    $url_base = URL_BASE . "eztabaidak/";
    
    // Orrikapena ("p") eta eztabaidaren id-a ("edit_id") eskuratu, egin beharreko eragiketak bukatutakoan erabiltzailea berriz ere dagokion lekura berbideratzeko.
    $p = isset ($_POST["p"]) ? (int) $_POST["p"] : 1;
    
    $url_bideoa = isset($_POST["gehitu-eztabaida-url-bideoa"]) ? testu_formatua_sql($_POST["gehitu-eztabaida-url-bideoa"]) : "";

    // Hasiera batean eztabaidak sortu den uneko data izango du eta pribatua izango da.
    $sql = "INSERT INTO eztabaidak (data, publiko, momenturik_onenak_bai, bilaketa_bai, gazta_bai, partekatu_bai, txertatu_bai,
            lizentzia_bai, barrak_bai, url_bideoa, zutabea_non, gazta_testu_kolorea, gazta_marra_kolorea, barrak_testu_kolorea)
            VALUES ('" . date("Y-m-d H:i:s") . "', '0', '0', '0', '0', '0', '0', '0', '0', '" . $url_bideoa . "', '0', '#000000', '#FFFFFF', '#FFFFFF')";
    $dbo->query($sql) or die($dbo->ShowError());
    
    // Recogemos el id recien creado
    $edit_id = db_taula_azken_id("eztabaidak");
    
    foreach (hizkuntza_idak() as $h_id) {
        
        $izenburua = isset($_POST["gehitu-eztabaida-izenburua_$h_id"]) ? testu_formatua_sql($_POST["gehitu-eztabaida-izenburua_$h_id"]) : "";
        $nice = isset($_POST["gehitu-eztabaida-izenburua_$h_id"]) ? sanitize_title_with_dashes(testu_formatua_sql($_POST["gehitu-eztabaida-izenburua_$h_id"])) : "";
        $sql = "INSERT INTO eztabaidak_hizkuntzak (nice_name, izenburua, hash_tag, fb_izenburua, bilaketa_kaxa_testua, url_lizentzia,
				fk_elem, fk_hizkuntza)
				VALUES ('$nice', '$izenburua', '', '', '', '', '$edit_id', '$h_id')";
        
        $dbo->query($sql) or die($dbo->ShowError());
    }
    
    // Erabiltzailea berdideratzeko url_param prestatu.
    $url_param = "form?p=$p&edit_id=$edit_id";
    
    // Erabiltzailea dagokion orrira berbideratu.
    header ("Location: " . $url_base . $url_param);
    
    exit();
?>