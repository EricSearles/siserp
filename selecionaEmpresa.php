<?php
session_start();
if(!empty($_SESSION['id'])){
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

        <div class="row justify-content-md-center">
            
	        <h2>Selecione a empresa</h2>
			
		</div>
			
			<div class="row justify-content-md-center">
                        <a href="?empresa=1"><img src="images/mveiga-arquitetura-logo.jpg" /></a>
                        <a href="?empresa=2"><img src="images/logo-tcassessoria.fw.png" /></a>
                        
                <?php           
                 if(isset($_GET['empresa']) && $_GET['empresa']=='1'){
                    $_SESSION['empresa'] = '1';
                ?>
                    <script>window.location.href = "administrativo.php"; </script>
                    <?php   
                     }elseif(isset($_GET['empresa']) && $_GET['empresa']=='2'){
                        $_SESSION['empresa'] = '2';    
                    ?>
                        <script>window.location.href = "administrativo.php"; </script>
                <?php
                }
                ?>
            </div>
        <div class="row justify-content-md-center">
            <a class="nav-link" href="sair.php">Sair</a>
        </div>
	</div>

<!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->

	<script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

<?php
    }else{
            $_SESSION['msg'] = "Página não encontrada";
			header("Location: login.php");
        }

?>
</body>

</html>