<?php

session_start();

if(isset($_GET['recuperar'])){
    $email = $_GET['email'];
    echo $email;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login - MVArquitetura</title>
	<link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="form-signin" style="background: #FFFFFF">
			<!--<h2>Área restrita</h2>-->

				<img src="images/mveiga-arquitetura-logo.jpg" alt="Logo MVeiga Arquitetura" name="Logo">

					<form name="login" method="POST" action="valida.php">
						<label>Login:</label>
						<input type="text" name="usuario" placeholder="Seu usuário" required class="form-control" ><br>
						<label>Senha:</label>
						<input type="password" name="senha" placeholder="Sua senha" required class="form-control" ><br>
						<input type="submit" name="btnLogin" value="Entrar" class="btn btn-lg btn-primary btn-block"><br>
                                                 <!--<label><a href ="?acao=pass">Esqueci minha senha</a></label>-->
                                               
                                <?php 
				//retorna mensagem quando o arquivo valida.php foi acessado diretamente
				if(isset($_SESSION['msg'])){
				   //unset($_SESSION['msg']); 
				echo '<div class="alert alert-warning" role="alert"> '. $_SESSION['msg'].'</div>';
				
				
				}
				 ?>
                                 
                        <?php
                            if(isset($_GET['acao'])&& $_GET['acao'] == 'pass'){
                                //$_SESSION['msg']= 'Digite seu e-mail cadastrado.';
                                echo'
                                <form name="ateraSenha" method="GET" action="recuperaSenha.php" >
                                 <label>Digite o e-mail cadastrado:</label>
                                 <input type="email" name="email"  placeholder="Seu e-mail" class="form-control" /><br/>
                                 <input type="submit" name="recuperar" value="Recuperar Senha" class="btn btn-lg btn-primary btn-block" /><br/>
                                <a href="?">Voltar</a>
                                </form>';  
                            }
                        ?>
					</form>
			<!-- JavaScript (Opcional) -->
		    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
		    <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		    <script src="js/bootstrap.min.js"></script>
		   
		</div>
	</div>
</body>
</html>