<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/ServicoDisponibilizado.php';

        $objOrcamento = new Orcamento();
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
	<title>Orçamento - MVArquitetura</title>
       
</head>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<?php include 'pesquisa.php';?>
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
        <h6 class="border-bottom border-gray pb-2 mb-0">Itens do Orçamento</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Nº</th>
                    <th scope="col">Cliente</th>
                     <th scope="col">Solicitante</th>
                    <th scope="col">Responsavel</th>
                    <th scope="col">Data</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Validade</th>
                    <th scope="col">Situação</th>
                    <th scope="col" colspan="4">Alterar para:</th>

                </tr>
                </thead>
    <?php
    foreach($objOrcamento->queryAtualizaOrcamento($_GET['orcamento']) as $res){
       // print_r($res);
                  echo ' <tr>
                    <td><br/><br/>'.$res->numero.'</td>
                    <td><br/><br/>'.$res->nomeCliente.'</td>
                    <td><br/><br/>'.$res->nomeColaborador.'</td>
                    <td><br/><br/>'.$res->nomeUsuario.'</td>
                    <td><br/><br/>'.$objFuncao->formataData($res->data).'</td>
                    <td><br/><br/>R$ '.$objFuncao->SubstituiPonto($res->total).'</td>
                    <td><br/><br/>'.$objFuncao->formataData($res->validade).'</td>
                    <td><br/><br/><a href="atualizaSituacaoOrcamento.php?pg=&acao=situacao&orcamento='.$res->idOrcamento.'">'.$res->situacao.'</a></td>
                    <td colspan ="4" ><a href="?pg=&acao=execucao&orcamento='.$res->idOrcamento.'">Em execução</a><hr><a href="?pg=&acao=finalizado&orcamento='.$res->idOrcamento.'"> Finalizado</a><hr><a href="?pg=&acao=cancelado&orcamento='.$res->idOrcamento.'">Cancelado</a></td>
                </tr>';     
    }
    //Atualiza a situação do Orçamento
    if(isset($_GET['acao'])&&$_GET['acao']=='execucao'){
        $id = $res->idOrcamento;
        $situacao = '4';//status em execução = 4
    if($objOrcamento->queryUpdateSituacaoOrcamento($id, $situacao) == 'ok'){
     ?>
<script>window.location.href ='orcamento.php?pg=&';</script>            
    <?php
    }
    }

 if(isset($_GET['acao'])&&$_GET['acao']=='finalizado'){
        $id = $res->idOrcamento;
        $situacao = '6';//status finalizado = 6
    if($objOrcamento->queryUpdateSituacaoOrcamento($id, $situacao) == 'ok'){
     ?>
<script>window.location.href ='orcamento.php?pg=&';</script>            
    <?php
    }
    }
 if(isset($_GET['acao'])&&$_GET['acao']=='cancelado'){
        $id = $res->idOrcamento;
        $situacao = '2';//status cancelado = 2
    if($objOrcamento->queryUpdateSituacaoOrcamento($id, $situacao) == 'ok'){
     ?>
<script>window.location.href ='orcamento.php?pg=&';</script>            
    <?php
    }
    }
    ?>
                <tr><td colspan ="11"> <a href="orcamento.php?pg=0&acao=limpar">Voltar</a></td></tr>    
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
