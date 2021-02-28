<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Usuario.php';
  
        $objOrcamento = new Orcamento();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        $objUsuario = new Usuario();

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
	<title>Proposta Comercial - MVeiga Arquitetura</title>
       
</head>
<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
     
                <?php 
				//mensagem de email enviado
				if(isset($_GET['msg'])){
				echo 'E-mail enviado com sucesso.<br/>';
                                //echo '<br/><br/><a href="orcamento.php?pg="> Voltar</a>';
				}
				 ?>

</div>
<div class="my-3 p-3 bg-white rounded shadow-sm">

<div class="container">
  <div class="row justify-content-md-center">
<!--    <div class="col col-lg-2">
     Espaço do lado esquerdo
    </div>         -->
<div class="media text-muted pt-3"> 
<!--<div class="col-md-auto">-->
<?php
        $busca= $_GET['busca'];
        $id = $objFuncao->base64($busca, 2);
        
        foreach($objOrcamento->querySelecionaImpressao($id) as $dados){}
        //print_r($dados);
?>
        <div>
            <table  cellpadding="6">
                <tr>
                    
                    <?php
                        //define a empresa para exibir o logo
                        if($_SESSION['empresa']=='1'){
                            
                         echo '<td colspan="4"><img src ="images/mveiga-arquitetura-logo.jpg" width="150" align="bottom" /></td>';
                         
                        }else{
                            
                         echo '<td colspan="4"><img src ="images/logo-tcassessoria.fw.png" width="200" align="bottom" /></td>';
                         
                        }
                    ?>
                    
<!--TITULO-->
<td colspan="5" align="">PROPOSTA COMERCIAL</td>
</tr>

<!--ESPAÇO DEPOIS DO TITULO-->
<tr>
<td colspan="9"></td>
</tr>

<!--NÚMERO DO ORÇAMENTO-->
<tr>
<td colspan="4">Nº Orçamento: <?php echo $dados->numero;?></td>
<td colspan="5" align="right">Data: <?php echo $objFuncao->formataData($dados->data);?></td>
</tr>

<!--NOME DO CLIENTE-->                
<tr>
<td colspan="9">Cliente: <?php echo $dados->nomeCliente;?></td>
</tr>

<!--RESPONSÁVEL PELO ORÇAMENTO (nomeUsuario)-->
<tr>
<td colspan="9"><!--Elaborado por:  $dados->nomeUsuario--></td>
</tr>
    <!--NOME DO SOLICITANTE-->
    <?php
    foreach ($objOrcamento->querySelecionaRespCliente($dados->idCliente) as $responsavel) {
    //print_r($responsavel);
    echo'
    <tr>
    <td colspan="9">A/C: '.$dados->nomeColaborador.'</td>
    </tr>';
    }               
    ?>



<tr>
<td colspan="9"><!--<hr>--></td>
</tr>




<?php
$verificaServico = $objOrcamento->querySelecionaItemServico($id);

//verifica se a array produto esta vazia, caso esteja não imprime nada de produtos
if(empty($verificaServico)){
                                    
}else{

echo'
<tr>
<td colspan="9">Serviço(s):</td>
</tr>

<!--LINHA ANTES DOS SERVIÇOS
<tr>
<td colspan="9"><hr></td>
</tr>-->

<!--CABEÇALHO DA TABELA SERVIÇOS-->               
<tr  align="center">
<td>Item</td>
<td colspan="2">Serviço</td>
<td colspan="2" align="center">Descrição</td>
<td align="center">Qtd</td>
<td colspan="3" align="center">Valor Item</td>
</tr>
';

    $i = 1;
    
    //Seleciona itens dos serviços            
    foreach($objOrcamento->querySelecionaItemServico($id) as $item){
                //print_r($item);
            
        $descricao = nl2br($item->descricao);//Quebra de linha para descrição dos serviços  
        
        //loop para exibir itens de serviços
        echo'   
        <tr  align="center">
        <td align="center">'.$i++.'</td>
        <td colspan="2" align="center">'.$item->sigla.'</td>	
        <td colspan="2" align="left">'.$descricao.'</td>
        <td align="center">'.$item->qtd.'</td>
        <td colspan="4" align="center">R$ '.str_replace('.', ',',$item->valorItem).'</td>
        </tr>
        
        <!--linha divisória de item-->      
        <tr>
        <td colspan="9"><hr></td>
        </tr>
        ';
    }
}
    
    
$verificaProduto = $objOrcamento->querySelecionaItemProduto($id);

//verifica se a array produto esta vazia, caso esteja não imprime nada de produtos
if(empty($verificaProduto)){
                                    
}else{
    
echo'
<tr>
<td colspan="9">Produto(s):</td>
</tr>

<!--CABEÇALHO DA TABELA PRODUTOS-->               
<tr  align="center">
<td>Item</td>
<td colspan="2">Produto</td>
<td colspan="2" align="center">Descrição</td>
<td align="center">Qtd</td>
<td align="center">U.M.</td>
<td colspan="2" align="center">Valor Item</td>
</tr>
';

    $i = 1;
    
    //Seleciona itens dos serviços            
    foreach($objOrcamento->querySelecionaItemProduto($id) as $itemProduto){
                //print_r($itemProduto);
            
        $descricao = nl2br($itemProduto->descricao);//Quebra de linha para descrição dos serviços  
        
        //loop para exibir itens de serviços
        echo'   
        <tr  align="center">
        <td align="center">'.$i++.'</td>
        <td colspan="2" align="center">'.$itemProduto->nomeProduto.'</td>	
        <td colspan="2">'.$descricao.'</td>
        <td align="center">'.$itemProduto->qtdProduto.'</td>
        <td align="center">'.$itemProduto->sigla.'</td>
        <td colspan="2" align="center">R$ '.str_replace('.', ',',$itemProduto->valorItemProduto).'</td>
        </tr>
        
        <!--linha divisória de item-->      
        <tr>
        <td colspan="9"><hr></td>
        </tr>
        ';
    }
}
    ?>
    
    <!--CAMPO OBSERVAÇÔES-->
    <tr>
    <td colspan="4">Observações</td><td><?php echo nl2br($dados->obsOrcamento);?></td>
    </tr>
    
    <!--LINHA DEPOIS DE OBSERVAÇÔES-->
    <tr>
    <td colspan="9"><hr></td>
    </tr>
    
    <!--CAMPO FORMA DE PAGAMENTO-->         
    <tr>
    <td colspan="4">Pagamento:</td><td colspan="1"><?php echo nl2br($dados->formaPagamento);?></td>
    </tr>
    
    <!--LINHA DEPOIS DE FORMA DE PAGAMENTO-->
    <tr>
    <td colspan="9"><hr></td>
    </tr>
    
    <!--CAMPOS DATA DE VALIDADE E VALOR TOTAL-->
    <tr>
    <td colspan="4">Orçamento válido até: <?php echo $objFuncao->formataData($dados->validade);?></td>
    <td colspan="5" align="right">Valor Total: R$ <?php echo str_replace('.', ',',$dados->total);?></td>
    </tr> 
    
    <tr>
    <td colspan="9"><hr></td>
    </tr>
    
        <?php
        
        
        $verificaAssinatura = $objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario);
        print_r($teste);
            
            //Seleciona dados do usuario para assinatura do rodapé
            foreach($objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario) as $res){

            }
        
        ?>
        
        <!--NOME DO USUÁRIO NO RODAPÉ-->
        <tr>
            <td colspan="9"><?php echo $res->nomeUsuario;?></td>
        </tr>
        
        <?php
            
            //Seleciona empresa para exibir assinatura
            if($_SESSION['empresa'] == '1'){
                
                echo'
                <tr>
                    <td colspan="9">Mareza Veiga Arquitetura</td>
                </tr>
                
                <tr>
                    <td colspan="9">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                </tr>
                
                <!--PEGA OS DADOS DO CADASTRO DO USUARIO-->
                ';
                if((empty($verificaAssinatura))){
                    
                    echo '
                    <tr>
                    <td colspan="9" align="justify">
                    <div class="alert alert-warning" role="alert">
                    É necessário finalizar seu cadastro de usuário para que os dados de contato apareçam aqui.</br>
                    Caso não deseje finalizar agora, esta mensagem não aparecerá no E-mail enviado e nem no <br/>relatório impresso.<br/>';
                    
                    echo '<a href="dadosUsuario.php?acao=contato&retorno='.$_GET['busca'].'">Finalizar cadastro agora.</a></div>
                    </td>
                    </tr>
                    ';
                    
                }else{
                
                echo'
                
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                
                <tr>
                    <td colspan="9">E-mail: '. $res->email.'</td>
                </tr>';
                }
                
                echo '
                <tr>
                    <td colspan="9" >www.mveigaarquitetura.com.br</td>
                </tr>';
                
            }else{
                
                echo'
                <tr>
                    <td colspan="9">TC Assessoria</td>
                </tr>
                
                <tr>
                    <td colspan="9">Tels: (11) 3996-3356 </td> 
                </tr>
                
<!--PEGA OS DADOS DO CADASTRO DO USUARIO-->
                ';
                
                 $verificaAssinatura = $objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario);
                if((empty($verificaAssinatura))){
                    
                    echo '
                    <tr>
                    <td colspan="9" align="justify">
                    <div class="alert alert-warning" role="alert">
                    É necessário finalizar seu cadastro de usuário para que os dados de contato apareçam aqui.</br>
                    Caso não deseje finalizar agora, esta mensagem não aparecerá no E-mail enviado e nem no <br/>relatório impresso.<br/>';
                    
                    echo '<a href="dadosUsuario.php?acao=contato&retorno='.$_GET['busca'].'">Finalizar cadastro agora.</a></div>
                    </td>
                    </tr>
                    ';
                    
                }else{
                
                echo'
                
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                
                <tr>
                    <td colspan="9">E-mail: '. $res->email.'</td>
                </tr>';
                }
                
                echo '
                <tr>
                    <td colspan="9">E-mail: comercial@tcassessoria.com.br</td>
                </tr>
                
                <tr>
                    <td colspan="9" >www.tcassessoria.com.br</td>
                </tr>';
                
                }
                
                ?>
                
                <!--LINHA DEPOIS DA ASSINATURA-->
                <tr>
                    <td colspan="9"><hr></td>
                </tr>
                
                <?php
                
                //define a url para o "voltar"
                if(isset($_GET['solicitante']) && $_GET['solicitante'] == 'cliente'){
                    
                        $url = "cliente.php?pg=0";
                        
                }elseif (isset($_GET['solicitante']) && $_GET['solicitante'] == 'colaborador') {
                    
                        $url = "colaborador.php?pg=0";
                     
                }else{
                    
                        $url = "orcamento.php?pg=0";
                }
                
                ?>
     
    <!--LINKS PARA ENVIAR EMAIL, IMPRIMIR OU VOLTAR   -->         
    <tr align="center">
    <td colspan="9"><a href="email.php?busca=<?php echo $busca;?>"> Enviar |</a><a href="orcamentoPDF.php?busca=<?php echo $busca;?>" target="_blank"> Imprimir |</a><a href="<?php echo $url;?>"> Voltar</a></td>
    </tr>
    </table>              
        
    
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

<?php
?>
    </div>
        </div>
<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                  
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 

</body>
</html>
    <?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
