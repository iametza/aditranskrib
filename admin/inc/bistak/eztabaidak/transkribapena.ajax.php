<?php
    // Erabiltzaileak transkribapen bat editatu eta gorde botoia sakatu badu.
    if (isset($_POST["gorde"])) {
        $id_eztabaida = isset($_POST["id_eztabaida"]) ? (int) $_POST["id_eztabaida"] : 0;
        $id_hizkuntza = isset($_POST["id_hizkuntza"]) ? (int) $_POST["id_hizkuntza"] : 0;
        $transkribapena = isset($_POST["transkribapena"]) ? $_POST["transkribapena"] : "";
        $tarteak = isset($_POST["tarteak"]) ? $_POST["tarteak"] : "";
        
        $sql = "UPDATE eztabaidak_momentuak " .
               "SET start_ms = '$start_ms', end_ms = '$end_ms', iraupena = '$iraupena' " .
               "WHERE id = '$id_momentua'";
        
        if ($dbo->query($sql)) {
            $tmp_testua = $testuak[$i]['testua'];
            $tmp_h_id = $testuak[$i]['h_id'];
            
            $sql = "UPDATE eztabaidak_hizkuntzak " .
                   "SET transkribapena_testua = '$transkribapena' " .
                   "WHERE eztabaidak_hizkuntzak.fk_elem = '$id_eztabaida' " .
                   "AND eztabaidak_hizkuntzak.fk_hizkuntza = '$id_hizkuntza'";
            
            if ($dbo->query($sql)) {
                // Tarte zaharrak ezabatuko ditugu lehenik
                $sql = "DELETE FROM eztabaidak_tarteak " .
                       "WHERE fk_elem = " . $id_eztabaida . " AND fk_hizkuntza = " . $id_hizkuntza;
                $dbo->query($sql);
                
                // Ondoren, erabiltzaileak sortutako tarteak gordeko ditugu
                foreach($tarteak as $tartea) {
                    $sql ="INSERT INTO eztabaidak_tarteak (indizea_hasiera, indizea_amaiera, fk_hizlaria, fk_elem, fk_hizkuntza) " .
                          "VALUES (" . $tartea['hasiera'] . ", " . $tartea['amaiera'] . ", " . $tartea['id_hizlaria'] . ", " . $id_eztabaida . ", " . $id_hizkuntza . ")";
                    if ($dbo->query($sql)) {
                        $erantzuna->arrakasta = true;
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
            $erantzuna->arrakasta = false;
            $erantzuna->mezua = $dbo->ShowError();
        }
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>