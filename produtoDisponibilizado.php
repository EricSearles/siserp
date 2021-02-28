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
        if(!empty($objServico->verificaProduto($dados))){
            $_SESSION['msg'] = 'Este produto já esta cadastrado.';     
////verifica se foi inserido e retorna mensagem
        }else{
            if($objServico->queryInsertProduto($dados) == 'ok'){
            $_SESSION['msg'] = "Novo produto cadastrado com sucesso.";
////             //header('location: servicoDisponibilizado.php');
             }else{
            $_SESSION['msg'] = "Não foi possível cadastrar este produto, tente novamente ou entre em cntato com o desenvolvedor.";
            } 
        }
    }  
   
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){
        $id = $_GET['prod'];
        $dados = $_POST;
        if($objServico->queryUpdateProduto($dados, $id)){
            $_SESSION['msg'] = "Dados alterados.";
        }else{
            $_SESSION['msg'] = "Não foi possível fazer a alteração dos dados.";
        }
      
        
    }  
    
    
    if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){     
        $id = $_GET['prod'];
            if($objServico->queryDeleteProduto($id) == 'ok'){
                $_SESSION['msg'] = "Registro Excluido.";
               //header('location: servicoDisponibilizado.php');
              
            }else{
                $_SESSION['msg'] = "Não foi possível excluir o produto.";
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
	<title>Produto - MVArquitetura</title>
</head>
<body>
<?php include 'menu.php';?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
    <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>
    </div>
    <div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?acao=novo&pg=0">Novo Produto</a></h6>
    
<div class="container">
  <div class="row justify-content-md-center">
  <!--<div class="col col-lg-2">
  
    </div>-->
    <div class="col-md-auto">
<?php
 //Exibe o form para cadastrar nova permissão
if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){            
    echo ' 
            <div class="media text-muted pt-3"> 
            
            
            <form method="POST" action="" class="form-signin">

                    <h5>Cadastrar Produto</h5>
                    
                    <hr/>
                    
                    <label>Código:</label>
                    <input type="text" name="codigoProduto" placeholder="Digite o código do produto" class="form-control" size= "20">
                    
                    <label>Produto: </label>
                    <input type="text" name="nomeProduto" placeholder="Digite o nome do produto" required="required" size="45" class="form-control">
                    
                    <label>Descrição: </label>
                    <textarea type="text" name="descricao" class="md-textarea form-control" cols="35" rows="3"  ></textarea>
                    
                    <label>Capacidade/Tamanho: </label>
                    <input type="text" name="qtdProduto"  class="form-control" required="required" />
                    
                    <label>Unidade Medida: </label></td> 
                    <select name="unidadeMedida" class="form-control" required="required">
                    <option value="0">Selecione...</option>';

                //Preenche o select permissao
                foreach($objServico->querySelecionaUnMedida() as $resMedida){                                    
                        echo '<option value ='.$resMedida->idUnidadeMedida.'>'.$resMedida->unidadeMedida.' - '.$resMedida->sigla.'</option>';                                   
                }  

                    echo'
                        </select>
                
                <label>Fornecedor: </label>
                        <select name="fornecedor" class="form-control" required="required">
                        <option value="">Selecione...</option>';

                //Preenche o select permissao
                foreach($objServico->querySelecionaFornecedor() as $resFornecedor){                                    
                       echo '<option value ='.$resFornecedor->idFornecedor.'>'.$resFornecedor->nomeFornecedor.'</option>';
                       
                }  
               
            echo'
                
                        </select>
                        ';
            if(empty($resFornecedor)){
                //print_r($resFornecedor); 
                echo'
                    <div class="alert alert-warning" role="alert">Você ainda não cadastrou fornecedores - <a href="fornecedor.php?pg=&acao=novo">Cadastrar Agora</a>
                </div>';
            }
            echo'
                <input type="hidden" name="status" value="1" >
                    <br/><input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />
                
                	';
                            if(isset($_SESSION['msg'])){
                            echo '<div class="alert alert-warning" role="alert">'.$_SESSION['msg'].'</div>';

                            }
                            echo '

               <a href="?pg=0&acao=limpar">Voltar</a>';
                            
            echo'
            </form>
        </div>
         ';    
}    
//Alterar produto      
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){  
    
        foreach($objServico->queryExibeUpdateProduto($_GET['prod']) as $res){
            
        }                                    
        
        
    echo ' 

    <div class="media text-muted pt-3"> 
    
            <form method="POST" action="" class="form-signin">
                
                <h5>Alterar Produto</h5>
                
                <hr/>
                
                <label>Código:</label>
                
                <input type="text" name="codigoProduto" value="'.$res->codigo.'" class="form-control"/>
                
                <label>Produto: </label>
                <input type="text" name="nomeProduto" value="'.$res->nomeProduto.'"  class="form-control"/>
                
                <label>Descrição: </label>
                <textarea type="text" name="descricao" class="md-textarea form-control" cols="35" rows="3"  >'.$res->descricao.'</textarea>
                
                <label>Capacidade/Tamanho: </label>
                <input type="text" name="qtdProduto"  value="'.$res->medida.'" class="form-control" >
                
                <label>Unidade Medida: </label>
                <select name="unidadeMedida" class="form-control">
                        <option value="'.$res->idUnidadeMedida.'">'.$res->unidadeMedida.'</option>';

                //Preenche o select permissao
                foreach($objServico->querySelecionaUnMedida() as $resMedida){                                    
                        echo '<option value ='.$resMedida->idUnidadeMedida.'>'.$resMedida->unidadeMedida.'</option>';                                   
                }  

                    echo'
                        </select>
                        
                        <label>Fornecedor: </label>
                        <select name="fornecedor" class="form-control">
                        <option value="'.$res->idFornecedor.'">'.$res->nomeFornecedor.'</option>';

                //Preenche o select permissao
                foreach($objServico->querySelecionaFornecedor() as $resFornecedor){                                    
                       echo '<option value ='.$resFornecedor->idFornecedor.'>'.$resFornecedor->nomeFornecedor.'</option>';                                   
                }  

            echo'
                        </select>
                
                <input type="hidden" name="status" value="1" >
                    <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />
                    	';
                            if(isset($_SESSION['msg'])){
                            echo '<div class="alert alert-warning" role="alert">'.$_SESSION['msg'].'</div>';

                            }
                            echo '

                <a href="?pg=0&acao=limpar">Voltar</a>
            </form>
        </div>
         ';    
}         
 ?>       
    </div>    


</div>
  
</div>
           <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Produtos Cadastrados</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr>
                    <th scope="row">#</th>
                    <th scope="col">Código</th>
                    <th scope="col">Produto</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Fornecedor</th>
                    <!--<th scope="col">Ver</th>-->
                    <th scope="col">Editar</th>
                    <th scope="col">Excluir</th>
                </tr>
                </thead>
<?php
$pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 10;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;

 //conta a quantidade total de registros na tabela
$quantreg = $objServico->querySelectTotalProduto();
 $i = $quantreg;
           foreach($objServico->querySelectPagProduto($inicial, $numreg) as $res){
 //              print_r($res);
//
          echo ' <tr>
                    <td>'.$i--.'</td>
                    <td>'.$res->codigo . '</td>
                    <td>'.$res->nomeProduto . '</td>
                    <td>'.$res->descricao .'</td>
                    <td>'.$res->nomeFornecedor .'
                   <!-- <td><a href="?pg='.$pg.'&acao=ver&prod='.$res->idProduto.'" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>-->
                    <td><a href="?pg='.$pg.'&acao=alterar&prod='.$res->idProduto.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                    <td><a href="?pg='.$pg.'&acao=excluir&prod='.$res->idProduto.'" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                </tr>
';
               
           } 

$quant_pg = ceil($quantreg/$numreg);	
$quant_pg++;		// Verifica se esta na primeira página, se nao estiver ele libera o link para anterior	
if ( $pg > 0) { 		
echo '<tr><td colspan=8><a href=?pg='.$anterior .' ><b>« anterior</b></a></td></tr>';	
} else { 		
echo "";	
}		// Faz aparecer os numeros das página entre o ANTERIOR e PROXIMO	
for($i_pg=1;$i_pg<$quant_pg;$i_pg++) { 		
// Verifica se a página que o navegante esta e retira o link do número para identificar visualmente		
if ($pg == ($i_pg-1)) { 			
echo "<tr><td colspan=8 align=center> [$i_pg] </td></tr>";		
} else {			
$i_pg2 = $i_pg-1;			
echo ' <tr><td colspan=8 align=center><a href="?pg='.$i_pg2.'" ><b>'.$i_pg.'</b></a></td></tr> ';
		}
		}		
		// Verifica se esta na ultima página, se nao estiver ele libera o link para próxima	
		if (($pg+2) < $quant_pg) {
			echo '<tr><td colspan=8 align=center><a href=?pg='.$proximo .' ><b>próximo »</b></a></td></tr>';	
			} else {
				echo "<tr><td colspan=8 align=center></td></tr>";

				}
?>
</table>
    
    </div><!--Fim Espaço central--> 
    <div class="col col-lg-2">
    <!--Espaço do lado direito-->
    </div>
  </div>
            <!-- JavaScript (Opcional) -->
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