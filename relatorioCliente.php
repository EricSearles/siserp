<?php
session_start();
if(!empty($_SESSION['id'])){
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Cliente.php';
        require_once 'classes/Colaborador.php';
        require_once 'classes/Pagamento.php';
        
        $objColaborador = new Colaborador();
        $objOS = new OrdemServico();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        $objPagamento = new Pagamento();
        $objCliente = new Cliente();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Relatório Cliente - MVArquitetura</title>
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
            <th>Cliente:</th>
        </tr>
    <form name="busca" method="POST" action="" enctype=""  autocomplete="off" class="form-inline my-2 my-lg-0">
        <tr>  
            <td>
                    <input type="text" name="cliente" id="search-cliente" placeholder="Cliente" autocomplete="off" required="required" size="20"/>
                    <input type="hidden" name="idCliente" id="search-idcliente" />
                    <div id="suggesstion-cliente"></div>  
            </td>
        </tr>
        <tr>
            <td>
                Dê: <br/><input type="date" name="inicio" />
            </td>
        </tr>
        <tr>
            <td>
                Até: <br/><input type="date" name="fim" />
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
 <?php
    if(isset($_POST['btnBusca'])&& $_POST['btnBusca']=='Buscar'){
        // pega nome e id do cliente
        if(empty($_POST['cliente'])){
            echo 'Preencha o nome do cliente';
        }else{
        $cliente = $_POST['cliente'];
        $idCliente = $_POST['idCliente'];
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
 echo '<br/>Cliente: <strong>'.$cliente.'</strong><br/>
       Período do Relatorio:<br/>
       de <strong>'.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</strong><br/><hr>';  
 
    foreach($objCliente->querySelecionaRelatorio ($idCliente, $dInicio, $dFim) as $OS) {
        echo '<strong>Dados da Ordem de Serviço</strong><br/>';
        echo '<strong>Nº OS - '.$OS->idOrdemServico.'</strong><br/>';
        echo 'Data abertura - '.$objFuncao->formataData($OS->data).'<br/>';
        echo 'Solicitante - '.$OS->nomeColaborador.'<br/>';
        echo 'Valor - R$ '.$objFuncao->SubstituiPonto($OS->total).'<br/>';
        echo 'Dados do pagamento da OS<br/>';
       
        foreach($objPagamento->querySelecionaRelatorioPagamento($OS->idOrdemServico) as $parcela){
            echo 'Parcela : '.$parcela->parcela.'  <strong>|</strong>  ';
            echo 'Valor : R$ '.$objFuncao->SubstituiPonto($parcela->valor).'  <strong>|</strong>  ';
            echo 'Vencimento : '.$objFuncao->formataData($parcela->dataVencimento).'  <strong>|</strong>  ';
            echo 'Situação : '.$parcela->situacao.'<br/><hr>';         
        }
    }
 
 $totalOS = $objCliente->querySelecionaRelatorio ($idCliente, $dInicio, $dFim);  
    $numCampo = count($totalOS);
    $total = 0;
     for($i=0; $i< $numCampo; $i++){
     $total =  $total + ($totalOS[$i]->total);
    }
    
    $totalPago = 0;
    $totalPendente = 0;
    foreach($objCliente->querySelecionaRelatorio ($idCliente, $dInicio, $dFim) as $OS) {
            $pagos = $objPagamento->querySelecionaPagamentoRealizadoPeriodo($OS->idOrdemServico, $dInicio, $dFim);

            $numPago = count($pagos);// conta o total de OS com status pagamento  pendente
            //echo '<br/>Dados numCampo( total de OS com status pagamento colaborador pendente) '.$numCampo.'<br/>';
            // pega o valor do pagamento para o colaborador(tabela pagamentocolaborador) por OS e soma para dar o valor total de pendencias no intervalo de data selecionado  
            for($i=0; $i< $numPago; $i++){
             $totalPago =  $totalPago + ($pagos[$i]->valor);

            }  

            $pendentes = $objPagamento->querySelecionaPagamentoPendentePeriodo($OS->idOrdemServico, $dInicio, $dFim);

            $numPendente = count($pendentes);// conta o total de OS com status pagamento colaborador pendente
            //echo '<br/>Dados numCampo( total de OS com status pagamento colaborador pendente) '.$numCampo.'<br/>';

            // pega o valor do pagamento para o colaborador(tabela pagamentocolaborador) por OS e soma para dar o valor total de pendencias no intervalo de data selecionado  
            for($i=0; $i< $numPendente; $i++){
             $totalPendente =  $totalPendente + ($pendentes[$i]->valor);

            }
    }
            
    echo '<br/><strong>Valor Total Ordem Serviço no Período: R$ '.$objFuncao->SubstituiPonto($total).'</strong><br/>';
    
    echo '<br/><strong>Total Recebido no período: <strong>R$ '.$objFuncao->SubstituiPonto($totalPago).'</strong><br/>';
    
    echo '<br/><strong>Total a receber no período: <strong>R$ '.$objFuncao->SubstituiPonto($totalPendente).'</strong><br/>';
    
    echo '<br/><strong>Total em aberto: <strong>R$ '.$objFuncao->SubstituiPonto($total).'</strong><br/>';
    
        }

    }
 ?>
       
<hr>
    </div>
      
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

