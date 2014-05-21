<?php
    $h_id = isset($_POST["h_id"]) ? (int) $_POST["h_id"] : 0;

    $erantzuna = new stdClass();
    
    $erantzuna->motak = array();
    
    if ($h_id > 0) {
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
    
    echo json_encode($erantzuna);
    
    exit();
?>