<?php 
unset($_SESSION['msg']);
session_start();
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/Cliente.php';
        require_once 'classes/Permissao.php';
        
        $objCliente = new Cliente();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();

$idCliente = $_GET[user];       
$tipoCliente = $_GET[tipoCliente];
$idResponsavel = $_GET[idResponsavel];  
    
    $tipo = $_GET['tipoCliente'];
    $userID = $_GET['user'];

// cadastrar novo responsavel       
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Cadastrar'){
        
        $dados = $_POST;
        $nomeResponsavel  = $dados['nomeResponsavel'];

    
            
            //verifica se o nome já está cadastrado
            if(!empty($objCliente->verificaNomeResponsavel($nomeResponsavel))){
                
                $_SESSION['msg'] = 'Este nome já esta cadastrado.';
                

                
            }else{          
                
                        
                        //insere nome do responsavel pelo cliente
                        $responsavel = $objCliente->queryInsereResponsavelCliente($idCliente, $_POST);
                        
                        //monta array para inserir dados de contato do responsavel
                     
                        $cttResp = array(
                                 'cttId'=> $responsavel,
                                 'cttIdent'=> '4',
                                 'cttDDDtel'=> $_POST['dddTelResponsavel'],
                                 'cttTel'=> $_POST['telResponsavel'], 
                                 'cttDDDcel'=> $_POST['dddCelResponsavel'],
                                 'cttCel'=> $_POST['celResponsavel'],
                                 'cttEmail'=> $_POST['emailResponsavel'],
                                 'endStatus'=> $_POST['status']
                                );
                 
                      //insere dados de contato do responsavel pelo cliente  
                     $cttResponsavel = $objCliente->queryInsereCTTResponsavel($responsavel, $cttResp);  
                     
                     if($cttResponsavel == 'ok'){
                         
                         $_SESSION['msg'] = "Cliente cadastrado com sucesso!.";
                         
                     }else{
                         
                         $_SESSION['msg'] = "Não foi possível efetuar o cadastro, tente novamente ou entre em contato com o desenvolvedor.";
                     }
    
      }  
    
    
    
}
// alterar responsavel cliente
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){  
    
        $dados = $_POST;
        $nomeResponsavel  = $dados['nomeResponsavel'];


    if($objCliente->updateResponsavelCliente($dados, $idResponsavel) == 'ok'){

         
                if($objCliente->queryUpdateCTTResponsavel($dados,  $idResponsavel) == 'ok'){
                    
                    $_SESSION['msg'] = "Os dados do responsável foram alterados com sucesso.";
                    
                }else{
                    
                    $_SESSION['msg'] = "Não foi possível fazer as alterações. Tente novamente";
                    
                }
                
    }

}

//excluir cliente
    if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){     
        
            if($objCliente->queryDeleteResponsavelCliente($_GET['user']) == 'ok'){
                
                
                if($objCliente->queryDeleteContatoResponsavel($_GET['user']) == 'ok'){
                
                            
                        $_SESSION['msg'] = "Nome Cliente Excluido com sucesso.";
                        
                }else{
                    
                        $_SESSION['msg'] = "Responsavel não excluido.";
                    
                }


            }else{
                
                $_SESSION['msg'] = "Nome do Responsável não foi excluido.";
                        
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
	<title>Responsavel pelo Cliente - MVArquitetura</title>

</head>

<?php include 'pesquisa.php'; ?>

<body>
    
<?php 

include 'menu.php';

?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
    
    <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
        
        <?php   
        //Saudação da página
        echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
        ?>

    </div><!--fim div "d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm"-->
    
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            
            
            
                <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?pg=&acao=novo&user=<?php echo $idCliente; ?>">Incluir Novo Responsável</a></h6>

<div class="container">
    
  <div class="row justify-content-md-center">
      
    <div class="col col-lg-2">
     <!--Espaço do lado esquerdo-->
    </div><!--fim div "col col-lg-2"--></fim>
            
 <?php
 
 //Exibe o form para cadastrar novo cliente
 if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){ 

echo '
    <div class="media text-muted pt-3"> 
    
        <div class="col-md-auto">
        
            <form method="POST" action="" class="form-signin">
            
                <H5> Cadastrar Responsável</h5>
                
                <hr>
                   
                        
                <label>Nome:</label></td>
                
                <input name="nomeResponsavel" type="text" size="40" required="required" class="form-control"  />
                
                    <label>Telefone:</label>
                    
                        <div class="form-group row center">
                        
                            <div class="col-xs-2">
                                <input name="dddTelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                            </div>
                            
                            <div class="col-xs-4">
                                <input type="text" name="telResponsavel" placeholder="Só numeros" maxlength="8" size="40" class="form-control"/>
                            </div>   
                            
                        </div>
                        
                            <label>Celular:</label>
                            
                                <div class="form-group row center">
                                
                                    <div class="col-xs-2">                            
                                        <input name="dddCelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                                    </div>
                                    
                                    <div class="col-xs-4">  
                                        <input type="text" name="celResponsavel" placeholder="Só numeros" maxlength="9" size="40" class="form-control"/>
                                    </div>
                                
                                </div>
                        
                        <label>E-mail:</label>
                        
                            <input name="emailResponsavel" type="text" size="30" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" /><br/>

                <input type="hidden" name="status" value="1" ><br/>
                
                    <input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />    
    
                        	';
                        
                            if(isset($_SESSION['msg'])){
                                
                                echo '<div class="alert alert-warning" role="alert">'.$_SESSION['msg'].'</div><!--Fim div "alert alert-warning"-->';
                                
                               // unset($_SESSION['msg']);
                            
                            }
                        
                        echo '
                    
                <a href="exibeCliente.php?pg=0&acao=ver&user='.$objFuncao->base64($userID, 1).'=&tipoCliente='.$tipoCliente.'">Voltar</a>
            
            </form>    
        
        </div><!--fim div "col-md-auto"-->
';
 }
 

//Exibe o form para atualizar usuario
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){ 


  foreach ($objCliente->querySelecionaRespClienteUp($idResponsavel) as $responsavel) {
    echo'
    <div class="media text-muted pt-3"> 

        <div class="col-md-auto">

            <form method="POST" action="" class="form-signin">   
                
                <h5>Alterar/Incluir Dados do Responsável</h5>
                
                    <hr>
                    
                    
                        <label>Nome:</label></td>
                
                <input name="nomeResponsavel" type="text" size="40" value="'.$responsavel->nomeResponsavel.'" class="form-control"  />
                
                    <label>Telefone:</label>
                    
                        <div class="form-group row center">
                        
                            <div class="col-xs-2">
                                <input name="dddTelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" value="'.$responsavel->dddTel.'" class="form-control"/>
                            </div>
                            
                            <div class="col-xs-4">
                                <input type="text" name="telResponsavel" placeholder="Só numeros" maxlength="8" size="40" value="'.$objFuncao->mask($responsavel->tel, '####-####').'"class="form-control"/>
                            </div>   
                            
                        </div>
                        
                            <label>Celular:</label>
                            
                                <div class="form-group row center">
                                
                                    <div class="col-xs-2">                            
                                        <input name="dddCelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" value="'.$responsavel->dddCel.'" class="form-control"/>
                                    </div>
                                    
                                    <div class="col-xs-4">  
                                        <input type="text" name="celResponsavel" placeholder="Só numeros" maxlength="9" size="40" value="'.$objFuncao->mask($responsavel->cel, '####-####').'" class="form-control"/>
                                    </div>
                                
                                </div>
                        
                        <label>E-mail:</label>
                        
                            <input name="emailResponsavel" type="text" size="30" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="'.$responsavel->email.'" class="form-control" /><br/>
                        
                   
                            <input type="hidden" name="status" value="1" >
                            <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />
                            
                    	
                    
                    ';       
  }                  
                    if(isset($_SESSION['msg'])){
                        
                    echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!--fim div "alert alert-warning"-->';
                    //unset($_SESSION['msg']);
                    
                    }
                    
                    echo '
                    
                    

                <a href="exibeCliente.php?pg=&acao=ver&user='.$userID.'=&tipoCliente='.$tipoCliente.'">Voltar</a>

        </form>    

    </div><!--fim div "col-md-auto"-->
    
';  
}  
?>              
 
    </div><!--Fim Espaço central--> 
    
        <div class="col col-lg-2">
            <!--Espaço do lado direito-->
        </div>

</div>
  
</div>
        
      
</div>
</div>
<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
<!--            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                  -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 

</body>
</html>
    <?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
