<?php 
unset($_SESSION['msg']);
session_start();

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
    <title>Atualizar Pagamento - MVArquitetura</title>
       
</head>

<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
  <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>
</div>                
<div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Ordens de Serviço em Aberto</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr align="center">
                    <th scope="col">OS</th>
                    <th scope="col">Parcela</th>
                    <th scope="col">Vencimento</th>               
                    <th scope="col">Valor</th>
                    <th scope="col">Situação</th>
                    <th scope="col" colspan="4">Alterar para:</th>
                </tr>
                </thead>
<!--                Para atualizar o pagamento:
                idOrdemServico = $_GET['idOS'];
                idPagamento    = $_GET['idPag'];
                tipoPagamento  = $_GET['tipoPag']; -->
                
<?php
$solicitante = $_GET['solicitante'];

echo $_GET['solicitante'];
    $pag = $objPagamento->querySelecionaPagamentoParcela($_GET['idOS'], $_GET['idPag']);
        foreach ($pag as $parc) {
            if($parc->parcela == 'Sinal'){
                $tipo = 'Parcelado';
//                echo '<tr>
//                       <td >Pagamento: '.$tipo.'<td>
//                <td>Valor Total: R$'.$objFuncao->SubstituiPonto($res->total).'</td>
//                </tr>
//                <tr>
//                <td>Forma de Pagamento: '.$res->formaPagamento.'</td>
//                </tr>';
}else{
                         //echo 'Não';
                     }
                   
                 }
                    
                foreach ($pag as $pagamento) {
                            //print_r($pagamento);


                           
                            if($pagamento->parcela == "Única"){
                                $tipoPagamento = 'À vista';
                                echo '                
                                    <tr>
                                        <td>'.$_GET['idOS'].'</td>
                                        <td>'.$tipoPagamento.'</td>
                                       <!--<td>'.$res->formaPagamento.'</td>-->
                                        <td>'.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                    <td>R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        
                                            
                                        <td>Situação: '.$pagamento->situacao.'</td>
                                        
                                                                                <td>'.$pagamento->situacao.'</td>
                                            <td colspan ="4" ><a href="?pg=&acao=pago&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Pago</a><hr><a href="?pg=&acao=pendente&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Pendente</a><hr><a href="?pg=&acao=cancelado&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Cancelado</a></td>     
            
                                        
                                    </tr>';
                                
                            }else{
                                $tipoPagamento = 'Parcelado';
                                echo'                
                                  
                                    <tr align="center">
                                    <td>'.$_GET['idOS'].'</td>
                                        <td>'.$pagamento->parcela.'</td>
                                            <td>'.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                        <td>R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        
                                        <td>'.$pagamento->situacao.'</td>
                                            <td colspan ="4" ><a href="?pg=&acao=pago&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Pago</a><hr><a href="?pg=&acao=pendente&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Pendente</a><hr><a href="?pg=&acao=cancelado&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">Cancelado</a></td>     
            
                                    </tr>';
                                }
                            }

  //Atualiza a situação do item OS
    if(isset($_GET['acao'])&&$_GET['acao']=='pago'){
        $id = $_GET['idPag'];
        $situacao = '5';//status pago = 5
        if($objPagamento->queryUpdateSituacaoPagamento($id, $situacao) == 'ok'){
                    
        ?>
        <script>window.location.href ='exibeServico.php?pg=&busca=<?php echo $pagamento->idOrdemServico;?>';</script>  
        <?php
        }
    }

 if(isset($_GET['acao'])&&$_GET['acao']=='pendente'){
        $id = $_GET['idPag'];
        $situacao = '1';//status pendente = 1
    if($objPagamento->queryUpdateSituacaoPagamento($id, $situacao) == 'ok'){
     ?>
    <script>window.location.href ='exibeServico.php?pg=&busca=<?php echo $pagamento->idOrdemServico;?>';</script>          -->
    <?php
    }
    }
    
    
 if(isset($_GET['acao'])&&$_GET['acao']=='cancelado'){
        $id = $_GET['idPag'];
        $situacao = '2';//status cancelado = 2
    if($objPagamento->queryUpdateSituacaoPagamento($id, $situacao) == 'ok'){
     ?>
    <script>window.location.href ='exibeServico.php?pg=&busca=<?php echo $pagamento->idOrdemServico;?>';</script>          -->
    <?php
    }
    }
    ?>


 
 <!---<a href="atualizaPagamento.php?pg=&acao=pagamento&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'"   -->
                <tr><td colspan ="11"> <a href="exibeServico.php?pg=0&busca=<?php echo $pagamento->idOrdemServico;?>&acao=limpar&solicitante="<?php echo $solicitante;?>">Voltar</a></td></tr>                              
            </table>
    </div>
</div>


</body>
</html>
    <?php
}else{
    $_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
    header("Location: login.php");
}


