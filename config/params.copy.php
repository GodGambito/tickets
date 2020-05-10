<?php
setlocale(LC_TIME, "Spanish_Chile");
$meses = array(1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE');
$pass = "";
$mes = date('n'); 
$ano = date('Y');    
if ($mes == "1"){$mesant = 12; $anoant = $ano -1;}else{$mesant = $mes-1;$anoant = $ano;};
$amd = date('Ymd');  
$pass2 = "";
$rutafinal = 'DKA200:[OPERINP]';
//$rutafinal = 'DKA0:[PBENCINI]';

return [
    'adminEmail' => '*******@*****.***',
    'senderEmail' => '*******@*****.***',
    'senderName' => '*******************',
    'ip_servidor' => '***************',    
    'rutaserver'=> $rutafinal,
    'user'=>'*************',
    'pass'=>$pass, 
    'pass2'=>$pass2,
    'rutared'=>'//PATRICIODONAIRE/o0055ticket/Entrada/',
    'rutalocal'=>'C:/Tickets/',
    'rutared2'=>'smb://patriciodonaire/o0055ticket/ENTRADA/',
    'amd'=>$amd,
    'mesactual'=>$meses[$mes],
];
