<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
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
    
    <title>Ordem de Serviço - MVArquitetura</title>
       
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
                    <!--<th scope="col">Data</th>-->
                    <!--<th scope="col">Serviço(s)</th>      -->         
                    <th scope="col">Cliente</th>
                    <th scope="col">Situação</th>
                    <th scope="col" colspan="4">Alterar para:</th>
                </tr>
                </thead>
<?php
 foreach($objOS->queryAtualizaOS($_GET['os']) as $res){
     echo'           
         <tr align="center">
         <td><br/><br/>'.$res->idOrdemServico.'</td>
         <!--<td><br/><br/>'.$objFuncao->formataData($res->idOrdemServico).'</td>-->
        <!-- <td><br/><br/><a href=?pg=&acao=ver&idOS='.$res->idOrdemServico.'&servico="ok">Ver</a></td>-->
         <td><br/><br/>'.$res->nomeCliente.'</td>
         <td><br/><br/>'.$res->situacao.'</td>
         <td colspan ="4" ><a href="?pg=&acao=execucao&os='.$res->idOrdemServico.'">Em execução</a><hr><a href="?pg=&acao=finalizado&os='.$res->idOrdemServico.'"> Finalizado</a><hr><a href="?pg=&acao=cancelado&os='.$res->idOrdemServico.'">Cancelado</a></td>     
         </tr>';
 }
 
     //Atualiza a situação da OS
    if(isset($_GET['acao'])&&$_GET['acao']=='execucao'){
        $id = $res->idOrdemServico;
        $situacao = '4';//status em execução = 4
    if($objOS->queryUpdateSituacaoOS($id, $situacao) == 'ok'){
     ?>
<script>window.location.href ='ordemServico.php?pg=&';</script>            
    <?php
    }
    }

 if(isset($_GET['acao'])&&$_GET['acao']=='finalizado'){
        $id = $res->idOrdemServico;
        $situacao = '6';//status finalizado = 6
    if($objOS->queryUpdateSituacaoOS($id, $situacao) == 'ok'){
     ?>
    <script>window.location.href ='ordemServico.php?pg=&';</script>           
    <?php
    }
    }
 if(isset($_GET['acao'])&&$_GET['acao']=='cancelado'){
        $id = $res->idOrdemServico;
        $situacao = '2';//status cancelado = 2
    if($objOS->queryUpdateSituacaoOS($id, $situacao) == 'ok'){
     ?>
<script>window.location.href ='ordemServico.php?pg=&';</script>            
    <?php
    }
    }
    ?>
                <tr><td colspan ="11"> <a href="ordemServico.php?pg=0&acao=limpar">Voltar</a></td></tr>                              
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
