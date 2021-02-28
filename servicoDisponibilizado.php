<?php 
session_start();
unset($_SESSION['msg']);
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/Usuario.php';
        require_once 'classes/ServicoDisponibilizado.php';
        
        $objUsuario = new Usuario();
        $objFuncao = new Funcoes();
        $objServico = new ServicoDisponibilizado();
              
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Cadastrar'){
        
        $dados = $_POST;
    
            //verifica se a permissao já está cadastrado
            if(!empty($objServico->verificaServico($dados['servico']))){
                
                $_SESSION['msg'] = 'Você já cadastrou um serviço com este nome.';   
                    
                //verifica se foi inserido e retorna mensagem
                }else if($objServico->queryInsert($_POST) == 'ok'){
                    
                 $_SESSION['msg'] = "Novo serviço cadastrado com sucesso.";
    
            }else{
            $_SESSION['msg'] = "Não foi possível cadastrar este serviço, tente novamente ou entre em cntato com o desenvolvedor.";
            }    
        
    }  
       
            if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){
                
                $dados = $_POST;
                $id = $_GET['id'];
                
                if($objServico->queryUpdateServico($dados, $id) == 'ok'){
                            
                           $_SESSION['msg'] = "Alteração realizada com sucesso.";
            
                        }else{
                            
                          $_SESSION['msg'] = "Não foi possivel alterar o registro, tente novamente ou entre em contato com o desenvolvedor.";
                          
                        }
                
                
              
            }  
        
        
                if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){    
                    
                    if($objServico->queryDelete($_GET['id']) == 'ok'){
                        
                        $_SESSION['msg'] = "Registro Excluido.";
                          
                     }else{
                            echo '<script type="text/javascript">alert("Erro em deletar");</script>';
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
	<title>Serviços Oferecidos - MVArquitetura</title>
</head>
<body>
    
    <?php include 'menu.php';?>
    
        <main role="main" class="container">
        
            <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
                
                <?php   
                    //Saudação da página
                    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
                ?>
                
            </div><!--fim div d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm -->
                
                <div class="my-3 p-3 bg-white rounded shadow-sm">
                    
                    <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?acao=novo">Novo Serviço</a></h6>
                
                <div class="container">
                
            <div class="row justify-content-md-center">
    
        <div class="col-md-auto">
        
    <?php
    
    //Exibe o form para cadastrar nova permissão
    if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){     
        
        echo ' 
        
            <div class="media text-muted pt-3"> 
            
                <form method="POST" action="" class="form-signin">
                
                    <h5>Cadastrar Serviço</h5>
                    
                    <hr/>  
                    
                        <label>Serviço</label><br/>
                        <input type="text" name="servico" placeholder="Digite o nome do serviço" required="required" size="45" class="form-control"><br/>
                        
                            <label>Sigla</label><br/>
                            <input type="text" name="sigla" placeholder="Digite a sigla" class="form-control" ><br/>      
                            
                                <label>Valor</label><br/>
                                R$ <input type="text" name="valor" placeholder="Digite o valor do serviço" class="form-control"><br/>
                            
                            <label>Descrição</label><br/>
                            <textarea type="text" name="descricao" class="md-textarea form-control" cols="35" rows="3" placeholder="Descrição do serviço" ></textarea><br/>
                        
                        <input type="hidden" name="status" value="1" >
                        <input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />
    
                            ';
                            
                                if(isset($_SESSION['msg'])){
                                            
                                    echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!--fim div "alert alert-warning" role="alert"-->';
            
                                }
                                        
            echo '
                            
                    
                    <a href="?acao=limpar">Voltar</a>
                    
                </form>
                
        </div><!--fim div "col-md-auto"-->
             ';    
    }    
//Alterar serviço        
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){  
    
    $dados = $_POST;
    
        foreach($objServico->queryUpdate($_GET['id']) as $res){
 
        }            
        
    echo ' 
        <div class="media text-muted pt-3"> 
            
            <form method="POST" action="" class="form-signin">
            
                <h5>Alterar Serviço</h5>
                
                <hr/>  
                
                    <label>Serviço</label>
                    <input type="text" name="servico"  size="45" value="'.$res->nomeServico.'" class="form-control"><br/><br/>
                    
                        <label>Sigla</label>
                        <input type="text" name="sigla" value="'.$res->sigla.'" class="form-control" ><br/><br/>       
                        
                            <label>Valor</label>
                            R$ <input type="text" name="valor" value="'.$res->valor.'" class="form-control"><br/><br/>
                        
                        <label>Descrição</label><br/>
                        <textarea type="text" name="descricao" class="md-textarea form-control" cols="35" rows="3"  >'.$res->descricao.'</textarea><br/>
                        
                    <input type="hidden" name="status" value="1" >
                    <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />

                	';
                
                            if(isset($_SESSION['msg'])){
                                
                            echo '<div class="alert alert-warning" role="alert">'. $_SESSION['msg'].'</div><!--fim div "alert alert-warning" role="alert"-->';

                            }
                            echo '
                
                
                <a href="?acao=limpar">Voltar</a>
                
            </form>
            
        </div><!--fim div "row justify-content-md-center"-->
         ';    
}         
 ?>     
 </div>
    </div><!-- fim div container-->
    
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            
            <h6 class="border-bottom border-gray pb-2 mb-0">Serviços Cadastrados</h6>
            
                <div class="media text-muted pt-3">
                    
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="row">#</th>
                            <th scope="col">Serviço</th>
                            <th scope="col">Sigla</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Excluir</th>
                        </tr>
                        </thead>
                
    <?php
         
        $i = 1;
        
        foreach($objServico->querySelect() as $res){
            
            if(strlen($res->descricao) > '40') {
                
                 $descricao = substr($res->descricao,0,40).' ...';
                 
                }else{
                    
                    $descricao = substr($res->descricao,0,40);
                 
                }
            
         echo ' <tr>
                    <th scope="row">'.$i++.'</th>
                    <td>'.$res->nomeServico.'</td>
                    <td>'.$res->sigla.'</td>
                    <td>'.$descricao.'</td>
                        <td>R$ '.$res->valor.'</td>
                    <td><a href="?acao=alterar&id='.$res->idServico.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                    <td><a href="?acao=excluir&id='.$res->idServico.'" title="Excluir esse dado" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                </tr>
                
            ';
               
        }    

    ?>
    
</table>
    
                    </div><!--Fim Espaço central--> 
                
                <div class="col col-lg-2">
                    <!--Espaço do lado direito-->
                </div><!--fim div "col col-lg-2" -->
                
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