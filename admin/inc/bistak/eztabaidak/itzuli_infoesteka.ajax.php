<?php

    $id_infoesteka = isset($_POST["id_infoesteka"]) ? (int) $_POST["id_infoesteka"] : 0;
    $h_id = isset($_POST["h_id"]) ? (int) $_POST["h_id"] : 0;

    $erantzuna = new stdClass();
    
    if ($id_infoesteka > 0 && $h_id > 0) {
        
        $sql = "SELECT A.hasiera, A.amaiera, A.fk_info_mota, B.izenburua, B.azalpena, B.esteka
                FROM eztabaidak_infoak AS A
                JOIN eztabaidak_infoak_hizkuntzak AS B
                ON A.id = B.fk_elem
                WHERE A.id = '$id_infoesteka' AND B.fk_hizkuntza = '$h_id'";
        
        $dbo->query($sql) or die($dbo->ShowError());
        
        if ($emaitza = $dbo->emaitza()) {
            
            // egokituDenboraHHMMSSra funtzioa admin/inc/bistak/eztabaidak/eztabaidak.load.php fitxategian dago.
            
            $erantzuna->hasiera = egokituDenboraHHMMSSra($emaitza["hasiera"]);
            $erantzuna->amaiera = egokituDenboraHHMMSSra($emaitza["amaiera"]);
            $erantzuna->id_infoesteka_mota = $emaitza["fk_info_mota"];
            $erantzuna->izenburua = $emaitza["izenburua"];
            $erantzuna->azalpena = $emaitza["azalpena"];
            $erantzuna->esteka = $emaitza["esteka"];
            
            $erantzuna->motak = array();
            
            $sql = "SELECT id, izena, path_irudia
                    FROM eztabaidak_infoak_motak";
        
            $emaitza = get_query($sql);
            
            for($i = 0; $i < count($emaitza); $i++) {
                
                $tmp_mota = new stdClass();
                
                $tmp_mota->id = $emaitza[$i]["id"];
                $tmp_mota->izena = $emaitza[$i]["izena"];
                $tmp_mota->path_irudia = $emaitza[$i]["path_irudia"];
                
                array_push($erantzuna->motak, $tmp_mota);
            }
            
            $erantzuna->arrakasta = true;
            
        } else {
            
            $erantzuna->arrakasta = false;
            
        }
        
    } else {
        
        $erantzuna->arrakasta = false;
        
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>