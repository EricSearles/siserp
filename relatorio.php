<?php
session_start();
if(!empty($_SESSION['id'])){
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Colaborador.php';
        
        $objColaborador = new Colaborador();
        $objOS = new OrdemServico();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Administração - MVArquitetura</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="css/offcanvas.css" rel="stylesheet">
        <link href="css/sticky-footer.css" rel="stylesheet">

        
</head>
<?php include 'pesquisa.php'; ?>
<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->
    
<main role="main" class="container">
<div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
<?php   echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';?>
</div>  
    
    <div class="my-3 p-3 bg-white rounded shadow-sm">
    
<br/><h6 class="border-bottom border-gray pb-2 mb-0">Selecione o relatório
</h6>
<br/><a href="relatorioColaborador.php">Relatório Colaborador</a>  <br/><br/>
<a href="relatorioCliente.php">Relatório Cliente</a>  
    
    <div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-auto">
     

<hr>

    </div>
      
  </div>
                          
<footer class="footer">
      <div class="container">
<!--        <span class="text-muted">Place sticky footer content here.</span>-->
      </div>
    </footer>          
<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 
<?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
?>
</body>
</html>

