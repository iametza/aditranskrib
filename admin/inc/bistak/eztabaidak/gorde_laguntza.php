<?php
    $url_base = URL_BASE . "eztabaidak/laguntza";
    
    $laguntza_testua = isset($_POST["laguntza_testua"]) ? testu_formatua_sql($_POST["laguntza_testua"]) : "";
    $laguntza_momenturik_onenak = isset($_POST["laguntza_momenturik_onenak"]) ? testu_formatua_sql($_POST["laguntza_momenturik_onenak"]) : "";
    $laguntza_bilaketa = isset($_POST["laguntza_bilaketa"]) ? testu_formatua_sql($_POST["laguntza_bilaketa"]) : "";
    $laguntza_partekatu = isset($_POST["laguntza_partekatu"]) ? testu_formatua_sql($_POST["laguntza_partekatu"]) : "";
    
    $sql = "UPDATE eztabaidak_laguntza
            SET testua = '$laguntza_testua'
            WHERE gakoa='laguntza'";
    
    $dbo->query($sql) or die($dbo->ShowError());
    
    // Erabiltzailea dagokion orrira berbideratu.
    header ("Location: " . $url_base);
    
    exit();
?>