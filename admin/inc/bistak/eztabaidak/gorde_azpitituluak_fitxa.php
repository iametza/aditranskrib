<?php
    $url_base = URL_BASE . "eztabaidak/";
    
    define ("AZPITITULUEN_PATH", "azpitituluak/eztabaidak/");

    // Orrikapena ("p") eta eztabaidaren id-a ("edit_id") eskuratu, egin beharreko eragiketak bukatutakoan erabiltzailea berriz ere dagokion lekura berbideratzeko.
    $p = isset ($_POST["p"]) ? (int) $_POST["p"] : 1;
    $edit_id = isset ($_POST["edit_id"]) ? (int) $_POST["edit_id"] : 0;
    
    // Bukaeran erabiltzailea berdideratzeko url_param prestatu.
    $url_param = "form?p=$p&edit_id=$edit_id";
    
    $azpitituluak_non = isset($_POST["azpitituluak_non"]) ? testu_formatua_sql($_POST["azpitituluak_non"]) : "0";
    
    $sql = "UPDATE eztabaidak SET azpitituluak_non = '$azpitituluak_non' WHERE id = '$edit_id'";
    
    $dbo->query ($sql) or die ($dbo->ShowError ());
   
    foreach (hizkuntza_idak() as $h_id) {
        $azpitituluak = fitxategia_igo("azpitituluak_" . $h_id, "../" . AZPITITULUEN_PATH);
        
        if (trim ($azpitituluak) != "") {
            $path_azpitituluak = fitxategia_path_hizkuntza("eztabaidak", "path_azpitituluak", $edit_id, $h_id);
            fitxategia_ezabatu_hizkuntza("eztabaidak_hizkuntzak", "azpitituluak", $edit_id, $h_id, "../" . $path_azpitituluak);
            
            $sql = "UPDATE eztabaidak_hizkuntzak
                    SET azpitituluak = '$azpitituluak', path_azpitituluak = '" . AZPITITULUEN_PATH . "'
                    WHERE fk_elem = '$edit_id' AND fk_hizkuntza='$h_id'";
            $dbo->query ($sql) or die ($dbo->ShowError ());
        }
        
        $azpitituluak_bistaratu = isset($_POST["azpitituluak_bistaratu_" . $h_id]) ? testu_formatua_sql($_POST["azpitituluak_bistaratu_" . $h_id]) : "0";
        $azpitituluak_botoia = isset($_POST["azpitituluak_botoia_" . $h_id]) ? testu_formatua_sql($_POST["azpitituluak_botoia_" . $h_id]) : "0";
        
        $sql = "UPDATE eztabaidak_hizkuntzak
                SET azpitituluak_bistaratu = '$azpitituluak_bistaratu', azpitituluak_botoia = '$azpitituluak_botoia'
                WHERE fk_elem = '$edit_id' AND fk_hizkuntza='$h_id'";
        $dbo->query ($sql) or die ($dbo->ShowError ());
    }
    
    // Erabiltzailea dagokion orrira berbideratu.
    header ("Location: " . $url_base . $url_param);
    
    exit();
?>