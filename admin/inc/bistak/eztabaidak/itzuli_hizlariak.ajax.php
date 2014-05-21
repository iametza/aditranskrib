<?php
    $id_eztabaida = isset($_POST["id_eztabaida"]) ? (int) $_POST["id_eztabaida"] : 0;
    $h_id = isset($_POST["h_id"]) ? (int) $_POST["h_id"] : 0;

    $erantzuna = new stdClass();
    
    $erantzuna->hizlariak = array();
    
    if ($id_eztabaida > 0 && $h_id > 0) {
        $sql = "SELECT A.id, B.izena FROM
                eztabaidak_hizlariak AS A, eztabaidak_hizlariak_hizkuntzak AS B
                WHERE A.id = B.fk_elem AND A.fk_elem = '$id_eztabaida' AND B.fk_hizkuntza = '$h_id'";
        
        $emaitza = get_query($sql);
        
        for($i = 0; $i < count($emaitza); $i++) {
            
            $tmp_hizlaria = new stdClass();
            
            $tmp_hizlaria->id = $emaitza[$i]["id"];
            $tmp_hizlaria->izena = $emaitza[$i]["izena"];
            
            array_push($erantzuna->hizlariak, $tmp_hizlaria);
        }
        
        $erantzuna->arrakasta = true;
    } else {
        $erantzuna->arrakasta = false;
    }
    
    echo json_encode($erantzuna);
    
    exit();
?>