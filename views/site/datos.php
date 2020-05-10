<?php
    $contador=0;
    $ip_servidor="192.168.252.2";
    $rutaalpha = 'DKA200:[OPERINP]';
    $rutatemp="DKA0:[PBENCINI]";
    $rutaserver=$rutaalpha;
    $user="BASIC";
    setlocale(LC_TIME, "Spanish_Chile");
    $meses = array(1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE');
    $pass = trim(strtoupper(strftime("%B%G")));
    $mes = date('n'); $ano = date('Y');    
    if ($mes == "1"){$mesant = 12; $anoant = $ano -1;}else{$mesant = $mes-1;$anoant = $ano;};
    $amd = date('Ymd');    
    $pass2 = $meses[$mesant].$anoant;
    $rutared="//PATRICIODONAIRE/o0055ticket/Entrada/";
    $rutalocal="C:/Tickets/";
    $rutared2="smb://patriciodonaire/o0055ticket/ENTRADA/";
    $pageActual = $_SERVER['PHP_SELF'];
?>