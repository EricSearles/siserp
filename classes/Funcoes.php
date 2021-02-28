<?php

class Funcoes {
    
    public function tratarCaracter($vlr, $tipo){
	switch($tipo){
            case 1: $rst = utf8_decode($vlr); break;
            case 2: $rst = utf8_encode($vlr); break;    
            case 3: $rst = htmlentities($vlr, ENT_QUOTES, "ISO-8859-1"); break; 
        }
        return $rst;
    }	
    
    public function dataAtual($tipo){
	switch($tipo){
            case 1: $rst = date("Y-m-d"); break;
            case 2: $rst = date("Y-m-d H:i:s"); break;
            case 3: $rst = date("d/m/Y"); break;
        }
        return $rst;
    }
    
    public function formataData($dado){
    $formata = date('d/m/Y', strtotime($dado));
    return $formata;
    }
    
    public function formataDataBD($dado){
    $formata = date('Y-m-d', strtotime($dado));
    return $formata;
    }
    
    public function SubstituiPonto($dado){
     $substitui = str_replace('.', ',', $dado);
     return $substitui;
    }
        public function SubstituiVirgula($dado){
     $substitui = str_replace(',', '.', $dado);
     return $substitui;
    }
    
    public function base64($vlr, $tipo){
        switch($tipo){
            case 1: $rst = base64_encode($vlr); break;
            case 2: $rst = base64_decode($vlr); break;
        }
        return $rst;
    }
    
    function mask($val, $mask){
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++){
 if($mask[$i] == '#'){
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }else{
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}

function addMonthIntoDate($date) {
     $this_year = substr ( $date, 0, 4 );
     $this_month = substr ( $date, 5, 2 );
     $this_day =  substr ( $date, 8, 2 );
     $next_date = mktime ( 0, 0, 0, $this_month + 1, $this_day, $this_year );
     return strftime("%Y-%m-%d", $next_date);
}

public function inicioMes (){
$hoje = date('Y-m-d');
$dataAtual = explode("-",$hoje);
$diaAtual = $dataAtual[2];
$mesAtual = $dataAtual[1];
$anoAtual = $dataAtual[0];
$calculaInicio = $diaAtual - 1;
$diaInicio = $diaAtual - $calculaInicio;
$dataInicio = $diaInicio.'-'.$mesAtual.'-'.$anoAtual;
return date('Y-m-d', strtotime($dataInicio));
    
}

public function fimMes (){
$hoje = date('Y-m-d');
$dataAtual = explode("-",$hoje);
$diaAtual = $dataAtual[2];
$mesAtual = $dataAtual[1];
$anoAtual = $dataAtual[0];
    if($mesAtual == '2'){
       $calculaFim = 28 - $diaAtual; 
    }else{
       $calculaFim = 30 - $diaAtual;
    }
$diaFinal = $diaAtual + $calculaFim;
$dataFinal = $diaFinal.'-'.$mesAtual.'-'.$anoAtual;
return date('Y-m-d', strtotime($dataFinal));
    
}

public function inicioMesAnterior (){
$hoje = date('Y-m-d');
$dataAtual = explode("-",$hoje);
$diaAtual = $dataAtual[2];
$mesAtual = $dataAtual[1];
$anoAtual = $dataAtual[0];
if($mesAtual==1){
    $mesAnterior = '12';
    $anoAtual = $anoAtual - 1;
}else{
$mesAnterior = $mesAtual - 1;
}
$calculaInicio = $diaAtual - 1;
$diaInicio = $diaAtual - $calculaInicio;
$dataInicio = $diaInicio.'-'.$mesAnterior.'-'.$anoAtual;
return date('Y-m-d', strtotime($dataInicio));
    
}

public function fimMesAnterior (){
$hoje = date('Y-m-d');
$dataAtual = explode("-",$hoje);
$diaAtual = $dataAtual[2];
$mesAtual = $dataAtual[1];
$anoAtual = $dataAtual[0];

if($mesAtual==1){
    $mesAnterior = '12';
    $anoAtual = $anoAtual - 1;
}else{
$mesAnterior = $mesAtual - 1;
}
    if($mesAnterior == '2'){
       $calculaFim = 28 - $diaAtual; 
    }else{
       $calculaFim = 31 - $diaAtual;
    }
$diaFinal = $diaAtual + $calculaFim;
$dataFinal = $diaFinal.'-'.$mesAnterior.'-'.$anoAtual;
return date('Y-m-d', strtotime($dataFinal));
    
}

}

?>
