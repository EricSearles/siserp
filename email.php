<?php 
session_start();
if(!empty($_SESSION['id'])){  
        require_once 'classes/Funcoes.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Email.php';
        require_once 'classes/SMTP.php';

        $mail = new PHPMailer(); 
        $objOrcamento = new Orcamento();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
       
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
	<title>MVeiga Arquitetura</title>     
</head>
<body>
<div class="my-3 p-3 bg-white rounded shadow-sm">
<div class="container">
  <div class="row justify-content-md-center">
<div class="media text-muted pt-3"> 
<?php
        $busca= $_GET['busca'];
        $id = $objFuncao->base64($busca, 2);
        foreach($objOrcamento->querySelecionaImpressao($id) as $dados){
        }
        foreach($objOrcamento->querySelecionaContatoCliente($dados->idCliente) as $contato){
        }
?>
    </br>
<form name="email" method="POST" action="">
    <table   cellpadding="6">
        <tr>
            <th>Dados para o E-mail</th>
        </tr>
        <tr>
            <td>Enviar para:</td>
            <td colspan="6"><input type="text" name="email" value="<?php echo $contato->email; ?>" class="form-control"/></td>
        </tr>
        <input type="hidden" name="idOrcamento" value="<?php echo $dados->idOrcamento; ?>"/>
        <tr>
            <td>Assunto:</td>
        <?php
            if($_SESSION['empresa']=='1'){
               echo '          
            <td colspan="6"><input type="text" name="assunto" value="ORÇAMENTO MVEIGA ARQUITETURA" class="form-control"/></td>
        </tr>
<!--    E-mail:<br/>-->        
                <tr>
                    <td colspan="3"><img src ="images/mveiga-arquitetura-logo.jpg" width="150" align="bottom" /></td>';
            }else{
                echo ' 
             <td colspan="6"><input type="text" name="assunto" value="ORÇAMENTO TC ASSESSORIA" class="form-control"/></td>
        </tr>
<!--    E-mail:<br/>-->        
                <tr>
                    <td colspan="3"><img src ="images/logo-tcassessoria.fw.png" width="200" align="bottom" /></td>';
            }
            ?>                     
                <td colspan="5" align="left">PROPOSTA COMERCIAL</td>
                </tr>
                <tr>
                    <td colspan="4">Nº Orçamento: <?php echo $dados->numero;?></td>
                    <td colspan="5" align="right">Data: <?php echo $objFuncao->formataData($dados->data);?></td>
                </tr>
                <tr>

                    <td colspan="9" ></td>
                </tr>
                <tr>
                    <td colspan="9"><!--Elaborado por:  $dados->nomeUsuario--></td>
                </tr>
                <?php
                  foreach ($objOrcamento->querySelecionaRespCliente($dados->idCliente) as $responsavel) {

                    echo'
                  <tr>
                  <td colspan="9">A/C: ' .$dados->nomeColaborador.'</td>
                  </tr>';
                }               
                ?>
                
                <tr>
                    <td colspan="9">Cliente: <?php echo $dados->nomeCliente;?></td>
                </tr>
                <tr>
                   <td colspan="9"><!--<hr>--></td>
                </tr>
                <!--DADOS SERVIÇO-->
                <tr>
                    <td colspan="9"><!--Serviço(s) a realizar:--></td>
                </tr>
                <!--<tr>
                   <td colspan="9"><hr></td>
                </tr>-->
                
                
<?php

$verificaServico = $objOrcamento->querySelecionaItemServico($id);

//verifica se a array produto esta vazia, caso esteja não imprime nada de produtos
if(empty($verificaServico)){
                                    
}else{
echo'
                <tr>
                    <td colspan="9">Serviços</td>
                </tr>
                <tr align="center">
                    <td>Item</td>
                    <td colspan="2" >Serviço</td>
                    <td colspan="2" align="center">Descrição</td>
                    <td align="center">Qtd</td>
                    <td colspan="3" align="center">Valor Item</td>
                </tr>
';
                $i = 1;

                foreach($objOrcamento->querySelecionaItemServico($id) as $item){
                //print_r($item);
                    $descricao = nl2br($item->descricao);
                echo'   
                <tr align="center">
                    <td>'.$i++.'</td>
                    <td colspan="2">'.$item->sigla.'</td>	
                    <td colspan="2">'.$descricao.'</td>
                    <td align="center">'.$item->qtd.'</td>
                    <td colspan="4" align="center">R$ '.str_replace('.', ',',$item->valorItem).'</td>
                </tr>
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
               <tr>
                    <td colspan="4">Observações</td><td><?php echo nl2br($dados->obsOrcamento);?></td>
                </tr>
                <tr>
                <td colspan="9"><hr></td>
                </tr>
                <tr>
                <td colspan="4">Pagamento:</td><td colspan="1"><?php echo nl2br($dados->formaPagamento);?></td>
                </tr>
                <tr>
                <td colspan="9"><hr></td>
                </tr>
                <tr>
                    <td colspan="4">Orçamento válido até: <?php echo $objFuncao->formataData($dados->validade);?></td>
                    <td colspan="5" align="right">Valor Total: R$ <?php echo str_replace('.', ',',$dados->total);?></td>
                </tr> 
                <tr>
                   <td colspan="9"><hr></td>
                </tr>
                
                
                <?php
                foreach($objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario) as $res){

                }
                ?>
                <tr>
                    <td colspan="9"><?php echo $res->nomeUsuario;?></td>
                </tr>
                <?php
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
        
                $verificaAssinatura = $objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario);

                
                if((empty($verificaAssinatura))){
                    
                    //echo'<tr><td>colocar email padrão aqui</td></tr>';
                    
                }else{
                
                echo'
                
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                
                <tr>
                    <td colspan="9">E-mail: '. $res->email.'</td>
                </tr>';
                }
                
                echo'

                <tr>
                    <td colspan="9" ><a href="http://marezaveigaarquitetura.com.br" target="_blank">www.mveigaarquitetura.com.br</a></td>
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
                    
                    //echo'<tr><td>colocar email padrão aqui</td></tr>';
                    
                }else{
                
                echo'
                
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>';
                
                }
                
                echo'
                
                <tr>
                    <td colspan="9">E-mail: comercial@tcassessoria.com.br</td>
                </tr>
                <tr>
                    <td colspan="9" ><a href="http://tcassessoria.com.br" target="_blank" >www.tcassessoria.com.br</a></td>
                </tr>';
                
                }
                ?>

                <tr>
                    <td colspan="9"><hr></td>
                </tr>
                <tr align="center">
                 <td colspan ="9"><input type="submit" name="btnEnviar" value="Enviar" class="btn-successbtn btn-lg btn-primary btn-block"/></a>
                 <br/>
                 <a href="orcamentoPDF.php?busca=<?php echo $busca;?>" target="_blank">Imprimir |</a><a href="imprimeOrcamento.php?pg0=&busca=<?php echo $busca;?>"> Voltar</a></td>
                </tr>
                <tr>
                    
                </tr>
            </table>                  
</form>        
<!--    </div>Fim Espaço central -->
  </div>
</div>
</div>
</div>
<?php
if (isset($_POST['btnEnviar'])){
    $email = $_POST['email'];
    $subject = $_POST['assunto'];
$mensagem = ' 
<div class="my-3 p-3 bg-white rounded shadow-sm">
<div class="container">
  <div class="row justify-content-md-center">

<div class="media text-muted pt-3"> 

<table>
                <tr>';
                if($_SESSION['empresa']=='1'){
                    $mensagem .= '<td colspan="3"><img src ="http://searles.com.br/siserp/images/mveiga-arquitetura-logo.jpg" width="150" align="bottom" /></td>';
                }else{
                    $mensagem .= '<td colspan="3"><img src ="http://searles.com.br/siserp/images/logo-tcassessoria.fw.png" width="200" align="bottom" /></td>';
                }
                
                $mensagem .='<td colspan="6" align="center">PROPOSTA COMERCIAL</td>
                </tr>
                <tr>
                    <td colspan="9"><br/><br/></td>
                </tr>

                <tr>

                    <td colspan="9" ></td>
                </tr>
                <tr>
                    <td colspan="9"><!--Elaborado por:  $dados->nomeUsuario--></td>
                </tr>';

                  foreach ($objOrcamento->querySelecionaRespCliente($dados->idCliente) as $responsavel) {

                    
                  $mensagem .='<tr>
                  <td colspan="9">A/C: <strong>'.$dados->nomeColaborador.' </strong>,</td>
                  </tr>
                  <tr>
                <td colspan="9">Segue abaixo o orçamento solicitado, <br/><br/></td>               
                </tr>
                <tr>
                <td colspan="9">Cliente: <strong> ' .$dados->nomeCliente.'<br/></td>
                </tr>
                <tr>

                    <td colspan="9" >Data: '. $objFuncao->formataData($dados->data).'</td>
                </tr>';
                }               
                
                $mensagem.='
                <tr>
                    <td colspan="9"><!--Cliente:  $dados->nomeCliente--></td>
                </tr>
                <tr>
                   <td colspan="9"><!--<hr>--></td>
                </tr>

                <tr>
                    <td colspan="9">Serviço(s) a realizar:</td>
                </tr>
                <tr>
                   <td colspan="9"><hr></td>
                </tr>
                
                <tr>
                    <td align="center">Item</td>
                    <td colspan="2" align="center">Serviço</td>
                    <td colspan="2" align="center">Descrição</td>
                    <td align="center">Qtd</td>
                    <td colspan="3" align="center">Valor Item</td>
                </tr>';
        
                $i = '1';

                foreach($objOrcamento->querySelecionaItem($id) as $item){
                    $descricao = nl2br($item->descricao);
                
                    $mensagem .='
                <tr>
                    <td align="center">'.$i++.'</td>
                    <td colspan="2" align="center">'.$item->sigla.'</td>	
                    <td colspan="2" align="center">'.$descricao.'</td>
                    <td align="center" align="center">'.$item->qtd.'</td>
                    <td colspan="4" align="center">R$ '.str_replace('.', ',',$item->valorItem).'</td>
                </tr>
                
                 <tr>
                   <td colspan="9"><hr></td>
                </tr>
                ';
                }
                $mensagem .='
                 <tr>
                <td colspan="3">Observações: </td><td colspan="7" align="left">'.$dados->obsOrcamento.'</td>
                </tr>
                <tr>
                <td colspan="9"><hr></td>
                </tr>
                <tr>
                <tr>
                    <td colspan="9" align="right">Valor Total: R$ '. str_replace('.', ',',$dados->total).'</td>
                </tr> 
                <tr>
                <td colspan="9"><hr></td>
                </tr>
                <tr>
                <td colspan="4">Pagamento:</td><td colspan="7" align="left">'.nl2br($dados->formaPagamento).'</td>
                </tr>
                <tr>
                <td colspan="9"><hr></td>
                </tr>
                <tr>
                    <td colspan="9" align="right">Orçamento válido até: '. $objFuncao->formataData($dados->validade).'</td>
                </tr> 
                <tr>
                   <td colspan="9"><hr><br/><br/><br/></td>
                </tr>
                <tr>
                    <td colspan="9">Atenciosamente,<br/></td>
                </tr>';
                foreach($objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario) as $res){

                $mensagem .='
                <tr>
                    <td colspan="9">'. $res->nomeUsuario.'</td>
                </tr>';
                
                if($_SESSION['empresa'] == '1'){
                
                $mensagem .='<tr>
                    <td colspan="9">Mareza Veiga Arquitetura</td>
                </tr>
                <tr>
                    <td colspan="9">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                </tr>
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
                    <td colspan="9">E-mail: '. $res->email.'</td>
                </tr>
                <tr>
                    <td colspan="9" >www.mveigaarquitetura.com.br</td>
                </tr>';
                }else{
                $mensagem .='
                <tr>
                    <td colspan="9">TC Assessoria</td>
                </tr>
                <tr>
                    <td colspan="9">Tels: (11) 3996-3356 </td>  
                </tr>
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
                    <td colspan="9">E-mail: comercial@tcassessoria.com.br</td>
                </tr>
                <tr>
                    <td colspan="9" >www.tcassessoria.com.br</td>
                </tr>';
                }

                $mensagem .='
                <tr>
                    <td colspan="9"><hr></td>
                </tr>
                ';
                }
                $mensagem .='
                </table>
                <!--    </div>Fim Espaço central -->
  </div>
</div>
</div>
</div>
';
             
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'mail.searles.com.br';//ENDEREÇODOSEUSERVIDORSMTP;
$mail->Port = '465';//ENDEREÇODAPORTADESEUSERVIDORSMTP;
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Username = '_mainaccount@searles.com.br';//NOMEDOUSUARIODESEUSERVIDORSMTP;
$mail->Password = 'searles102030';//SENHADESEUUSUÁRIODESEUSERVIDORSMTP;

$mail->CharSet = 'UTF-8';
$mail->setFrom( $res->email, $res->nomeUsuario);
$mail->addAddress($email, $dados->nomeCliente);
$mail->isHTML(true);
if($_SESSION['empresa'] == '1'){
$mail->Subject = 'MVEIGA ARQUITETURA - ORÇAMENTO';
}else{
$mail->Subject = 'TC ASSESSORIA - ORÇAMENTO'; 
}
$mail->Body = $mensagem;
 
 if ($mail->send()) {
?>
<script>
window.location="imprimeOrcamento.php?pg=&busca=$busca&msg=ok";
</script>
<?php
 } else {
     echo "Mailer Error: " . $mail->ErrorInfo; 
     echo "<br/> Não foi possível enviar o e-mail, tente novamente!";
 }


?>


<?php
}
?>
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