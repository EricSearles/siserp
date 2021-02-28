<?php 
session_start();
unset($_SESSION['msg']);
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/Usuario.php';
        require_once 'classes/Permissao.php';
        
        $objUsuario = new Usuario();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
               
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Cadastrar'){
        
        $dados = $_POST;
        
            //verifica se a forma de pagamento já está cadastrado
            if(!empty($objUsuario->verificaFormaPagamento($dados['formaPagamento']))){
                
                $_SESSION['msg'] = 'Esta forma de pagamento já esta cadastrado.';     
                    
            //verifica se foi inserido e retorna mensagem
                }else if($objUsuario->queryInsereFormaPagamento($dados) == 'ok'){
                    
                    $_SESSION['msg'] = "Nova forma de pagamento cadastrada com sucesso.";
            
                }else{
                    
                     $_SESSION['msg'] = "Não foi possível cadastrar esta forma de pagamento, tente novamente ou entre em ontato com o desenvolvedor.";
            
                }       
        
    }  
       //Editar forma de pagamento
        if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){ 
            
            unset($_SESSION['msg']);
            
                $dados = $_POST;
            
                if($objUsuario->queryUpdateFormaPagamento($dados) == 'ok'){
                    
                    $_SESSION['msg'] = "Alteração realizada com sucesso.";
        
                }else{
                
                    $_SESSION['msg'] = "Não foi possivel alterar o registro, tente novamente ou entre em contato com o desenvolvedor.";
                   
                
                }
        
        }  
        
            //Excluir forma de pagamento
            if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){     
                
                if($objUsuario->queryDeleteFormaPagamento($_GET['id']) == 'ok'){
                            
                    $_SESSION['msg'] = "Registro Excluido.";
                          
                }else{
                    
                    $_SESSION['msg'] = "erro em deletar";
                        
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
	<title>Formas de Pagamento - MVArquitetura</title>
</head>
<body>
<?php include 'menu.php'; ?>

    <main role="main" class="container">
        
        <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
            
            <?php   
            
                //Saudação da página
                echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
            
            ?>
        </div><!--fim Div d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm -->
        
            <div class="my-3 p-3 bg-white rounded shadow-sm">
                
                <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?acao=novo">Nova Forma de Pagamento</a></h6>
            
                <div class="container">
                    
            <div class="row justify-content-md-center">
            
        <div class="col-md-auto">
<?php

//Exibe o form para cadastrar nova permissão
if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){  

    echo ' 
        <div class="media text-muted pt-3">  
        
            <form method="POST" action="" class="form-signin">
            
                <h5>Cadastrar Forma de Pagamento</h5>
                
                <hr/> 
                
                    <input type="text" name="formaPagamento" placeholder="Digite a forma de pagamento" required maxlength="20" class="form-control"><br/>
                    <input type="hidden" name="status" value="1" >
                    <input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />

                	';
                
                            if(isset($_SESSION['msg'])){
                                
                            echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!-- fim div  "alert alert-warning" role="alert"-->';
                            
                            }
                            echo '
                
                
                <a href="?acao=limpar">Voltar</a>
                
            </form>
            
        </div><!-- fim div  "media text-muted pt-3"-->
         ';    
}    

//Alterar forma de pagamento        
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){  
    
        foreach($objUsuario->querySelecionaUpFormaPagamento($_GET['id']) as $res){
            
        }                                    
       
    echo ' 
        <div class="media text-muted pt-3">    
        
            <form method="POST" action="" class="form-signin">
            
                <h5>Alterar Forma de Pagamento</h5>
                
                <hr/>  
                
                    <input type="hidden" name="idFormaPagamento" value="'.$res->idFormaPagamento.'" >
                    <input type="text" name="formaPagamento" value="'.$res->formaPagamento.'" class="form-control" ><br/>
                    <input type="hidden" name="status" value="1" >
                    <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />

                	';
                            if(isset($_SESSION['msg'])){
                                
                            echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!-- fim div  "alert alert-warning" role="alert"-->';
                            }
                            
                            echo '
                                
                
                
                <a href="?acao=limpar">Voltar</a>
                
            </form>
            
        </div><!-- fim div  "media text-muted pt-3"-->
         ';    
}         
 ?>       
        
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        
        <h6 class="border-bottom border-gray pb-2 mb-0">Formas de Pagamento Cadastradas</h6>
        
            <div class="media text-muted pt-3">
                
            <table class="table">
                <thead>
                    <tr>
                        <th scope="row">#</th>
                        <th scope="col">Forma de Pagamento</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Excluir</th>
                    </tr>
                </thead>
                    
<?php     

    $i = 1;

        foreach($objUsuario->querySelecionaFormaPagamento() as $res){
            
            echo ' 
                <tr>
                    <th scope="row">'.$i++.'</th>
                    <td>'.$res->formaPagamento . '</td>
                    <td><a href="?acao=alterar&id='.$res->idFormaPagamento.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                    <td><a href="?acao=excluir&id='.$res->idFormaPagamento.'" title="Excluir esse dado" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                </tr>
                
            ';
                       
        }    

?>
                
</table>
    
        </div><!--Fim Espaço central--> 
        
    <div class="col col-lg-2">
        <!--Espaço do lado direito-->
    </div>
    
</div>

</script>
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