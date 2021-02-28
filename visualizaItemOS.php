<?php 
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
    <title>Ordem de Serviço - MVArquitetura</title>
       
</head>

<body>
<?php 

include 'menu.php';

?>


<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
  <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>
</div>                
<div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0"><?php echo 'Ordem de Serviço: '.$_GET['idOS'];?></h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr align="center">
                    <th scope="col">Doc</th>
                    <th scope="col">Nº</th>
                    <th scope="col">Processo</th>               
                    <th scope="col">Valor</th>
                    <th scope="col" colspan="3" align="center">Obs.</th>
                    <th scope="col">Data Documento</th>
                    <th scope="col">Situação</th>
                    <th scope="col" colspan="4">Alterar para:</th>
                </tr>
                </thead>
<?php
//Inclui data documento
             if(isset($_GET['dado'])&& $_GET['dado']=='incluiData'){
             $idItem = $_GET['item'];
             $solicitante = $_GET['solicitante'];
             
             echo'
             <form name="altera" method="POST" >
             <tr>
             <td colspan="5">Incluir Data Documento: <input type="date" name="dataDocumento" />
             <input type="hidden" name="idItem" value="'.$idItem.'" />
            <input type="hidden" name="solicitante" value="'.$solicitante.'" />
             <input type="submit" name="btnCadastrar" value="Incluir" class="btn btn-outline-success my-2 my-sm-0"/></td>
             </tr>
             </form>';
                    
        }
        
//Ver inclui data       
if(isset($_GET['acao']) && $_GET['acao'] == "ver"){
    
     $idOS = $_GET['idOS'];
     
     foreach($objOS->querySelecionaItem($_GET['idOS']) as $item){

         
         if($item->entrega == "1970-01-01" OR $item->entrega == ""){
             $dataDocumento = "-";
         }else{
             $dataDocumento = $objFuncao->formataData($item->entrega);
         }
         
     echo '
         
         ';
         
//inclui data documento         
if(isset($_POST['btnCadastrar'])&& $_POST['btnCadastrar'] == "Incluir"){

        $idItem = $_POST['idItem'];  
        
        $novaData = $_POST['dataDocumento'];

     $upData = $objOS->queryUpdateItemEntrega($idItem, $novaData);
     
      if($upData == 'ok'){
          
      ?>
          <script>
          window.location.href = "visualizaItemOS.php?pg=&acao=ver&item=<?php echo $idItem;?>&idOS=<?php echo $idOS;?>&servico=ok";
          </script>
       <?php   
          $_SESSION['msg'] = "Data atualizada";
      }else{
          $_SESSION['msg'] = "Não foi possível atualizar a data";
      }   
}

//exibe item da ordem de serviço e botão incluir data     
     echo'
         <tr align="center">
         <td><br/><br/>'.$item->sigla.'</td>

         <td><br/><br/>'.$item->nDocumento.'</td>
  
         <td><br/><br/>'.$item->nProcesso.'</td>
 
         <td><br/><br/>R$'.$item->valorItem.'</td>

         <td colspan="3" ><br/><textarea type="text" name="descricao" class="md-textarea form-control"  rows="3"  >'.$item->obs.'</textarea></td>

         <td><br/>'.$dataDocumento.'<hr><a href="?pg=&acao=ver&dado=incluiData&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'&solicitante='.$_GET['solicitante'].'">Incluir data</a></td>

        <td><br/><br/>'.$item->situacao.'</td>
        <td colspan ="4" ><a href="?pg=&acao=execucao&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'">Em execução</a><hr><a href="?pg=&acao=finalizado&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'"> Finalizado</a><hr><a href="?pg=&acao=cancelado&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'">Cancelado</a></td> 
             </tr>
             <input type="hidden" name="acao" value="ver" />
             <input type="hidden" name="idOS" value="'.$idOS.'" />
             </form>
';
     
     }
 }

// ver item
  if(isset($_GET['acao']) && $_GET['acao'] == "verItem"){
     
     foreach($objOS->querySelecionaItemUnico($_GET['idOS'],$_GET['item']) as $item){

        
         
         if($item->entrega == "1970-01-01" OR $item->entrega == ""){
             $dataDocumento = "-";
         }else{
             $dataDocumento = $objFuncao->formataData($item->entrega);
         }  
         
         
     echo '
         <tr>
         <td align="center"><br/>'.$item->sigla.'</td>

         <td align="center"><br/>'.$item->nDocumento.'</td>
  
         <td align="center"><br/>'.$item->nProcesso.'</td>
 
         <td align="center"><br/>R$'.$item->valorItem.'</td>

         <td colspan="3" align="center"><br/><textarea type="text" name="descricao" class="md-textarea form-control"  rows="3"  >'.$item->obs.'</textarea></td>

          <td align="center"><br/>'.$dataDocumento.'<hr><a href="?pg=&acao=ver&dado=incluiData&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'&solicitante='.$_GET['solicitante'].'">Incluir data</a></td>

         
             <td align="center"><br/>'.$item->situacao.'</td>
        <td colspan ="4" ><a href="?pg=&acao=execucao&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'">Em execução</a><hr><a href="?pg=&acao=finalizado&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'"> Finalizado</a><hr><a href="?pg=&acao=cancelado&item='.$item->idItemOS.'&idOS='.$_GET['idOS'].'">Cancelado</a></td>     
               
             </tr>
';




     
     }
 }

 
     //Atualiza a situação do item OS
    if(isset($_GET['acao'])&&$_GET['acao']=='execucao'){
        $id = $_GET['item'];
        $idOS= $_GET['idOS'];
     
        $situacao = '4';//status em execução = 4
    
        if($objOS->queryUpdateSituacaoItemOS($id, $situacao) == 'ok'){
                    
        ?>
        <script>//window.location.href ='ordemServico.php?pg=&';</script>  
        <?php
        }
    }


 if(isset($_GET['acao'])&&$_GET['acao']=='finalizado'){
        $id = $_GET['item'];
        $situacao = '6';//status finalizado = 6
    if($objOS->queryUpdateSituacaoItemOS($id, $situacao) == 'ok'){
     ?>
    <script>//window.location.href ='ordemServico.php?pg=&';</script>          
    <?php
    }
    }
 if(isset($_GET['acao'])&&$_GET['acao']=='cancelado'){
        $id = $_GET['item'];
        $situacao = '2';//status cancelado = 2
    if($objOS->queryUpdateSituacaoItemOS($id, $situacao) == 'ok'){
     ?>
<script>//window.location.href ='ordemServico.php?pg=&';</script>             
    <?php
    }
    }
    ?>
                <?php 
                 if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'servico'){
                     $url = 'ordemServico.php?pg=0&acao=limpar';
                 }elseif (isset($_GET['solicitante']) && $_GET['solicitante'] == 'inicio') {
                     $url = 'administrativo.php?pg=0&acao=limpar';
                    }else{
                     $url = 'exibeServico.php?pg=0&busca='.$_GET['idOS'].'&acao=limpar&solicitante=';
                 }
                 
                 ?>
                <tr><td colspan ="11"> <a href="<?php echo $url;?>">Voltar</a></td></tr>                              
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
