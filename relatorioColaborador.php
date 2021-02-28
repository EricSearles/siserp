<?php
session_start();
if(!empty($_SESSION['id'])){
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Colaborador.php';
        
        $objColaborador = new Colaborador();
        $objOS = new OrdemServico();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Relatório Colaborador - MVArquitetura</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="css/offcanvas.css" rel="stylesheet">
        <link href="css/sticky-footer.css" rel="stylesheet">

        
</head>
<?php include 'pesquisa.php'; ?>
<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->
    
<main role="main" class="container">
<div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
<?php   echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';?>
</div>  
    
    <div class="my-3 p-3 bg-white rounded shadow-sm">
    <table cellspacing="4" cellpadding="6">
        <tr>
            <th>Solicitante:</th>
<!--            <th>Período:</th>-->
        </tr>
    <form name="busca" method="POST" action="" enctype=""  autocomplete="off" class="form-inline my-2 my-lg-0">
        <tr>  
            <td>
                    <input type="text" name="colaborador" id="search-colaborador" placeholder="Solicitante" size="20" autocomplete="off" required="required" class=" form-control"/>
                    <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                    <div id="suggesstion-colaborador"></div>       
            </td>
        </tr>
        <tr>
            <td>
                Dê: <br/><input type="date" name="inicio" class=" form-control"/>
            </td>
        </tr>
        <tr>
            <td>
                Até: <br/><input type="date" name="fim" class=" form-control"/>
            </td>
        </tr>


<td><input type="submit" name="btnBusca" value="Buscar" class="btn btn-outline-success my-2 my-sm-0"</td></tr>

    </form>   
    </table>
<br/><h6 class="border-bottom border-gray pb-2 mb-0">Relatório
</h6>
    <div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-auto">
        <br/><br/>
<!--SELECT * FROM tabela_x WHERE data_operacao BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE();-->
 <?php
    if(isset($_POST['btnBusca'])&& $_POST['btnBusca']=='Buscar'){
        // pega nome e id do colaborador
        if(empty($_POST['colaborador'])){
            echo 'Preencha o nome do colaborador';
        }else{
        $colaborador = $_POST['colaborador'];
        $idColaborador = $_POST['idColaborador'];
// print_r($_POST);
 //define o intervalo de data do relatorio
 // caso a data não seja selecinada, ele faz de 01 a 30 do mês corrente
 if($_POST['inicio']==""){
$dInicio = $objFuncao->inicioMes();
 }else{
$dInicio = $_POST['inicio'];
 }
 if($_POST['fim']==""){
$dFim = $objFuncao->fimMes();
 }else{
$dFim = $_POST['fim'];
 }

 echo '<br/>Colaborador: <strong>'.$colaborador.'</strong><br/>
       Período do Relatorio: de <strong>'.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</strong><br/>';  
    
    $mesAtual = $objColaborador->queryRelatorioPagamentoAReceber($idColaborador, $dInicio, $dFim);

    //print_r($mesAtual);
    
    $numCampo = count($mesAtual);// conta o total de OS com status pagamento colaborador pendente
    //echo 'Dados numCampo( total de OS com status pagamento colaborador pendente) '.$numCampo.'<br/>';
    $total = 0;
    // pega o valor do pagamento para o colaborador(tabela pagamentocolaborador) por OS e soma para dar o valor total de pendencias no intervalo de data selecionado  
    for($i=0; $i< $numCampo; $i++){
     $totalReceber =  $totalReceber + ($mesAtual[$i]->valor);
    }
    
  echo 'Total a receber: R$ <strong>'.$objFuncao->SubstituiPonto($totalReceber).'</strong><br/>';
   //seleciona o intervalo de  datas de 01 a 30 do mês anterior
//    $inicioMesAnterior = $objFuncao->inicioMesAnterior();
 //   $fimMesAnterior = $objFuncao->fimMesAnterior();  
    
    
if($_POST['inicio']==""){
$inicioMesAnterior = $objFuncao->inicioMesAnterior();
 }else{
$inicioMesAnterior = $_POST['inicio'];
 }
 if($_POST['fim']==""){
    $fimMesAnterior = $objFuncao->fimMesAnterior(); 
 }else{
$fimMesAnterior = $_POST['fim'];
 }
    
    
    
 // vale as mesmas observações feitas para pagamento pendente
    $mesAnterior = $objColaborador->queryRelatorioPagamentoRecebido($idColaborador, $inicioMesAnterior, $fimMesAnterior);
    
   // var_dump($mesAnterior);
    $numCampo= count($mesAnterior);
    //echo 'Dados numCampo( total de OS com status pagamento colaborador pago) '.$numCampo.'<br/>';
    $total = 0;
    for($i=0; $i< $numCampo; $i++){
     $total =  $total + ($mesAnterior[$i]->valor);
    }
    
    if($_POST['inicio']==""){
  echo 'Total recebido no mês anterior: R$ <strong>'.$objFuncao->SubstituiPonto($total).'</strong><br/><br/><hr>';
    }else{
        echo 'Total recebido: R$ <strong>'.$objFuncao->SubstituiPonto($total).'</strong><br/><br/><hr>';
    }
   echo'
        <table class="table table-sm">
        <thead>
        <tr class="table-success" align="center">
        <th colspan="8">Pagamentos a receber '.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</td>
        </tr>
        <tr align="center">
          <th scope="col">Nº OS</th>
          <th scope="col">Data OS</th>
          <th scope="col">Valor OS</th>
          <th scope="col">Valor a receber</th>
          <th scope="col">Parcela</th>
          <th scope="col">Vencimento</th>
          <th scope="col">Cliente</th>
          <th scope="col">Responsavel pela OS</th>
        </tr>
        </thead>
       '; 
    foreach ($mesAtual as $relatorioAtual) {
        echo'
          <tr align="center">
          <td scope="col">'.$relatorioAtual->idOrdemServico.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAtual->data).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAtual->total).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAtual->valor).'</td>
          <td scope="col">'.$relatorioAtual->parcela.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAtual->dataVencimento).'</td>
          <td scope="col">'.$relatorioAtual->nomeCliente.'</td>
          <td scope="col">'.$relatorioAtual->nomeUsuario.'</td>
        </tr>
         ';       
    }
    
     echo'
        <table class="table table-sm">
        <thead>
        <tr class="table-success" align="center">
        <th colspan="8">Pagamentos recebidos '.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</td>
        </tr>
        <tr align="center">
          <th scope="col">Nº OS</th>
          <th scope="col">Data OS</th>
          <th scope="col">Valor OS</th>
          <th scope="col">Valor Pago</th>
          <th scope="col">Vencimento</th>
          <th scope="col">Cliente</th>
          <th scope="col">Responsavel pela OS</th>
        </tr>
        </thead>
       '; 
    foreach ($mesAnterior as $relatorioAnterior) {
        echo'
          <tr align="center">
          <td scope="col">'.$relatorioAnterior->idOrdemServico.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAnterior->data).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAnterior->total).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAnterior->valor).'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAnterior->dataVencimento).'</td>    
          <td scope="col">'.$relatorioAnterior->nomeCliente.'</td>
          <td scope="col">'.$relatorioAnterior->nomeUsuario.'</td>
        </tr>
         '; 
    }
  
            echo'

      </table>
      
      '; 

$colaborador;
$periodoInicio       = $objFuncao->formataData($dInicio);
$periodoFinal        = $objFuncao->formataData($dFim);
$areceber            = $totalReceber;
$recebidoMesAnterior = $objFuncao->SubstituiPonto($total);
$recebido            = $objFuncao->SubstituiPonto($total);
$idColaborador; 
$dInicio; 
$dFim;
$inicioMesAnterior;
$fimMesAnterior;
      
      
        }


  echo'     

<hr>
<table>
            <form method="POST" action="relatorioColaboradorPDF.php" target="_blank class="form-signin">   
<tr>
    <td>
        <input type="hidden" name="status" value="1" >
        <input type="hidden" name="idColaborador" value="'.$idColaborador.'" />
        <input type="hidden" name="colaborador" value="'.$colaborador.'" />
        <input type="hidden" name="inicio" value="'.$dInicio.'"/>
        <input type="hidden" name="fim" value="'.$dFim.'"/>
        <input type="hidden" name="inicioAnterior" value="'.$inicioMesAnterior.'"/>
        <input type="hidden" name="fimAnterior" value="'.$fimMesAnterior.'"/>
        <input type="hidden" name="areceber" value="'.$areceber.'"/>
        <input type="hidden" name="recebido" value="'.$recebido.'"/>
        <input type="hidden" name="recebidoAnterior" value="'.$recebidoMesAnterior.'"/>
        
        <input type="submit" name="btnCadastrar" value="Imprimir" class="btn btn-outline-success my-2 my-sm-0" />
        </form>
    </td>
</tr>
</table>
    </div>';

    }
 ?>   
      
  </div>
                          
<footer class="footer">
      <div class="container">
<!--        <span class="text-muted">Place sticky footer content here.</span>-->
      </div>
    </footer>          
<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 
<?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
?>
</body>
</html>

