<?php 
session_start();
unset ($_SESSION['msg']);
unset($_SESSION['erroResponsavel']);
unset($_SESSION['erroSolicitante']);
unset($_SESSION['erroCliente']);

if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/ServicoDisponibilizado.php';
        require_once 'classes/Pagamento.php';
        
        $objOS = new OrdemServico();
        $objPagamento = new Pagamento();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        $objServico = new ServicoDisponibilizado();
        
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']== "Gerar Ordem de Serviço"){
        $dados = $_POST;

    if($dados['idUsuario'] < '1'){
        
        $_SESSION['erroResponsavel'] = "Você preencheu o nome do Responsável manualmente, <br/>ou pelas configurações automáticas do browser, <br/>por favor utilize a opção de preenchimento do sistema.";
        
            }else if(empty($dados['idColaborador'])){
                
                $_SESSION['erroSolicitante'] = "Você preencheu o nome do Colaborador manualmente, <br/>ou pelas configurações automáticas do browser, <br/>por favor utilize a opção de preenchimento do sistema.";
              
            }else if(empty($dados['idCliente'])){
                
        $_SESSION['erroCliente'] = "Você preencheu o nome do Cliente manualmente, <br/>ou pelas configurações automáticas do browser, <br/>por favor utilize a opção de preenchimento do sistema.";
      
    }else{
    
    //para pegar valores das arrays aninhadas       
    $servico = array($dados['servico']);   
    foreach ($servico as $serv) {

    } 
    $nDocumento = array($dados['nDocumento']);
    foreach ($nDocumento as $nDoc) {
        
    }
    $nProcesso = array($dados['nProcesso']);
    foreach ($nProcesso as $nProc) {
        
    }
    $agencia = array($dados['agencia']);
    foreach ($agencia as $ag) {
        
    }
    $obsOS = array($dados['obs']);
    foreach ($obsOS as $obs) {
        
    }
     $quantidade = array ($dados['qtd']);
    foreach ($quantidade as $qtd) {
      $numQtd = count($qtd);

    }
    
    $total = array ($dados['valorItem']);

    foreach ($total as $valor) {
        $numValor = count($valor);
//        //substitui virgulas por pontos
        $valorTotal = str_replace(',', '.', $valor);
    }
    //multiplica o valor pela quantidade do item
    for($i = 1; $i<= $numValor; $i++){
        $soma[$i] = $qtd[$i] * $valorTotal[$i];
    }
    //soma os valores da array
    $totalItem = array_sum($soma);
    
    $ultimoId = $objOS->queryInsertOS($dados, $totalItem); 
    if (empty($ultimoId)){
        echo 'Não foi possivel cadastrar a ordem de serviço, tente novamente ou entre em contato com o desenvolvedor';
    }else{
    //conta o tamanho da array para inserir na tabela itemordemservico
    $tamanho = count($valor);
    //monta a repetição para o insert
    for($i = 1;$i<=$tamanho;$i++){
        //cria a array item que será passada para função
    $itens = array('servico'=>$serv[$i],'nDocumento'=>$nDoc[$i],'nProcesso'=>$nProc[$i], 'agencia'=>$ag[$i], 'qtd'=>$qtd[$i],'valor'=>$valor[$i], 'obs'=>$obs[$i]);
        //substitui as virgulas por pontos
        $trataItens = str_replace(',', '.', $itens);       
        //insere dados na tabela itensorcamento
        $insere = $objOS->queryInsertItem($trataItens, $ultimoId);             
    } 
      if($insere == "ok"){

        if($dados['TipoPagamento']=='1'){

         //inserir tabela parcelamento
                $idOrdemServico = $ultimoId;
                $parcela = 'Única';
                $valorParcela = str_replace(',', '.', $dados['pagamento']);
                $vencimento = $dados['dataPagamento'];
                $status = $dados['statusPagamento'];

            $pgVista = $objPagamento->queryInsertPagamento($dados, $ultimoId, $parcela, $vencimento, $valorParcela);           
        }else{
          //inserir sinal na tabela parcelamento
                $idOrdemServico = $ultimoId;
                $parcela = 'Sinal';
                $valorParcela = str_replace(',', '.', $dados['entrada']);
                $vencimento = $dados['dataOrdemServico'];
                $status = $dados['statusPagamento'];
            $entrada = $objPagamento->queryInsertPagamento($dados, $ultimoId, $parcela, $vencimento, $valorParcela);
            if($entrada = 'ok'){
        //inserir parcelas na tabela parcelamento
        $qtdParcelas = $dados['parcela'];  
        $data = date('Y-m-d');
        $dataVencimento =  explode('-', $data);
        $dia = $dataVencimento['2'];       
        for($i = 1; $i<=$qtdParcelas; $i++){           
            $idOrdemServico = $ultimoId;
            $parcela   = $i;
            $valorParcela = str_replace(',', '.', $dados['valorParcela']);
                if($i=='1'){
                    $mesVencimento = $dataVencimento['1'] + 1;
                }else if($i > 1){
                    $mesVencimento = $dataVencimento['1'] + ($i);
                }
                if($mesVencimento <= 12){
                    $ano = $dataVencimento['0'];
                }else{
                    $ano = $dataVencimento['0'] + ($i-12);
                }
            $vencimento = $ano.'-'.$mesVencimento.'-'.$dia;
          $parcelas = $objPagamento->queryInsertPagamento($dados, $ultimoId, $parcela, $vencimento, $valorParcela);
            
        }    
 
    }

}
               
        $busca = $objFuncao->base64($ultimoId, 1);
        header("Location: imprimeOrdemServico.php?pg=&busca=$busca");
        }
    } 
    
    }
    
}
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){  
        $dados = $_POST;
        
        if($dados['idColaborador']==""){
            $dados['idColaborador'] = $dados['idcolaborador'];
        }
        if($dados['idUsuario']==""){
            $dados['idUsuario'] = $dados['idusuario'];
        }
        if($dados['idCliente']==""){
            $dados['idCliente'] = $dados['idcliente'];
        }     
           $upOS = $objOS->queryUpdateOS($dados);

           
             foreach($dados['servico'] as $id => $item){
               //aqui vai a funcao update item
              $upServico = $objOS->queryUpdateItemServico($id, $item); 
             }           

            foreach($dados['nDocumento'] as $id => $doc){
               //aqui vai a funcao update item
              $upDoc = $objOS->queryUpdateItemDocumento($id, $doc);
  
            } 
            
            foreach($dados['nProcesso'] as $id => $proc){
               //aqui vai a funcao update item
              $upProc = $objOS->queryUpdateItemProcesso($id, $proc);
  
            } 
            
            foreach($dados['agencia'] as $id => $ag){
               //aqui vai a funcao update item
              $upAgencia = $objOS->queryUpdateItemAgencia($id, $ag);

  
            }            
            
            foreach($dados['qtd'] as $id => $qtd){
               //aqui vai a funcao update item
              $upQTD = $objOS->queryUpdateItemQtd($id, $qtd);
  
            }  
            
            foreach($dados['valorItem'] as $id => $valor){
               //aqui vai a funcao update item
             $upValor = $objOS->queryUpdateItemValor($id, $valor);
  
            } 
                 
            foreach($dados['obs'] as $id => $obs){
               //aqui vai a funcao update item
              $upObs = $objOS->queryUpdateItemObs($id, $obs);
  
            } 
        
}



    if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){
         //busca = idOS
        if($objOS->queryDeleteOS($_GET['busca'])=='ok'){
            
           if($objOS->queryDeleteItemOS($_GET['busca'])){
              $_SESSION['msg'] = "OS deletada com sucesso.";
           }else{
               $_SESSION['msg'] = "Não foi possível deletar a OS.";
           }
        }else{
               $_SESSION['msg'] = "Não foi posível deletar o Item da OS.";
        }
               
    }

    if(isset($_GET['acao']) AND $_GET['acao'] == "limpar"){
        unset($_SESSION['msg']);
    }
        
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">       
    <link href="offcanvas.css" rel="stylesheet">   
    <title>Ordem de Serviço - MVArquitetura</title>
       
</head>
<script type="text/javascript">
function tipoPagamentoSel() {
  var pag = document.getElementById("opt-avista").checked;
  if (pag) {
    document.getElementById("avista").style.display = "block";
    document.getElementById("aprazo").style.display = "none";
  } else {
    document.getElementById("avista").style.display = "none";
    document.getElementById("aprazo").style.display = "block";
  }
}
</script> 

<script type="text/javascript">
function pagamento() {
  var pagamento = document.getElementById("opt-pagamento").onchange;
  if (pagamento) {
    document.getElementById("avista").style.display = "block";
    document.getElementById("aprazo").style.display = "none";
  } else {
    document.getElementById("avista").style.display = "none";
    document.getElementById("aprazo").style.display = "block";
  }
}
</script> 
<!--arquivo responsavel por preencher os campos, responsavel, solicitante, cliente-->
<?php include 'pesquisa.php'; ?>
<body>
<?php 
include 'menu.php';
?>

<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
  <?php   
    //Saudação da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>
</div>
<div class="my-3 p-3 bg-white rounded shadow-sm">
<table cellspacing="4" cellpadding="6" class="table-sm">
    <!--    <tr>
            <th>Buscar por:</th>
        </tr>
    <form name="busca" method="POST" action="" enctype=""  class="form-inline my-2 my-lg-0">
        <tr>  
            <td>
                    <input type="text" name="nDocumento" id="search-documento" placeholder="Nº Documento" size="20"/>
                    <input type="hidden" name="idOSDoc" id="search-idOrdemServico" />
                    <div id="suggesstion-documento">            
            </td> 
            <td>
                    <input type="text" name="nProcesso" id="search-processo" placeholder="Nº Processo" size="20"/>
                    <input type="hidden" name="idOSPro" id="search-idOS" />
                    <div id="suggesstion-processo">            
            </td> 
                        <td>
                    <input type="text" name="cliente" id="search-cliente" placeholder="Cliente" size="20"/>
                    <input type="hidden" name="idCliente" id="search-idcliente" />
                    <div id="suggesstion-cliente"></div>  
            </td>
            <td>
                    <input type="text" name="colaborador" id="search-colaborador" placeholder="Solicitante" size="20"/>
                    <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                    <div id="suggesstion-colaborador"></div>       
            </td>
            <td>
                    <input type="text" name="responsavel" id="search-usuario" placeholder="Responsável pela O.S." size="20"/>
                    <input type="hidden" name="idUsuario" id="search-idusuario" />
                    <div id="suggesstion-usuario">            
            </td> 
 

        </tr>
        <tr><td><input type="submit" name="btnBusca" value="Buscar" class="btn btn-outline-success my-2 my-sm-0"</td></tr>
        
    </form>   
    </table>-->
<?php
    if(isset($_POST['btnBusca']) && $_POST['btnBusca'] == "Buscar"){
   echo '<div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Resultados</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">OS</th>
                    <th scope="col">Data</th>
                    <th scope="col">Serviço(s)</th>               
                    <th scope="col">Cliente</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Ver</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Excluir</th>
                </tr>
                </thead>';
          
        //print_r($_POST);
        $opcao = $_POST;
    switch($opcao){
            case (!empty($_POST['nDocumento'])): 
        $busca= $opcao['idOSDoc'];
                $filtro = 'WHERE ordemservico.status > 0 AND itemordemservico.idItemOS = '.$busca;
                $pg = $_GET['pg'];
$i = 1;
 foreach($objOS->querySelecionaItemServico($filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
       
';
 }    
        
         break;
            
            case (!empty($_POST['nProcesso'])): 
                $busca= $opcao['idOSPro'];
                $filtro = 'WHERE ordemservico.status > 0 AND itemordemservico.idItemOS = '.$busca;
                $pg = $_GET['pg'];
$i = 1;
 foreach($objOS->querySelecionaItemServico($filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
       
';
 }    
                
                break;
            
            case (!empty($_POST['idCliente'])): 
                $busca= $opcao['idCliente'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idCliente = '.$busca;
                $pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 5;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;
 //conta a quantidade total de registros na tabela
$quantreg = $objOS->querySelectTotalServico($filtro);//$objOS->querySelectTotal();
 $i = $quantreg;
 foreach($objOS->querySelecionaServico($inicial, $numreg, $filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
       
';
 }

                break;
            
            case (!empty($_POST['idColaborador'])): 
                $busca= $opcao['idColaborador'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idColaborador = '.$busca;
                $pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 5;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;
 //conta a quantidade total de registros na tabela
$quantreg = $objOS->querySelectTotalServico($filtro);//$objOS->querySelectTotal();
 $i = $quantreg;
 foreach($objOS->querySelecionaServico($inicial, $numreg, $filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
       
';
 }              

                break;
            
            case (!empty($_POST['idUsuario'])): 
                $busca= $opcao['idUsuario'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idResponsavel = '.$busca;
                $pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 5;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;
 //conta a quantidade total de registros na tabela
$quantreg = $objOS->querySelectTotalServico($filtro);//$objOS->querySelectTotal();
 $i = $quantreg;
 foreach($objOS->querySelecionaServico($inicial, $numreg, $filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
       
';
 }                   

                break;
      
    }
echo'
    </table></div></div> 
    ';
    }

        ?>
    
    
<br/><h6 class="border-bottom border-gray pb-2 mb-0"><a href="?pg=0&acao=novo">Nova Ordem de Serviço</a>
</h6>

        
</div>        
            



<div class="container">
    
  <div class="row justify-content-md-center">
      
      <div class="media text-muted pt-3"> 

        <?php           
         if(isset($_SESSION['msg'])){
            echo '<br/>'.$_SESSION['msg'];

            }
        ?>


 <!--Exibe o form para cadastrar nova ordem de serviço-->
 <?php
 if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){ 
     
unset($_SESSION['msg']);
     
echo '
    <div class="media text-muted pt-3"> 
    
        <div class="col-md-auto">
        
        <form method="POST" action="" autocomplete="off" class="form-signin" >
        
        <div>
        
            <table width="40%" align="center">
                <tr>     
                    <td colspan="2">ORDEM DE SERVIÇO</td>
                </tr>
                
                    <tr>
                        <td colspan="3"> <div class="alert alert-primary" role="alert">
                        Caso a ordem de serviço possua mais de 1 serviço, <br/>clique em "incluir Serviço" antes de preencher os campos.</div>
                        </td>
                    </tr>
                    
';
                foreach ($objOS->querySelecionaOS() as $nOS) { }
    
                    echo'
                        <tr>
                        <td colspan="3"><label>Nº O.S.:</label>
                            <input class="form-control" type="text" name="nOrdemServico" placeholder="'.$nOS->idOrdemServico.'" readonly  />
                        </td>
                        </tr>
                            <tr>
                                <td colspan="3" ><label>Data:</label>
                                <input type="date" name="dataOrdemServico" required class="form-control" />
                                </td>
                            </tr>
                
                                <!--usuario
                                <tr>
                                    <td colspan="3"><label>Responsável: </label>
                                        <input type="text" name="responsavel" id="search-usuario" placeholder="Responsável pela O.S." size="30"  required autocomplete="off" class="form-control" />
                                -->        
                                        <input type="hidden" name="idUsuario" id="search-idusuario" value="'.$_SESSION['id'].'" />
                                <!--
                                        <div id="suggesstion-usuario"></div>     
                                    </td>
                                </tr>-->
                
                                                               ';
                                if(isset($_SESSION['erroResponsavel'])){
                                
                                    echo '<tr>
                                            <td colspan="3"><div class="alert alert-warning" role="alert">'. $_SESSION['erroResponsavel'].'</div><!--Fim div "alert alert-warning"--></td>
                                        </tr> ';
                            
                                }
                                    echo'
                                    <!--Solicitante-->
                                    <tr>                 
                                        <td colspan="3"><label>Solicitante: </label>
                                            <input type="text" name="colaborador" id="search-colaborador" placeholder=" Nome do solicitante" size="30"  required autocomplete="off" class="form-control"/>
                                            <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                                            <div id="suggesstion-colaborador"></div>       
                                        </td>
                                    </tr>
                                    ';
                                    
                                        if(isset($_SESSION['erroSolicitante'])){
                                                            
                                            echo '<tr><td colspan="3"><div class="alert alert-warning" role="alert">'. $_SESSION['erroSolicitante'].'</div><!--Fim div "alert alert-warning"--></td></tr> ';
                                                        
                                        }
                                echo'
                                     <!--Cliente-->                
                                    <tr>
                                        <td colspan="3"><label>Cliente:</label>
                                        <input type="text" name="cliente" id="search-cliente" placeholder=" Nome do cliente" size="30"  required autocomplete="off" class="form-control"/>
                                        <input type="hidden" name="idCliente" id="search-idcliente" />
                                        <div id="suggesstion-cliente"></div>                  
                                        </td>
                                    </tr>
                                 ';
                                
                            if(isset($_SESSION['erroCliente'])){
                                
                                echo '<tr><td colspan="3"><div class="alert alert-warning" role="alert">'. $_SESSION['erroCliente'].'</div><!--Fim div "alert alert-warning"--></td></tr> ';
                            
                            }
                            
                        echo'               
                            <tr>
                                <td colspan="3"><label>Previsão Entrega: </label>
                                <input type="date" name="validade"  required class="form-control"/>
                                </td>
                            </tr>
                            
            <tr>
                <td><br/></td>
            </tr>    
        
                <tr>
                    <td>Item Serviço</td>
                </tr> 

                
                ';
                        //inclui 3 ou mais serviços ao form
                        if(isset($_GET['form'])&& $_GET['form']=='servico'){
                        $i = $_GET['inclui'] + 1;                       
                        $a = $i;
                       
                    for($i = 0;$i < $_GET['inclui']+1;$i++){

                        $j = $i + 1;

                    echo '
                    <tr>
                        <td><label>Serviço: </label>
                        <select name="servico['.$j.']" class="form-control">
                            <option value="0">Selecione...</option>
                    ';
               
                            foreach($objServico->querySelect() as $res){
                                 
                                 echo '<option value="'.$res->idServico.'">'.$res->sigla.'</option>';
                                 
                            }
                    
                        echo'
                        </select>
                        </td>    
                    
                            <td>
                                <label>Qtd: </label>
                                <input type="text" name="qtd['.$j.']" size="1" class="form-control" />
                            </td>
                
                        <td>
                            <label>Valor: </label>
                            <input type="text" name="valorItem['.$j.']"  placeholder="R$" class="form-control" />
                        </td>
                        
                    </tr>
                 
                <tr>
                    <td>
                    <label>RRT:</label> 
                        <input type="text" name="nDocumento['.$j.']" class="form-control" />
                    </td>
 
                    <td><label>Nº Processo:</label> 
                        <input type="text" name="nProcesso['.$j.']" class="form-control" />
                    </td>

                    <td><label>Agência:</label> 
                        <input type="text" name="agencia['.$j.']" class="form-control" />
                    </td>
                </tr>
                
            <tr>
                <td colspan="3"><label for="obsOS">Observações</label>
                    <textarea class="md-textarea form-control" name="obs['.$j.']" id="obs['.$j.']" rows="3"></textarea>
                </td>
            </tr>
                        ';
                        
            }
                 
            echo '
                <tr>
                    <td><a href="?pg=0&acao=novo&form=servico&inclui='.$a.'">Incluir Serviço</a></td>
                </tr>
                ';
            }
                
                if(empty($_GET['inclui'])){
                    
                    $i = 1;
                    echo'
                    <tr>
                    <td><label>Serviço: </label>
                    <select name="servico['.$i.']"  class="form-control">

                    <option value="0">Selecione...</option>';
                     foreach($objServico->querySelect() as $res){
                         echo 
                          '<option value="'.$res->idServico.'">'.$res->sigla.'</option>';
                         
                    }
                 echo'
                    </select></td>          
                
                    <td><label>Qtd: </label>
                    <input type="text" name="qtd['.$i.']" size="1" class="form-control" />
                    </td>
                    <td><label>Valor: </label>
                    <input type="text" name="valorItem['.$i.']"   placeholder="R$" class="form-control" /></td>
                    </tr>
                    
                    <tr>
                    <td><label>RRT:</label> 
                    <input type="text" name="nDocumento['.$i.']" class="form-control" /></td>

                    <td><label>Nº Processo:</label> 
                    <input type="text" name="nProcesso['.$i.']" class="form-control" /></td>

                    <td><label>Agência:</label> 
                    <input type="text" name="agencia['.$i.']" class="form-control" /></td>
                </tr>
 
                    <tr>
                    <td colspan="3"><label for="obsOS">Observações</label>
                       
                        <textarea class="md-textarea form-control" name="obs['.$i.']" id="obs['.$i.']" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><a href="?pg=&acao=novo&form=servico&inclui='.$i.'">Incluir Serviço</a></td>
                </tr>';
                    }
                echo
                '<tr>
                    
                    <td colspan="9" align="center"><hr></td>
                </tr>
                <tr>
                    
                    <td align="center">Pagamento</td>

                </tr>

                <tr>
                    <td> 
                        <label for="avista">À vista</label>
                        <input id="avista" checked="checked" type="radio" name="TipoPagamento" onclick="tipoPagamentoSel();" value="1"/>
                    </td>
                    <td> 
                        <label for="prazo">Parcelado</label>
                        <input id="prazo" type="radio" name="TipoPagamento" onclick="tipoPagamentoSel();" value="2"/>
                    </td>
            </tr>

            <tr>
                    <td colspan="2"><label>Valor :</label> R$<input type="text" name="pagamento" size="3" class="form-control" required="required" /></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Data Pagamento: </label><input type="date" name="dataPagamento" required="required" class="form-control"/></td>
                </tr>    
                
                <tr>
                <td colspan="4"><hr/></td>
                </tr>
                
                <tr>
                <td colspan="4">Preencha para pagamento à prazo</td>
                </tr>

                   <tr>
                    <td><label>Sinal:</label>R$ <input type="text" name="entrada" size="3" class=" form-control"/></td>
                </tr>               
                <tr>
                    <td ><label>Nº Parcelas:</label><input type="text" name="parcela" size="1" class=" form-control"/></td>
                    <td ><label>Valor Parcela:</label>R$<input type="text" name="valorParcela" size="3" class="form-control"/></td>
                    <td ><label>Data Vencimento 1ª parcela:</label><input type="date" name="vencimento" class="form-control"/></td>
                </tr>    

                <tr>
                <td colspan="4"><hr/></td>
                </tr>


                <tr>
                    <td colspan="2" ><label>Pagar com: </label>
                    <select name="formaPagamento" required class="form-control">
                    <!--<option value="0">Selecione...</option>-->';

                        foreach ($objPagamento->querySelecionaFormaPagamento() as $res) {
                            echo '<option value="'.$res->idFormaPagamento.'">'.$res->formaPagamento.'</option>';   
                        }

                echo
                    '</select>
                    
                    <tr>
                    <td colspan="2" >
                        <label>Status Pagamento:</label>
                        
                    <select name="statusPagamento" class="form-control">
                    <option value="1">Pendente</option>
                    <option value="5">Pago</option>
                    </select>
                    </td>
                </tr>          
                
                     <td colspan="3">
                         <input type="hidden" name="status" value="1"/><!--status 1 = pendente -->
                         <br/><input type="submit" name="btnCadastrar" value="Gerar Ordem de Serviço" class="btn btn-lg btn-primary btn-block" />
                         </td>
                </tr>
          <tr>                
        <td><a href="?pg=0&acao=limpar">Voltar</a></td>
        </tr>
        </table>
    </form> 
    
    </div>
    ';   
    
    
    }
    
     if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){ 
        unset($_SESSION['msg']); 
        $busca= $_GET['busca'];
        $id = $busca;     
echo '
        <form method="POST" action="" class="form-signin" >
        <div>
            
            <table width="50%" align="center">
                <tr>     
                    <td colspan="3" align="center">ORDEM DE SERVIÇO</td>
                </tr>

                <tr>
                    <td colspan=""></td>
                </tr>
';
        foreach($objOS->querySelecionaImpressao($id) as $res){
            
        }

                echo'
                <tr>                
                    <td colspan="3"><label>Nº O.S.:</label> <input class="form-control" type="text" name="nOrdemServico" value="'.$res->idOrdemServico.'"   /></td>
                </tr>
                
                <tr>
                    <td colspan="3"> <label>Data: </label><input type="text" name="dataOrdemServico" value="'.$objFuncao->formataData($res->data).'" class="form-control"/></td>
                </tr>

                    <!--usuario-->
                <tr>
                    <td colspan="3"><label>Responsável: </label>
                    <input type="text" name="responsavel" id="search-usuario" value="'.$res->nomeUsuario.'" " class="form-control" size="30"/>
                    <input type="hidden" name="idUsuario" id="search-idusuario" />
                    <input type="hidden" name="idusuario" value="'.$res->idUsuario.'" />
                    <div id="suggesstion-usuario"></div>     
                    </td>
                </tr>
                
                    <!--Solicitante-->
                <tr>                 
                    <td colspan="3"><label>Solicitante: </label>
                    <input type="text" name="colaborador" id="search-colaborador" value="'.$res->nomeColaborador.'" class="form-control" size="30"/>
                    <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                    <input type="hidden" name="idcolaborador" value="'.$res->idColaborador.'" />
                    <div id="suggesstion-colaborador"></div>       
                    </td>
                </tr>

                 <!--Cliente-->                
                <tr>
                    <td colspan="3"><label>Cliente: </label>
                    <input type="text" name="cliente" id="search-cliente" value="'.$res->nomeCliente.'" class="form-control" size="30"/>
                    <input type="hidden" name="idCliente" id="search-idcliente" />
                    <input type="hidden" name="idcliente" value="'.$res->idCliente.'" />
                    <div id="suggesstion-cliente"></div>                  
                    </td>
                    <tr>
                    <td colspan="3"><label>Previsão Entrega: </label><input type="text" name="validade" value="'.$objFuncao->formataData($res->validade).'" class="form-control"/></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Total OS:  R$</label> <input type="text" name="total" value="'.$objFuncao->SubstituiPonto($res->total).'" class="form-control"/></td>
                </tr>
                <tr>
                    <td><br/></td>
                </tr>

                
                ';
                
                foreach($objOS->querySelecionaItem($id) as $item){

                   echo '<tr>
                       <td colspan="2"><label>Serviço: </label>
                       <select name="servico['.$item->idItemOS.']" class="form-control">
                            <option value="'.$item->idServicoDisponibilizado.'">'.$item->sigla.'</option>';

                     foreach($objServico->querySelect() as $res){
                         echo 
                          '<option value="'.$res->idServico.'">'.$res->sigla.'</option>';
                         
                    }                                                    
                    echo'
                        </select> </td>
                        <td><label>Qtd: </label><input type="text" name="qtd['.$item->idItemOS.']" value="'.$item->qtd.'" class="form-control" size="1" /></td>
                
                    <td><label>Valor Item: R$</label> <input type="text" name="valorItem['.$item->idItemOS.']" value="'.$objFuncao->SubstituiPonto($item->valorItem).'" class="form-control" size="2" /></td>
                 </tr> 
                 
                <tr>
                    <td colspan="3"><label>RRT:</label> 
                    <input type="text" name="nDocumento['.$item->idItemOS.']"  value="'.$item->nDocumento.'" class="form-control" /></td>

                    <td><label>Nº Processo:</label> <input type="text" name="nProcesso['.$item->idItemOS.']" value="'.$item->nProcesso.'" class="form-control"/></td>
                    
                    <td><label>Agencia:</label> <input type="text" name="agencia['.$item->idItemOS.']" value="'.$item->agencia.'" class="form-control"/></td>
                </tr>
                
                    <tr>
                    <td colspan="4">
                    <label for="obsOS">Observações</label>
                        <textarea class="md-textarea form-control" name="obs['.$item->idItemOS.']" id="obs" rows="3">'.$item->obs.'</textarea>
                    </td>
                </tr><br/>';
                    
                }
                echo
                '
                    <tr>
                        <td colspan="3" align="center"><input type="hidden" name="status" value="1" >
                        <br/><input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" /></td>
                    </tr>
                                        <tr>
                        <td td colspan="3">
                    <div class="alert alert-warning" role="alert">	
                    ';       
                    if(isset($_SESSION['msg'])){
                    echo $_SESSION['msg'];

                    }
                    echo '
                    </div>  
                        </td>
                    </tr>          
            </div> 
          </div>

        </table>
     
    </form> ';   
    }

?>
</div>    
<br/><br/>             
</div><!--    </div>Fim Espaço central -->
</div>
                  
<div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Ordens de Serviço em Aberto</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">OS</th>
                    <th scope="col">Data</th>
                    <th scope="col">Serviço(s)</th>               
                    <th scope="col">Cliente</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Ver</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Excluir</th>
                </tr>
                </thead>
<?php
$pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 5;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;

 //conta a quantidade total de registros na tabela
$quantreg = $objOS->querySelectTotal();
 $i = $quantreg;
 foreach($objOS->querySelectPag($inicial, $numreg) as $res){
     if($res->data == "0000-00-00" OR $res->data == ""){
         $resData = " - ";
     }else{
         $resData = $objFuncao->formataData($res->data);
     }
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$resData.'</td>
         <td><a href="visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico=ok&solicitante=servico">Ver Serviço</a></td>
         <td>'.$res->nomeCliente.'</td>
         <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=servico" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=servico" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="?pg=&acao=excluir&busca='.$res->idOrdemServico.'" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
         </tr>
         
';

 }
 if(isset($_GET['acao']) && $_GET['acao'] == "ver"){
     echo '<tr align="center"><td colspan="9">Odem de Serviço: '.$_GET['idOS'].'</td></tr>';
     foreach($objOS->querySelecionaItem($_GET['idOS']) as $item){

     echo '
                     <tr>
         <td>'.$item->sigla.'</td>

         <td>Nº:'.$item->nDocumento.'</td>
  
         <td >Proc.: '.$item->nProcesso.'</td>
 
         <td>Valor: R$'.$item->valorItem.'</td>

         <td colspan="2">Obs.:<textarea type="text" name="descricao" class="md-textarea form-control"  rows="3"  >'.$item->obs.'</textarea></td>

         <td>Entrega:</td>
             <td>Situação: '.$item->situacao.'</td>
                 
             </tr>
             <tr><td><a href="?pg=&acao=limpar">Voltar</a></td></tr>
';
     
     }
 }
echo '<tr><td colspan=11 align="center">';
$quant_pg = ceil($quantreg/$numreg);	
$quant_pg++;		// Verifica se esta na primeira página, se nao estiver ele libera o link para anterior	
if ( $pg > 0) { 		
echo '<a href=?pg='.$anterior .' ><b>« anterior</b></a>';	
} else { 		
echo "";	
}		// Faz aparecer os numeros das página entre o ANTERIOR e PROXIMO	
for($i_pg=1;$i_pg<$quant_pg;$i_pg++) { 		
// Verifica se a página que o navegante esta e retira o link do número para identificar visualmente		
if ($pg == ($i_pg-1)) { 			
echo " [$i_pg] ";		
} else {			
$i_pg2 = $i_pg-1;			
echo ' <a href="?pg='.$i_pg2.'" ><b>'.$i_pg.'</b></a> ';
		}
		}		
		// Verifica se esta na ultima página, se nao estiver ele libera o link para próxima	
		if (($pg+2) < $quant_pg) {
			echo '<a href=?pg='.$proximo .' ><b>próximo »</b></a>';	
			} else {
				echo "";
                                echo '</td></tr>';
				}
?>                 <?php 
                 if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'servico'){
                     $url = 'ordemServico.php?pg=0&acao=limpar';       
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'inicio'){
                     $url = 'administrativo.php?pg=0&acao=limpar';
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'cliente'){
                     $url = 'cliente.php?pg=0&acao=limpar';  
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'colaborador'){
                     $url = 'colaborador.php?pg=0&acao=limpar'; 
                 }else{
                     $url = '?pg=0&acao=limpar';
                 }
                 ?>
                <tr>
                    <td><a href="<?php echo $url;?>">Voltar</a></td>
                </tr>
                                                
            </table>
    </div>
        </div>
</div>

<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
</body>
</html>
    <?php
}else{
    $_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
    header("Location: login.php");
}
