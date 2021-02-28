<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/Cliente.php';
        require_once 'classes/Permissao.php';
        
        $objCliente = new Cliente();
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
    
	<title>Cliente - MVArquitetura</title>
 
</head>
<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
 <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
  <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>

</div>
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
    </form>
    <br/><h6 class="border-bottom border-gray pb-2 mb-0"><a href="cliente.php?pg=&acao=novo">Novo Cliente</a>
</h6>
<!--****************************Corte**********************************  -->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-2">
     <!--Espaço do lado esquerdo-->
    </div>
            
        <div class="media text-muted pt-3">     
        
        </div>';         

</div><!--Fim Espaço central--> 
    <div class="col col-lg-2">
    <!--Espaço do lado direito-->
    </div>
  </div>
</div>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Dados Cliente</h6>
        <div class="media text-muted pt-3">
            
<?php
$user = $_GET['user'];
$tipo = $_GET['tipoCliente'];
if($tipo == '1'){
$clientePF = $objCliente->querySelecionaClientePF($user, $tipo);
foreach ($clientePF as $res) {
    //print_r($res);
}
echo 'Nome : '.  $res->nomeCliente;
echo '<br/>CPF : '.  $res->cpf;
echo '<br/>Telefone : ('.  $res->dddTel.') '.$res->tel;
echo '<br/>Celular : ('.  $res->dddCel.') '.$res->cel;
echo '<br/>E-mail : '.  $res->email;
echo '<br/>CEP : '.  $res->cep;
echo '<br/>Endereço : '.  $res->logradouro.', '.$res->numero;
echo '<br/>Bairro : '.  $res->bairro;
echo '<br/>Cidade : '.  $res->cidade.' - '.$res->estado;

echo '<br/>Serviços<hr/>';

}else{
 $clientePJ = $objCliente->querySelecionaClientePJ($user, $tipo);   
 foreach ($clientePJ as $res) {
   // print_r($res);
     //print_r($clientePJ);
}
echo 'Nome Fantasia : '.  $res->nomeCliente;
echo '<br/>Razão Social : '.  $res->razaoSocial;
echo '<br/>CNPJ : '.  $res->cnpj;
echo '<br/>Inscrição Estadual : '.  $res->iEstadual;
echo '<br/>Inscrição Municipal : '.  $res->iMunicipal;
echo '<br/>Telefone : ('.  $res->dddTel.') '.$res->tel;
echo '<br/>Celular : ('.  $res->dddCel.') '.$res->cel;
echo '<br/>E-mail : '.  $res->email;
echo '<br/>CEP : '.  $res->cep;
echo '<br/>Endereço : '.  $res->logradouro.', '.$res->numero;
echo '<br/>Bairro : '.  $res->bairro;
echo '<br/>Cidade : '.  $res->cidade.' - '.$res->estado;

echo '<br/>Serviços<hr/>';
}
?>

<br/>
<hr>

    </div>
         <a href="cliente.php?pg=0&acao=limpar">Voltar</a>  
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
