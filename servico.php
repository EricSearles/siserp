<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Pagamento.php';
 //chama o script preenche endereco pelo cep       
 //       include 'cep.php';
        
        $objOS = new OrdemServico();
        $objPagamento = new Pagamento(); 
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
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
    
	<title>OS - MVArquitetura</title>
       
</head>
<script type="text/javascript">
$('#modal').on('shown.bs.modal', function () {
  $('#meuInput').trigger('focus')
})
</script>

<?php include 'pesquisa.php'; ?>
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
    <table cellspacing="4" cellpadding="6">
        <tr>
            <th>Buscar por:</th>
        </tr>
    <form name="busca" method="POST" action="" enctype=""  class="form-inline my-2 my-lg-0">
        <tr>  
            <td>
                    <input type="text" name="nDocumento" id="search-documento" placeholder="Nº Documento" size="20"/>
                    <input type="hidden" name="idOSDoc" id="search-idOrdemServico" />
                    <div id="suggesstion-documento">            
            </td> 
            <td>
                    <input type="text" name="nProcesso" id="search-processo" placeholder="Nº Processo" size="20"/>
                    <input type="hidden" name="idOSPro" id="search-idOS" />
                    <div id="suggesstion-processo">            
            </td> 
                        <td>
                    <input type="text" name="cliente" id="search-cliente" placeholder="Cliente" size="20"/>
                    <input type="hidden" name="idCliente" id="search-idcliente" />
                    <div id="suggesstion-cliente"></div>  
            </td>
            <td>
                    <input type="text" name="colaborador" id="search-colaborador" placeholder="Solicitante" size="20"/>
                    <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                    <div id="suggesstion-colaborador"></div>       
            </td>
            <td>
                    <input type="text" name="responsavel" id="search-usuario" placeholder="Responsável pela O.S." size="20"/>
                    <input type="hidden" name="idUsuario" id="search-idusuario" />
                    <div id="suggesstion-usuario">            
            </td> 
 

        </tr>
        <tr><td><input type="submit" name="btnBusca" value="Buscar" class="btn btn-outline-success my-2 my-sm-0"</td></tr>
        
    </form>   
    </table>
<br/><h6 class="border-bottom border-gray pb-2 mb-0">Ordem de Serviço
</h6>
<!--****************************Corte**********************************  -->
<div class="container">
  <div class="row justify-content-md-center">
<!--    <div class="col col-lg-2">
     Espaço do lado esquerdo
    </div>         -->
<div class="media text-muted pt-3"> 
    <!--<div class="col-md-auto">-->
    <?php
    if(isset($_POST['btnBusca']) && $_POST['btnBusca'] == "Buscar"){
        
        //print_r($_POST);
        $opcao = $_POST;
    switch($opcao){
            case (!empty($_POST['nDocumento'])): 
        $busca= $opcao['idOSDoc'];
        $id = $busca;
        foreach($objOS->querySelecionaImpressao($id) as $res){
            
            include 'forms/resDocumento.php';
        }
        
         break;
            
            case (!empty($_POST['nProcesso'])): 
        $busca= $opcao['idOSPro'];
        $id = $busca;
        foreach($objOS->querySelecionaImpressao($id) as $res){
            
            include 'forms/resDocumento.php';
        }
                break;
            
            case (!empty($_POST['idCliente'])): 
                $busca= $opcao['idCliente'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idCliente = '.$busca;
                $pg = $_GET['pg'];
               
                include 'forms/resDocumento1.php';
                break;
            
            case (!empty($_POST['idColaborador'])): 
                $busca= $opcao['idColaborador'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idColaborador = '.$busca;
                $pg = $_GET['pg'];
               
                include 'forms/resDocumento1.php';
                break;
            
            case (!empty($_POST['idUsuario'])): 
                echo 'Busca Usuario'; 
                $busca= $opcao['idUsuario'];
                $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idResponsavel = '.$busca;
                $pg = $_GET['pg'];
               
                include 'forms/resDocumento1.php';
                break;
        
        
    }
    }
    
    
        //$busca= $_GET['busca'];
//        $id = 4510;//$objFuncao->base64($busca, 2);
//        
//        foreach($objOS->querySelecionaImpressao($id) as $res){
//            //print_r($res);
//            //$parcela = array($res->parcela);
//            //print_r($parcela);
//            
//        }
        //include 'forms/resDocumento.php';
        ?>
 <!--*****************************************
 O FORM VAI AQUI
 ********************************************->
    
 <?php
 
  ?>              
<!--    </div>Fim Espaço central -->
  </div>
<!--    <div class="col col-lg-2">
    Espaço do lado direito
    </div>-->
  </div>


</div>

<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
<!--          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
  <!--          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>-->
<!--           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 
</body>
</html>
    <?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
