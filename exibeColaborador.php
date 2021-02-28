<?php 
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Colaborador.php';
        require_once 'classes/Pagamento.php';
        
        $objOS = new OrdemServico();
        $objPagamento = new Pagamento(); 
        $objFuncao = new Funcoes();
        $objColaborador = new Colaborador();
        //$objOrcamento = new Orcamento();
        
    if(isset($_GET['acao']) AND $_GET['acao'] == "limpar"){
        unset($_SESSION['msg']);
    }
    $pg = 0;
        
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
	<title>Dados Colaborador - MVArquitetura</title>
       
</head>

<?php include 'pesquisa.php'; ?>
<body>
<?php 

    include 'menu.php';

?>

<main role="main" class="container">
    
    <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
      
        <?php   
          
            //Saudaçõ da página
            echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
            
        ?>
    
    </div>

    <div class="container">
        
        <div class="row justify-content-md-center">
            
        <!--<div class="col col-lg-2">
             Espaço do lado esquerdo
            </div> -->
            
            <div class="media text-muted pt-3"> 
            
                <table  cellpadding="6" class="table">
                    
                    <tr>
                        <th colspan="6" align="center" ><h6>Dados do Colaborador</h6></th>
                    </tr>
   
<?php

    if(isset($_GET['btnBusca'])){
        
       $USER = $objFuncao->base64($_GET['idColaborador'], 1);
       
       $user = $objFuncao->base64($USER, 2);
       
   }else{
       
        $user = $objFuncao->base64($_GET['user'], 2);
   }

        $tipo = $_GET['tipoColaborador'];
        
            if($tipo == '1'){
                
                $colaboradorPF = $objColaborador->querySelecionaColaboradorPF($user, $tipo);
                
                    foreach ($colaboradorPF as $res) {}
                    
                    echo '
                        <tr>
                        <td>Nome : </td><td colspan="6">'.  $res->nomeColaborador.'</td>
                        </tr>
    
                            <tr>
                                <td>CPF : </td><td colspan="6">
                                
                                ';
                                    
                                        if(empty($res->cpf)){
                                            
                                            echo '
                                                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar CPF">Cadastrar CPF</td>
                                            ';
                                            
                                        }else{
                                            
                                            echo $objFuncao->mask($res->cpf,'###.###.###-##').'</td>';
                                            
                                        }
                
                                echo'
                                
                            </tr>
                                <tr>
                                <td>Telefone : </td><td colspan="6">
                                ';
                                    
                                    if($res->dddTel == ('0')){
                                        
                                        echo '
                                            <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Telefone.">Cadastrar Telelefone</td>
                                        ';
                                    
                                    }else{
                                        
                                        echo '('.$res->dddTel .') '.$objFuncao->mask($res->tel, '####-####').'</td>';
                                        
                                    }
                        
                                echo'
                                </tr>
                                
                                    <tr>
                                        <td>Celular : </td><td colspan="6">
                                    ';
                                    
                                            if($res->dddCel == ('0')){
                                                
                                                echo '
                                                    <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Celular.">Cadastrar Celular</td>
                                                ';
                                                
                                            }else{
                                                
                                                echo '('.$res->dddCel .') '.$objFuncao->mask($res->cel, '#####-####').'</td>';
                                            }
                                    echo'
                                    </tr>
                                    
                                <tr>
                                    <td>E-mail : </td><td colspan="6">
                                    ';
                                    
                                        if(empty($res->email)){
                                            
                                            echo '
                                                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar e-mail">Cadastrar E-mail</td>
                                            ';
                                            
                                        }else{
                                            
                                            echo $res->email . '</td>';
                                            
                                        }
                
                                echo'
                                </tr>
    
                            <tr>
                                <td>CEP : </td><td colspan="6">
                                ';
                
                                 if(empty($res->cep)){
                                     
                                     echo '
                                        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar cep">Cadastrar CEP</td>
                                            ';
                                    }else{
                                        echo $res->cep . '</td>';
                                    } 
                             
                            echo'
                            </tr>
                        <tr>
                        <td>Endereço : </td><td colspan="6">
                        ';
                        
                         if(empty($res->logradouro)){
                             
                             echo '
                                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar endereco">Cadastrar Endereço</td>
                                ';
                                    
                                }else{
                                    
                                    echo $res->logradouro.', '.$res->numero.'</td>';
                                } 
                        
                        echo'        
                        </tr>
                        
                    <tr>
                        <td>Bairro : </td><td colspan="6">
                        
                        ';
                        
                         if(empty($res->bairro)){
                             
                             echo '
                                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar bairro">Cadastrar Bairro</td>
                                ';
                                    
                                }else{
                                    
                                    echo $res->bairro.'</td>';
                                    
                                } 
                
                    echo'
                    </tr>
                    
                <tr>
                    <td>Cidade : </td><td colspan="6">
                    ';
                    
                     if(empty($res->cidade)){
                         
                         echo '
                            <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar cidade">Cadastrar Cidade</td>
                            ';
                            
                        }else{
                            
                                echo $res->cidade.' - '.$res->estado.'</td>';
                                
                        } 
                
                echo'
                
                </tr>
                ';


}else{
    
 $colaboradorPJ = $objColaborador->querySelecionaColaboradorPJ($user, $tipo); 
 
    foreach ($colaboradorPJ as $res) {}
echo '   
    <tr>
    <td>Nome Fantasia : </td><td colspan="6">'.  $res->nomeColaborador.'</td>
    </tr>
    
        <tr>
            <td>Razão Social :  </td><td colspan="6">
            ';
            
                 if(empty($res->razaoSocial)){
                     echo '
                    <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Razão Social">Cadastrar Razão Social</td>
                    ';
                }else{
                    echo $res->razaoSocial.'</td>';
                } 
        
        echo'
        </tr>
    <tr>
    <td>CNPJ :  </td><td colspan="6">
            ';
     if(empty($res->cnpj)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar CNPJ">Cadastrar CNPJ</td>
                ';
            }else{
                echo $objFuncao->mask($res->cnpj, '##.###.###/####-##').'</td>';
            } 
    
    echo'
    </tr>
    <tr>
    <td>Inscrição Estadual :  </td><td colspan="6">
    ';
     if(empty($res->iEstadual)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Insc Estadual">Cadastrar Inscrição Estadual</td>
                ';
            }else{
                echo $res->iEstadual.'</td>';
            } 
    
    echo'
    </tr>
    <tr>
    <td>Inscrição Municipal :  </td><td colspan="6">
    ';
     if(empty($res->iMunicipal)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Insc Municipal">Cadastrar Inscrição Municipal</td>
                ';
            }else{
                echo $res->iMunicipal.'</td>';
            } 
    
    echo'
    </tr>
    
    <tr>
        <td>Telefone : </td><td colspan="6">
        ';
        
            if($res->dddTel == ('0')){
                
                echo '
                    <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Telefone.">Cadastrar Telelefone</td>
                    ';
                    
            }else{
                
                echo '('.$res->dddTel .') '.$objFuncao->mask($res->tel, '####-####').'</td>';
                
            }
            
        echo'
    </tr>
    
    <tr>
    <td>Celular : </td><td colspan="6">
    ';
                if($res->dddCel == ('0')){
                echo '
                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar Celular.">Cadastrar Celular</td>
                ';
            }else{
                echo '('.$res->dddCel .') '.$objFuncao->mask($res->cel, '#####-####').'</td>';
            }
    echo'
    </tr>
    <tr>
    <td>E-mail : </td><td colspan="6">
    ';
                if(empty($res->email)){
                echo '
                <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar e-mail">Cadastrar E-mail</td>
                ';
            }else{
                echo $res->email . '</td>';
            }
    
    echo'
    
    </tr>
    
    <tr>
    <td>CEP : </td><td colspan="6">
    ';
    
     if(empty($res->cep)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar cep">Cadastrar CEP</td>
                ';
            }else{
                echo $res->cep . '</td>';
            } 
     
    
    echo'
    </tr>
    <tr>
    <td>Endereço : </td><td colspan="6">
    ';
     if(empty($res->logradouro)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar endereco">Cadastrar Endereço</td>
                ';
            }else{
                echo $res->logradouro.', '.$res->numero.'</td>';
            } 
    
    echo'        
    </tr>
    <tr>
    <td>Bairro : </td><td colspan="6">
    ';
     if(empty($res->bairro)){
         echo '
       <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar bairro">Cadastrar Bairro</td>
                ';
            }else{
                echo $res->bairro.'</td>';
            } 
    
    echo'
    </tr>
    <tr>
    <td>Cidade : </td><td colspan="6">
    ';
     if(empty($res->cidade)){
         echo '
        <a href="colaborador.php?pg='.$pg.'&acao=alterar&user='.$objFuncao->base64($res->idColaborador, 1).'&tipoColaborador='.$res->tipoColaborador.'" title="Cadastrar cidade">Cadastrar Cidade</td>
                ';
            }else{
                echo $res->cidade.' - '.$res->estado.'</td></tr>';
            } 
}
    echo'
    <tr>
    <th colspan="6">Orçamentos Solicitados</th>
    <tr>
    <tr>
    <td>Cliente</td><td> Data Pedido</td><td>Data Validade</td><td>Situação</td><td> Ver </td>
    </tr>';
foreach($objColaborador->querySelecionaOrcamento($res->idColaborador) as $dados){
//print_r($dados);
    $busca = $objFuncao->base64($dados->idOrcamento, 1);
echo '

    <td>'.$dados->nomeCliente.'</td><td>'.$objFuncao->formataData($dados->data).'</td><td>'.$objFuncao->formataData($dados->validade).'</td><td>'.$dados->situacao.'</td>
        <td><a href="imprimeOrcamento.php?pg=0&busca='.$busca.'&solicitante=colaborador " title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
    </tr>
        ';      
     
}
       


echo '<tr><th  colspan="6">Serviços Realizados</th></tr>
    <tr>
    <td>Cliente</td><td> Data Pedido</td><td>Data Validade</td><td>Situação</td><td> Ver </td>
    </tr>';
foreach($objColaborador->querySelecionaServico($res->idColaborador) as $servico){
    $busca = $objFuncao->base64($servico->idOrdemServico, 1);
echo '

    <td>'.$servico->nomeCliente.'</td><td>'.$objFuncao->formataData($servico->data).'</td><td>'.$objFuncao->formataData($servico->validade).'</td><td>'.$servico->situacao.'</td>
        <td><a href="exibeServico.php?pg=0&busca='.$servico->idOrdemServico.'&solicitante=colaborador " title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
    </tr> 
        ';      
     
}


?>
    
        
        <tr><td  colspan="6"><a href="colaborador.php?pg=0&acao=limpar">Voltar</a></td></tr>
    </table>
</div><!--Fim media text-muted pt-3 -->  
            
</div><!--Fim  row justify-content-md-center -->
<!--    <div class="col col-lg-2">
Espaço do lado direito
</div>-->
</div><!--Fim container -->
</div><!--Fim my-3 p-3 bg-white rounded shadow-sm -->

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
