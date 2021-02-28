<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Pagamento.php';
 //chama o script preenche endereco pelo cep       
 //       include 'cep.php';
        
        $objOS = new OrdemServico();
        $objPagamento = new Pagamento(); 
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
    if(isset($_GET['acao']) AND $_GET['acao'] == "limpar"){
        unset($_SESSION['msg']);
    }
    
    
    if(isset($_POST['btnCadastrar'])&& $_POST['btnCadastrar'] == "Incluir"){
        $dados = $_POST;
        
        if($objOS->queryInsertPagoColaborador($dados) == 'ok'){
            $_SESSION['msg'] = "pagamento incluido";
        }
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
    
	<title>OS - MVArquitetura</title>
       
</head>
<?php include 'pesquisa.php'; ?>
<body>
<?php 
if($_SESSION['permissao'] <= 1){
include 'menu.php';
}else{
include 'menu1.php'; 
}
?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
  <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>

</div>
<div class="my-3 p-3 bg-white rounded shadow-sm">
<!--    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
    </form>
<br/><h6 class="border-bottom border-gray pb-2 mb-0"><a href="?pg=&acao=novo">Nova Ordem de Serviço</a>
</h6>-->
<!--****************************Corte**********************************  -->
<div class="container">
  <div class="row justify-content-md-center">
<!--    <div class="col col-lg-2">
     Espaço do lado esquerdo
    </div>         -->
<div class="media text-muted pt-3"> 
    <!--<div class="col-md-auto">-->
    <?php
        $busca= $_GET['busca'];
        $id = $busca;  
        $solicitante= $_GET['solicitante'];
        foreach($objOS->querySelecionaImpressao($id) as $res){
         if($res->data == "0000-00-00" OR $res->data == ""){
         $resData = " - ";
        }else{
            $resData = $objFuncao->formataData($res->data);
        }  
        }

        ?>
<!--        <form method="POST" action="" class="form-signin">-->
        <div>
            <table   cellpadding="6">
                <tr>
                    <td colspan="20" align="center">ORDEM DE SERVIÇO</td>
                </tr>
                <tr>
                    <td colspan="20"></td>
                </tr>
                <tr>
                    <td colspan="10">Nº O.S.: <?php echo $res->idOrdemServico;?></td>
                    <!--Seleciona tabela usuario-->
                    
                    <td colspan="10" align="right">Data: <?php echo $resData;?></td>
                </tr>
                <tr>
                    <td colspan="10" >Solicitante: <?php echo $res->nomeColaborador;?></td>
<!--                    Seleciona tabela usuario-->
                    <td colspan="10" align="right">Responsável: <?php echo $res->nomeUsuario;?></td>
                </tr>

                <tr>
                    <!--                    Seleciona tabela cliente-->
                    <td colspan="10">Cliente: <?php echo $res->nomeCliente;?></td>                  
                    <td colspan="10" align="right">Vencimento: <?php echo $objFuncao->formataData($res->validade);?></td>
                </tr>
                <?php
                $valorTotal = $res->total;
                $pag = $objPagamento->querySelecionaPagamento($id);
                
                 foreach ($pag as $parc) {
                     if($parc->parcela == 'Sinal'){
                            
                         $tipo = 'Parcelado';
                         echo '<tr>
                                    <td colspan="10">Pagamento: '.$tipo.'<td>
                               <td colspan="10" align="right">Valor Total: R$'.$objFuncao->SubstituiPonto($valorTotal).'</td>
                            </tr>
                            <tr>
                            <td colspan="20" >Forma de Pagamento: '.$res->formaPagamento.'</td>
                            </tr>';
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
                                        <td colspan="10">Pagamento: '.$tipoPagamento.'</td>
                                        <td colspan="10" align="right">Forma de Pagamento: '.$res->formaPagamento.'</td>
                                    </tr>
                                    <tr>
                                    <td colspan="7">Valor: R$ '.$objFuncao->SubstituiPonto($valorTotal).'</td>
                                        <td colspan="7">Data Pagamento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                            
                                        <!--<td colspan="6" align="right">Situação: '.$pagamento->situacao.'</td>-->
                                        
                                         <td colspan="4" align="right">Situação: <a href="atualizaPagamento.php?pg=&acao=pagamento&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'&solicitante='.$solicitante.'">'.$pagamento->situacao.'</a></td>
                                    
                                        
                                        
                                    </tr>';
                                
                            }else{
                                $tipoPagamento = 'Parcelado';
                                echo'                
                                  
                                    <tr>
                                        <td colspan="4">Parcela: '.$pagamento->parcela.'</td>
                                            <td colspan="6">Vencimento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                        <td colspan="6">Valor: R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        
                                        <td colspan="4" align="right">Situação: <a href="atualizaPagamento.php?pg=&acao=pagamento&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'&solicitante='.$solicitante.'">'.$pagamento->situacao.'</a></td>
                                    </tr>';
                                }
                            }
                ?>
                

                                    <tr>
                                       <td colspan="20"><hr></td>
                                    </tr>
                                    <tr>

                                        <td colspan="20">Serviço(s):</td>
                                    </tr>
                <tr align="center">
                   <td colspan="20"></td>
                </tr>
                <tr>
                    <td align="center">Item</td>                    
                    <td colspan="2" align="center">RRT</td>
                    <td colspan="2" align="center">NºProcesso</td>
                    <td colspan="2" align="center">Agência</td>
                    <td colspan="2">Serviço</td>
                    <td align="center">Obs</td>
                    <td align="center">Qtd</td>
                    <td colspan="4" align="center">Valor</td>

                    <td colspan="3" align="center">Previsão</td>
                    <td colspan="3" align="center">Status do serviço</td>
                </tr>
                <?php
                $i = 1;

        foreach($objOS->querySelecionaItem($id) as $item){
            
         if($item->entrega == "0000-00-00" OR $item->entrega == ""){
                $dataEntrega = " - ";
            }else{
                $dataEntrega = $objFuncao->formataData($item->entrega);
            }
                echo'                   
                <tr align="center">
<!--                    Seleciona tabela serviço disponibilizado-->                    

                    <td align="center">'.$i++.'</td>
                    <td colspan="2">'.$item->nDocumento.'</td>
                    <td colspan="2">'.$item->nProcesso.'</td>
        ';            
                    if($item->agencia == ""){
                        echo '<td colspan="2"> - </td>';
                    }else{
                    echo '<td colspan="2">'.$item->agencia.'</td>';
                    }
                    
                    echo '
                    <td colspan="2" align="center">'.$item->sigla.'</td>';
                    
                    
                if(empty($item->obs)){
                    echo '<td align="center"> - </td>';
                }else{
                    //link para observação
                  echo  '<td align="center"><a href="?pg=&busca='.$res->idOrdemServico.'&item='.$item->idItemOS.'&obs=ok&solicitante='.$_GET['solicitante'].'">Sim</a></td>';
                                        
                   }                                        
                    echo'
                    <td align="center">'.$item->qtd.'</td>
                    <td colspan="4" align="center">R$'.$item->valorItem.'</td>
                    <td colspan="3" align="center">'.$dataEntrega.'</td>
                    <td colspan="3" align="center"><a href="visualizaItemOS.php?pag=&acao=verItem&idOS='.$id.'&item='.$item->idItemOS.'">'.$item->situacao.'</a></td>
                                             
                </tr>';
                if(isset($_GET['obs'])&& $_GET['obs'] == 'ok') {
                       echo '<tr><td colspan="20" align="center"><div class="alert alert-primary" role="alert">Obs: '.$item->obs.'</div></td></tr>';
                }
                 echo'
                    <tr>
                    <td colspan="20"><hr></td>
                    </tr>';
                }
                echo '                
                    <tr>
                    <td colspan="3"><!--<hr><a href="?pg=&busca='.$busca.'&solicitante='.$_GET['solicitante'].'&acao=incluiPagamento">Incluir Pagamento Colaborador</a><hr>--></td>
                    </tr>';
                    
                
             if(isset($_GET['acao'])&& $_GET['acao']=='incluiPagamento'){

             echo'
             <form name="altera" method="POST" >
             <tr>
             <td colspan="2">Valor Pagamento: </td><td> <input type="text" name="valorPagamento" class="form-control"/></td>
             </tr>
             <tr>
             <td colspan="2">Data Pagamento: </td><td> <input type="date" name="dataPagamento" class="form-control"/></td>
             </tr>
             <tr>
            <tr>
            <td colspan="2">Pagamento</td>
            <td><select name="status" class="form-control">
            <option value="1">Pendente</option>
            <option value="5">Pago</option>
            <option value="2">Cancelado</option>
            </select>
            </td>
            </tr>
             <td colspan="2">
             <input type="hidden" name="idOS" value="'.$_GET['busca'].'" />
             <input type="hidden" name="idColaborador" value="'.$res->idColaborador.'" />
             <input type="submit" name="btnCadastrar" value="Incluir" class="btn btn-primary btn-block"/></td>
             <td align="center"><a href="?pg=&busca='.$busca.'&solicitante='.$_GET['solicitante'].'">Fechar</a></td>
             </tr>
             <tr>
             <td colspan="3">            
             <div class="alert alert-warning" role="alert">';
                if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                }
            echo '
            </div>
            </td>
             </tr>
             </form>';
                    
        }

                    
                ?>

                 <?php 
                 if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'servico'){
                     $url = 'ordemServico.php?pg=&acao=limpar';       
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'inicio'){
                     $url = 'administrativo.php?pg=&acao=limpar';
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'cliente'){
                     $url = 'cliente.php?pg=&acao=limpar';  
                 }else if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'colaborador'){
                     $url = 'colaborador.php?pg=&acao=limpar'; 
                 }else{
                     $url = 'administrativo.php?pg=&acao=limpar&solicitante=inicio';
                 }
                 ?>
                <tr>
                    <td><a href="ordemServicoPDF.php?busca=<?php echo $busca;?>" target="_blank"> Imprimir |</a><a href="<?php echo $url;?>">Voltar</a></td>
                </tr>
            </table>  
            
            
        
<!--    </form>    -->
</div>  

    
 <?php
 
  ?>              
<!--    </div>Fim Espaço central -->
  </div>
<!--    <div class="col col-lg-2">
    Espaço do lado direito
    </div>-->
  </div>
</div>

<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
<!--          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
  <!--          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>-->
<!--           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 
</body>
</html>
    <?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
