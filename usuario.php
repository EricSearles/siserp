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
        
    //Faz as validações de email e nome e cadastra usuario no BD        
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Cadastrar'){
        $dados = $_POST;
        $email = $dados['email'];
        $nome  = $dados['nome'];
        $empresa = $_SESSION['empresa'];
            
            //verifica se o e-mail já está cadastrado
                    if(!empty($objUsuario->verificaEmail($email))){
                        
                        $_SESSION['msg'] = 'Este email já esta cadastrado.';
                        
                         //verifica se o nome já está cadastrado            
                        }else if(!empty($objUsuario->verificaNome($nome))){
                            
                            $_SESSION['msg'] = 'Este nome já está sendo usado.'; 
                        
                 //verifica se foi inserido e retorna mensagem
                }else if($objUsuario->queryInsert($dados, $empresa) == 'ok'){
                    
                        $_SESSION['msg'] = "Usuário Cadastrado.";
                         //header('location: usuario.php');
                }else{
                    
            $_SESSION['msg'] = "Não foi possível cadastrar o usuário, tente novamente ou entre em ontato com o desenvolvedor.";
            
            }       
    }   
    
                            //Faz o update nos dados do usuario
                            if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){
                                    $dados = $_POST;
                                    $email = $dados['email'];
                                    $nome  = $dados['nome'];
                            
                                            if($objUsuario->queryUpdate($dados) == 'ok'){
                                           $_SESSION['msg'] = "Alteração realizada com sucesso.";
                                   
                                      }else{
                                          $_SESSION['msg'] = "Não foi possivel alterar o registro, tente novamente ou entre em contato com o desenvolvedor.";
                                       }    
                            }   
    
                //Exclui usuario do BD
                if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){          
                        if($objUsuario->queryDelete($_GET['user']) == 'ok'){
                            $_SESSION['msg'] = "Usuário Excluido.";
                            header('location: usuario.php');
                          
                        }else{
                            echo '<script type="text/javascript">alert("Erro em deletar");</script>';
                        }
                            
                }
    
    //Limpa as mensagens
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
    <title>Usuário - MVArquitetura</title>
</head>

<body>
    <?php include 'menu.php'; ?>
    
        <main role="main" class="container">
            
            <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
                
                <?php   
                    //Saudação da página
                    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
                ?>
            </div>
                        
    <div class="my-3 p-3 bg-white rounded shadow-sm">
                
        <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?acao=novo">Novo usuário</a></h6>
            
            <div class="container">
                
                <div class="row justify-content-md-center">
                
                    <div class="col col-lg-2">
                        <!--Espaço do lado esquerdo-->
                    </div>
            
        <div class="col-md-auto">
                    
            <?php
            
                 //Exibe o form para cadastrar novo usuario
                if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){   
                    
                   // unset($_SESSION['msg']);
                    
                        echo ' 
                            <div class="media text-muted pt-3">
                            
                        	    <form method="POST" action="" class="form-signin">
                        	    
                                    <h5>Cadastrar Usuário</h5>
                                    
                                    <hr/>
                                    
                            		<label>Nome:</label>
                                	<input type="text" name="nome" placeholder="Digite nome e sobrenome" class="form-control" required ><br/>
                                	
                                		<label>E-mail:</label>
                                		<input type="email" name="email" placeholder="Digite e-mail pessoal" class="form-control" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"><br/>
                                    		
                                    		<label>Senha:</label>
                                    		<input type="password" name="senha" placeholder="Mínimo 8 caracteres" class="form-control" required ><br/>
                                		
                                		<label>Permissão para:</label>
                                		<select name="permissao" class="form-control" required="required" >
                            		        <option value="3">Selecione...</option>
                            ';
                                                //Preenche o select permissao
                                                foreach($objPermissao->querySelect() as $res){  
                                        
                                                echo '<option value ='.$res->idPermissao.'>'.$res->permissao.'</option>';                                   
                                                }  
                        
                                    echo'
                                        </select><br /><br/>
                                    
                                    <input type="hidden" name="status" value="1" >
                                        <input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />
                                        
                                	';
                        
                                    if(isset($_SESSION['msg'])){
                                        
                                        echo '<div class="alert alert-warning" role="alert">' .$_SESSION['msg'].'</div><!--fim div alert-warning" role="alert"-->';
                                    
                                    //unset($_SESSION['msg']);
                                    
                                    }
                            echo '
                                
                                
                                    <a href="?acao=limpar">Voltar</a>
                                    
                                </form>
                                
                            </div><!-- fim div "media text-muted pt-3"-->
                ';    
    }    

//Exibe o form para atualizar usuario
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){ 
    
    //preenche as informações
    foreach($objUsuario->querySelecionaUser($_GET['user']) as $res){                                    

    } 

        echo ' 
            <div class="media text-muted pt-3">     
            
        	    <form method="POST" action="" class="form-signin">
        	    
                    <h5>Alterar Dados do Usuário</h5>
                    
                        <hr/>
                    
                        <input type="hidden" name="user" value="'.$res->idUsuario.'" >
                        
                    		<label>Nome:</label><br/>
                    		<input type="text" name="nome"  value="'.$res->nomeUsuario.'" class="form-control" /><br/>
                    		
                        		<label>E-mail:</label><br/>
                        		<input type="text" name="email" value="'.$res->email.'" class="form-control" /><br/>
                        		
                            		<label>Permissão para:</label><br/>
                            		<select name="permissao" class="form-control" />
                            		<option value="'.$res->permissao.'">Selecione...</option>';
                            
                                        //Preenche o select permissao
                                        foreach($objPermissao->querySelect() as $res){                                    
                                                echo '<option value ='.$res->idPermissao.'>'.$res->permissao.'</option>';                                   
                                        }  
            
                                        echo'
                                    </select><br /><br/>
                            
                                <input type="hidden" name="status" value="1" >
                                <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />
                            	';
                    
                                if(isset($_SESSION['msg'])){
                                    
                                    echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!--fim div alert-warning" role="alert"-->';
                                    //unset($_SESSION['msg']);
                                }
                        echo '
                            
                    
                    <a href="?acao=limpar">Voltar</a>
                    
                </form>
            </div><!-- fim div "media text-muted pt-3"-->
        ';    
} 
?>             
        </div><!--Fim Espaço central--> 
        
                <div class="col col-lg-2">
                    <!--Espaço do lado direito-->
                </div><!--Fim div Espaço lado direito--> 
                
              </div><!--fim div  "row justify-content-md-center"-->
            
            
            </div><!--fim div container-->

<div class="my-3 p-3 bg-white rounded shadow-sm">
    
    <h6 class="border-bottom border-gray pb-2 mb-0">Usuários Cadastrados</h6>
    
        <div class="media text-muted pt-3">
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="row">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Permissão</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Excluir</th>
                    </tr>
                </thead>
                
                    <?php
                         
                         $i = 1;
                         
                           foreach($objUsuario->querySelect() as $res){
                
                                echo ' 
                                    <tr>
                                        <th scope="row">'.$i++.'</th>
                                        <td>'.$res->nomeUsuario . '</td>
                                        <td>'.$res->email . '</td>
                                        <td>'.$res->permissao . '</td>
                                        <td><a href="?acao=alterar&user='.$res->idUsuario.'" title="Alterar esse dado" ><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                                        <td><a href="?acao=excluir&user='.$res->idUsuario.'" title="Excluir esse dado" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                                    </tr>
                                ';
                               
                           }    
                
                    ?>
                
            </table>
      
        </div><!--fim da div "media text-muted pt-3"-->
        
    </div><!--fim div "my-3 p-3 bg-white rounded shadow-sm"-->
    
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
