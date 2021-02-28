<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/Usuario.php';
        require_once 'classes/Permissao.php';
       
        $objUsuario = new Usuario();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
    if(isset($_POST['btnCadastrar'])){
            $dados = $_POST;
            $email = $dados['email'];
            $nome  = $dados['nome'];
    //verifica se o e-mail já está cadastrado
            if(!empty($objUsuario->verificaEmail($email))){
                $_SESSION['msg'] = 'Este email já esta cadastrado.';
    //verifica se o nome já está cadastrado            
            }else if(!empty($objUsuario->verificaNome($nome))){
                $_SESSION['msg'] = 'Este nome já está sendo usado.';       
    //verifica se foi inserido e retorna mensagem
            }else if($objUsuario->queryInsert($_POST) == 'ok'){
                $_SESSION['msg'] = "Usuário Cadastrado.";
                 header('location: cadastra-usuario.php');
            }else{
                $_SESSION['msg'] = "Não foi possível cadastrar o usuário, tente novamente ou entre em ontato com o desenvolvedor.";
            }       
    }         
    if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){          
        if($objUsuario->queryDelete($_GET['user']) == 'ok'){
                $_SESSION['msg'] = "Usuário Excluido.";
                header('location: cadastra-usuario.php');             
        }else{
                echo '<script type="text/javascript">alert("Erro em deletar");</script>';
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
            <title>Cadastra Usuário - MVArquitetura</title>
    </head>
<body> 
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"><img src ="images/mveiga-arquitetura-logo1.fw.png" width="50" />MVeiga Arquitetura</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Administração
                        </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="cadastra-usuario.php">Usuários</a>
                              <a class="dropdown-item" href="cadastra-permissao.php">Permissões</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Serviços</a>
                            </div>
                    </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">Meus Dados</a>
                          </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sair.php">Sair</a>
                        </li>
                <li class="nav-item">
                  <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
          </form>
        </div>
    </nav>
        <main role="main" class="container">
            <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
                <?php   
                //Saudaçõ da página
                echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
                ?>
            </div>    
                <div class="my-3 p-3 bg-white rounded shadow-sm">
                   <h6 class="border-bottom border-gray pb-2 mb-0">Novo usuário</h6>
                   <div class="media text-muted pt-3">         
			<form method="POST" action="" class="form-signin">                               
				<label>Nome:</label>
				<input type="text" name="nome" placeholder="Digite nome e sobrenome" required ><br/><br/>
				<label>E-mail:</label>
				<input type="text" name="email" placeholder="Digite e-mail pessoal" required ><br/><br/>
				<label>Senha:</label>
				<input type="password" name="senha" placeholder="Mínimo 8 caracteres" required ><br/><br/>
				<label>Permissão para:</label>
				<select name="permissao">
                                    <option value="0">Selecione...</option>
                                        <?php
                                            foreach($objPermissao->querySelect() as $res){                                  
                                            echo '<option value ='.$res->idPermissao.'>'.$res->permissao.'</option>';                                   
                                        }     
                                        ?>	
				</select><br /><br/>
				<input type="hidden" name="status" value="1" >
				<input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />
                            <div class="alert alert-warning" role="alert">	
				<?php
                                    if(isset($_SESSION['msg'])){
					echo $_SESSION['msg'];
                                        //unset($_SESSION['msg']);
                                    }
				?>
                            </div>
			</form>        
                    </div>
                </div>
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
                   foreach($objUsuario->querySelect() as $res){
                        $a = count($objUsuario->querySelect());
                        echo ' 
                            <tr>
                                <th scope="row">#</th>
                                <td>'.$res->nome . '</td>
                                <td>'.$res->email . '</td>
                                <td>'.$res->dataCriacao . '</td>
                                <td><a href="?acao=alterar&user='.$res->idUsuario.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                                <td><a href="?acao=excluir&user='.$res->idUsuario.'" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                            </tr>';
                    }    
                 ?>
            </table>
            </div>
    </div>
            <!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 
</body>
</html>
<?php
}else{
    $_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
    header("Location: login.php");
}
