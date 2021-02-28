<?php 
unset($_SESSION['msg']);
session_start();
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/Cliente.php';
        require_once 'classes/Permissao.php';
        
        $objCliente = new Cliente();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
        //chama o script preenche endereco pelo cep       
        include 'cep.php'; 
        
// cadastrar novo cliente       
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Cadastrar'){
        
        //verificação do campo nome e nome fantasia
         if(empty($_POST['nome1']) && (empty($_POST['nome2']))){
             
             $cpfForm = $_POST['Cpf'];
     
     echo '<div class="alert alert-warning" role="alert"> "O campo "Nome (PF)" ou "Nome Fantasia (PJ)" está vazio, você precisa preencher este campo no formulário."</div>';
     
 }else{
        //seleciona se o nome vai para PF ou PJ
        if($_POST['TipoPessoa'] == "1"){
            
            $_POST['nome'] = $_POST['nome1'];  
            
            $tipo = 2;
            
        }else{
            $_POST['nome'] = $_POST['nome2'];
            
            $tipo = 1;
        }
            $nome = $_POST['nome'];
            
            $rSocial = $_POST['razaoSocial'];    
            
            //verifica se o nome já está cadastrado
            if(!empty($objCliente->verificaNome($nome))){
                
                $_SESSION['msg'] = 'Este nome já esta cadastrado.';
                
            // }Verificação de razão social
            //else if(!empty($objCliente->verificaRSocial($rSocial, $tipo))){
                
            //    $_SESSION['msg'] = 'Esta razão social já esta cadastrada.';
                
            }else{           
                
                //Insere dados na tabela cliente e retorna ultimo id inserido
                $ultimoId = $objCliente->queryInsert($_POST);
                
                //ultimo id inserido na tabela cliente = $ultimoId;
                //tipo identifica o tipo de documento cadastrado para o cliente CPF(1) ou CNPJ(2)
                //identificador identifica a quem pertence o documento cliente(1), colaborador(2), FUNCIONÁRIO(3) 
            
                    //Monta array para cadastrar documentos
                    //CASO O CLIENTE FOR PESSOA FISICA
                    if($_POST['TipoPessoa'] == "1"){
                        
                        $dados = array (
                        $_POST['id'] = $ultimoId,
                        $_POST['identificador'] = '1',
                        $CPF = $_POST['Cpf'],
                        $RG = 0,
                        $_POST['status'] = '1'
                        );      
                        
                        //cadastra documento
                       $docpf = $objCliente->queryInsertDocPF($dados);
                       
                    //CASO O CLIENTE FOR PESSOA JURIDICA 
                    }else{
                        
                       //PARA INSERIR OS DOCUMENTOS CNPF, INSC ESTADUAL, INSC MUNICIPAL NA TABELA DOCUMENTOS
                        $dad = array (
                        $_POST['id'] = $ultimoId,
                        $_POST['identificador'] = '1',
                        $CNPJ = $_POST['Cnpj'],
                        $IEST    = $_POST['iEstadual'],
                        $IMUN    = $_POST['iMunicipal'],
                        $_POST['status'] = '1'
                        );
                    }
            
                            //insere dados na tabela documento 
                            $doc = $objCliente->queryInsertDocPJ($dad);
                            
                            
                            //Monta array para cadastrar dados de endereco
                                $end = array(
                                $endId = $ultimoId,
                                $endIdent = '1',
                                $endCep = $_POST['cep'],
                                $endRua = $_POST['rua'], 
                                $endNum = $_POST['numero'],
                                $endComp = $_POST['complemento'],
                                $endBairro = $_POST['bairro'],
                                $endCidade = $_POST['cidade'], 
                                $endEst = $_POST['uf'],
                                $endPais = 'BRA',
                                $endStatus = $_POST['status']
                                );
                                
                        //insere dados na tabela endereço
                        $teste = $objCliente->queryInsertEnd($end);   
                        
                        //monta array para cadastrar dados de contato
                        $ctt = array(
                                 $cttId = $ultimoId,
                                 $cttIdent = '1',
                                 $cttDDDtel = $_POST['ddd_telefone'],
                                 $cttTel = $_POST['telefone'], 
                                 $cttDDDcel = $_POST['ddd_celular'],
                                 $cttCel = $_POST['celular'],
                                 $cttEmail = $_POST['email_contato'],
                                 $endStatus = $_POST['status']
                                );
                        
                        //insere dados na tabela contato
                        $insereCTT = $objCliente->queryInsertCTT($ctt);
                        
                        //monta array para inserir dados do responsavel pelo cliente
                        $resp = array(
                            'idCliente'=> $ultimoId,
                            'nomeResponsavel'=>$_POST['nomeResponsavel']
                        );
                        
                        //insere nome do responsavel pelo cliente
                        $responsavel = $objCliente->queryInsereResponsavelCliente($ultimoId, $_POST);
                        
                        //monta array para inserir dados de contato do responsavel
                        $cttResp = array(
                                 'cttId'=> $responsavel,
                                 'cttIdent'=> '4',
                                 'cttDDDtel'=> $_POST['dddTelResponsavel'],
                                 'cttTel'=> $_POST['telResponsavel'], 
                                 'cttDDDcel'=> $_POST['dddCelResponsavel'],
                                 'cttCel'=> $_POST['celResponsavel'],
                                 'cttEmail'=> $_POST['emailResponsavel'],
                                 'endStatus'=> $_POST['status']
                                );
                      
                      //insere dados de contato do responsavel pelo cliente  
                     $cttResponsavel = $objCliente->queryInsereCTTResponsavel($responsavel, $cttResp);  
                     
                     if($cttResponsavel == 'ok'){

                         $_SESSION['msg'] = "Cliente cadastrado com sucesso!";
                         
                     }else{
                         
                         $_SESSION['msg'] = "Não foi possível efetuar o cadastro, tente novamente ou entre em contato com o desenvolvedor.";
                     }
    
      }  
    
    }
    
}
// alterar cliente
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){  
        $dados = $_POST;
        $nome  = $dados['nome'];
unset($_SESSION['msg']);
    if($_GET['tipoCliente'] == '1'){    
        $cpf   = $dados['Cpf'];
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        if($objCliente->queryUpdateClientePF($_GET['user'], $nome) == 'ok'){ 
             //echo "o nome foi alterado com sucesso";  
             //echo $cpf;
            if($objCliente->queryUpdateDocPF($_GET['user'],$cpf)){
                //echo "o cpf foi alterado com sucesso"; 
                
                if($objCliente->queryUpdateEnd($_GET['user'], $dados) == 'ok'){
                
                //echo "Documento Alterado";
                
                if($objCliente->queryUpdateCTT($_GET['user'], $dados) == 'ok'){
                    $_SESSION['msg'] = "Suas alterações foram salvas";
                }
                
            }
               
            }
            
            
        }  
    }else{
        
            if($objCliente->queryUpdateClientePJ($_GET['user'], $dados) == 'ok'){   
            //echo "alterado com sucesso"; 
                
                if($objCliente->queryUpdateDocPJ($_GET['user'], $dados) == 'ok'){
                  //  echo "Doc pj alterado";
                    
                  if($objCliente->queryUpdateEnd($_GET['user'], $dados) == 'ok'){
                
                    //  echo "Documento Alterado";
                
                        if($objCliente->queryUpdateCTT($_GET['user'], $dados) == 'ok'){
                             $_SESSION['msg'] = "Suas alterações foram salvas";
                        }
                
            }
                    
                }
                    
             } 
    }    
}

//excluir cliente
    if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){          
            if($objCliente->queryDeleteCliente($_GET['user']) == 'ok'){
                
                if($objCliente->queryDeleteEndereco($_GET['user']) == 'ok'){
                            
                    if($objCliente->queryDeleteContato($_GET['user']) == 'ok'){
                        
                        if($objCliente->queryDeleteDocumentoPF($_GET['user']) == 'ok'){
                            
                            $_SESSION['msg'] = "Cliente Excluido com sucesso.";
                            //header('location: ?pg');

                        }else{
                            echo '<script type="text/javascript">alert("Erro em deletar");</script>';
                        }
                        
                        if($objCliente->queryDeleteDocumentoPJ($_GET['user']) == 'ok'){
                            
                            $_SESSION['msg'] = "Cliente Excluido com sucesso.";
                           // header('location: ?pg');

                        }else{
                            echo '<script type="text/javascript">alert("Erro em deletar");</script>';
                        }

              
                    }else{
                          echo '<script type="text/javascript">alert("Erro em deletar");</script>';
                    }
              
                }else{
                      echo '<script type="text/javascript">alert("Erro em deletar");</script>';
                }

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
	<title>Cliente - MVArquitetura</title>

<!--funcao seleciona pf ou pj-->
<script type="text/javascript">

function tipoPessoaSel() {
  var pf = document.getElementById("opt-pf").checked;
  if (pf) {
    document.getElementById("pf").style.display = "block";
    document.getElementById("pj").style.display = "none";
  } else {
    document.getElementById("pf").style.display = "none";
    document.getElementById("pj").style.display = "block";
  }
}

</script> 

</head>

<?php include 'pesquisa.php'; ?>

<body>
    
<?php 

include 'menu.php';

?>
<!--********************FIM DO MENU*******************************-->

<main role="main" class="container">
    
    <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
        
        <?php   
        //Saudação da página
        echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
        ?>

    </div><!--fim div "d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm"-->
    
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            
            <table cellspacing="4" cellpadding="6">
                
                <tr>
                    <th>Buscar Cliente:</th>
                </tr>
                
                    <form name="busca" method="GET" action="exibeCliente.php"  class="form-inline my-2 my-lg-0">
                        
                    <tr>  
                        <td>
                                <input type="text" name="cliente" id="search-cliente" placeholder="Nome cliente" size="20" class="form-control"/>
                                <input type="hidden" name="idCliente" id="search-idcliente" />
                                <input type="hidden" name="tipoCliente" id="search-tipocliente" />
                                <input type="hidden" name="pg" />
                                <div id="suggesstion-cliente"></div>  
                        </td>
                        <td><input type="submit" name="btnBusca" value="Buscar" class="btn btn-outline-success my-2 my-sm-0"</td>
                    </tr>
                    
                    </form>   
            </table>
            
                <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?pg=0&acao=novo">Novo Cliente</a></h6>

<div class="container">
    
  <div class="row justify-content-md-center">
      
    <div class="col col-lg-2">
     <!--Espaço do lado esquerdo-->
    </div><!--fim div "col col-lg-2"--></fim>
            
 <?php
 
 //Exibe o form para cadastrar novo cliente
 if(isset($_GET['acao']) AND $_GET['acao'] == 'novo'){ 

echo '
    <div class="media text-muted pt-3"> 
    
        <div class="col-md-auto">
        
            <form method="POST" action="" class="form-signin">
            
                <H5> Cadastrar Novo Cliente</h5>
                
                <hr>
                    Selecione o tipo de cliente
                    
                        <div>
                        
                            <table>
                                <tr>
                                    <td> 
                                        <label for="opt-pf">Pessoa Física</label>
                                        <input id="opt-pf" checked="checked" type="radio" name="TipoPessoa" onclick="tipoPessoaSel();" value="1"/>
                                    </td>
                                    <td> 
                                        <label for="opt-pj">Pessoa Jurídica</label>
                                        <input id="opt-pj" type="radio" name="TipoPessoa" onclick="tipoPessoaSel();" value="2"/>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
            
                                <div id="pf">
                                
                                    <div>
                                    
                                        <label for="cpf">CPF:</label><br/>
                                        <input id="cpf" type="text" name="Cpf" placeholder="Somente números" maxlength="11" autocomplete="off" class="form-control"
                                        '; if ($_SERVER['REQUEST_METHOD'] == "POST") { echo "value=\"" . $cpfForm . "\"";} echo '/><br/>
                                        
                                        <label for="nome">Nome:</label><br/>
                                        <input name="nome1" type="text" id="" autocomplete="off" size="40" class="form-control"
                                        />
                                        <small id="passwordHelpInline" class="text-muted">Este campo é obrigatório*.</small><br/>
                                        
                                    </div>
                                    
                                </div>
    
                            <div id="pj" style="display: none;">
                            
                                <div>
                    
                                    <label for="cnpj">CNPJ:</label><br/>
                                    <input id="cnpj" type="text" name="Cnpj" placeholder="Somente números" autocomplete="off" class="form-control"  /><br/>
                    
                                        <label>Nome Fantasia:</label><br/>
                                        <input name="nome2" type="text" autocomplete="off" size="40" class="form-control" />
                                        <small id="passwordHelpInline" class="text-muted">Este campo é obrigatório*.</small><br/>
                        
                                            <label>Razão Social:</label><br/>
                                            <input type="text" name="razaoSocial" id="" autocomplete="off" size="40" class="form-control" /><br/>
                        
                                        <label>Inscrição Estadual :</label><br/>
                                        <input type="text" name="iEstadual" placeholder="Somente números" autocomplete="off" class="form-control" /><br/>
                    
                                    <label>Inscrição Municipal :</label><br/>
                                    <input type="text" name="iMunicipal" placeholder="Somente números" autocomplete="off" class="form-control" /><br/>
                    
                                </div> 
                                
                              </div>                
    
                                <labeL>CEP:</labeL><br/>
                                <input type="text" name="cep" id="cep" size="10" maxlength="9" onblur=pesquisacep(this.value) class="form-control" /><br/>
                
                                    <label>Logradouro:</label><br/>
                                    <input type="text" name="rua" id="rua" class="form-control" /><br/>
                    
                                        <label>Numero:</label><br/>
                                        <input type="text" name="numero" id="numero" size="4" class="form-control" /><br/>
                        
                                            <label>Complemento:</label><br/>
                                            <input type="text" name="complemento" id="complemento" class="form-control" /><br/>
                        
                                        <label>Bairro:</label><br/>
                                        <input type="text" name="bairro" id="bairro" class="form-control" /><br/>
                                            
                                    <label>Cidade:</label><br/>
                                    <input type="text" name="cidade" id="cidade" class="form-control" /><br/>
                
                                <label>UF:</label><br/>
                                <input type="text" name="uf" id="uf" size="1" class="form-control" /><br/>
                        
                            <label for="ddd_telefone">Telefone:</label><br/>
                            
                                <div class="form-group row center">
                                
                                    <div class="col-xs-2">
                                    <input name="ddd_telefone" id="ddd_telefone" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                                    </div>
                                    
                                    <div class="col-xs-4">
                                    <input type="text" name="telefone" placeholder="Só Números" maxlength="8" size="40"  class="form-control" /><br/>
                                    </div>
                                
                                </div>
                            
                            <label> Celular:</label><br/>
                            
                                <div class="form-group row center">
                                
                                    <div class="col-xs-2">
                                    <input name="ddd_celular" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control" />
                                    </div>
                                    
                                    <div class="col-xs-4">
                                    <input type="text" name="celular" placeholder="Só numeros" maxlength="9" size="40" class="form-control" /><br/>
                                    </div>
                                
                                </div>
                    
                            <label>E-mail:</label><br/>
                            <input name="email_contato" type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" size="30" class="form-control"/><br/>

                        <hr/>
                        
                <label><strong>Responsável pelo Cliente:</strong></label></td>
                
                <input name="nomeResponsavel" type="text" size="40" class="form-control"  />
                
                    <label>Telefone:</label>
                    
                        <div class="form-group row center">
                        
                            <div class="col-xs-2">
                                <input name="dddTelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                            </div>
                            
                            <div class="col-xs-4">
                                <input type="text" name="telResponsavel" placeholder="Só numeros" maxlength="8" size="40" class="form-control"/>
                            </div>   
                            
                        </div>
                        
                            <label>Celular:</label>
                            
                                <div class="form-group row center">
                                
                                    <div class="col-xs-2">                            
                                        <input name="dddCelResponsavel" type="text" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                                    </div>
                                    
                                    <div class="col-xs-4">  
                                        <input type="text" name="celResponsavel" placeholder="Só numeros" maxlength="9" size="40" class="form-control"/>
                                    </div>
                                
                                </div>
                        
                        <label>E-mail:</label>
                        
                            <input name="emailResponsavel" type="text" size="30" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" />

                <input type="hidden" name="status" value="1" ><br/>
                
                    <input type="submit" name="btnCadastrar" value="Cadastrar" class="btn btn-lg btn-primary btn-block" />    
    
                        	';
                        
                            if(isset($_SESSION['msg'])){
                                
                                echo '<div class="alert alert-warning" role="alert">'.$_SESSION['msg'].'</div><!--Fim div "alert alert-warning"-->  ';
                                
                               // unset($_SESSION['msg']);
                            
                            }
                        
                        echo '
                        
                        
                    
                <a href="?pg=0&acao=limpar">Voltar</a>
            
            </form>    
        
        </div><!--fim div "col-md-auto"-->
';
 }
 

//Exibe o form para atualizar usuario
if(isset($_GET['acao']) AND $_GET['acao'] == 'alterar'){ 
    
    $tipo = $_GET['tipoCliente'];
    $userID = $_GET['user'];
    
    echo'
    <div class="media text-muted pt-3"> 

        <div class="col-md-auto">

            <form method="POST" action="" class="form-signin">   
                
                <h5>Alterar/Incluir Dados do Cliente</h5>
                
                    <hr>
                    ';
                    
                        if($tipo == 1){
                            
                            $up = $objCliente->querySelecionaClientePF($userID, $tipo);
                            
                                foreach ($up as $resPF) {};
                                
                                    $cpf = $objFuncao->mask($resPF->cpf, '###.###.###-##');
                                        
                                        echo'   

                                        <label for="nome">Nome:</label><br/>
                                        <input name="nome" type="text" value="'.$resPF->nomeCliente.'" class="form-control" /><br/>
                                        
                                            <label for="cpf">CPF:</label></td>
                                            <input id="cpf" type="text" name="Cpf" value="'.$cpf.'" class="form-control" /><br/>
                                    
                                        <labeL>CEP:</labeL><br/>
                                        <input type="text" name="cep" id="cep" size="10" maxlength="9" value="'.$objFuncao->mask($resPF->cep, '#####-###').'" onblur="pesquisacep(this.value);" class="form-control" />
                                            
                                            <label for="nome">Logradouro:</label><br/>
                                            <input name="rua" type="text" value="'.$resPF->logradouro.'" class="form-control"/><br/>
                                            
                                                <label>N°:</label><br/>
                                                <input name="numero" type="text" value="'.$resPF->numero.'" size="4" class="form-control" /><br/>
                                                
                                                    <label for="nome">Complemento:</label><br/>
                                                    <input type="text" name="complemento"  value="'.$resPF->complemento.'" class="form-control" /><br/>
                                                
                                                <label>Bairro:</label><br/>
                                                <input type="text" name="bairro" value="'.$resPF->bairro.'" class="form-control" /><br/>
                                            
                                            <label>Cidade:</label><br/>
                                            <input type="text" name="cidade" value="'.$resPF->cidade.'" class="form-control" /><br/>
                                        
                                        <label>UF:</label><br/>
                                        <input type="text" name="uf" value="'.$resPF->estado.'" size="1" class="form-control" /><br/>
                                
                                    <label for="ddd_telefone">Telefone</label><br/>
                                    
                                        <div class="form-group row center">
                                        
                                            <div class="col-xs-2">
                                                <input name="ddd_telefone" id="ddd_telefone" type="text" value="'.$resPF->dddTel.'" size="5" placeholder="DDD" maxlength="2" class="form-control" />
                                            </div>
                                            
                                            <div class="col-xs-4">
                                                <input type="text" name="telefone" value="'.$resPF->tel.'" placeholder="Só Números" maxlength="8" size="40" class="form-control" /><br/>
                                            </div>
                                            
                                        </div>
                                        
                                   <label>Celular:</label><br/>
                                    
                                        <div class="form-group row center">
                                        
                                            <div class="col-xs-2">                                        
                                                <input name="ddd_celular" type="text" value="'.$resPF->dddCel.'" size="5" placeholder="DDD" maxlength="2" class="form-control"   />
                                            </div>
                                            
                                            <div class="col-xs-4">    
                                                <input type="text" name="celular" value="'.$resPF->cel.'" placeholder="Só numeros" maxlength="9" size="40" class="form-control"/><br/>
                                            </div>
                                        
                                        </div>
                                        
                                    <label>E-mail:</label><br/>
                                    <input name="email" type="text" value="'.$resPF->email.'" size="30" class="form-control"/><br/>

                                    ';
                        }else{
                            
                            $up = $objCliente->querySelecionaClientePJ($userID, $tipo);
                            
                                foreach ($up as $resPJ) {}
                                
                                   //macara para todos os tipos de documentos
                                    $cnpj = $objFuncao->mask($resPJ->cnpj, '##.###.###/####-##');
                                    
                                        echo'
                                        <label for="nome">Nome Fantasia:</label><br/>
                                        <input name="nome" type="text" value="'.$resPJ->nomeCliente.'" class="form-control" /><br/>
                                        
                                            <label>Razão Social:</label><br/>
                                            <input type="text" name="razaoSocial" value="'.$resPJ->razaoSocial.'" class="form-control" /><br/>
                                                
                                            <label>CNPJ:</label><br/>
                                            <input type="text" name="Cnpj" value="'.$cnpj.'" placeholder="Sómente números" class="form-control" /><br/>
                                        
                                        <label>Inscrição Estadual :</label><br/>
                                        <input type="text" name="insc_estadual" value="'.$resPJ->iEstadual.'" class="form-control" /><br/>
                                    
                                    <label>Inscrição Municipal :</label><br/>
                                    <input type="text" name="insc_municipal" value="'.$resPJ->iMunicipal.'" class="form-control" /><br/>

                                <h5>Endereço</h5>
                                
                            <hr/>
                            
                                <labeL>CEP:</labeL><br/>
                                <input type="text" name="cep" id="cep" size="10" maxlength="9" value="'.$objFuncao->mask($resPJ->cep, '#####-###').'" onblur=pesquisacep(this.value) /><br/>
                                
                                    <label for="nome">Logradouro:</label><br/>
                                    <input name="rua" type="text" value="'.$resPJ->logradouro.'" class="form-control" /><br/>
                                    
                                        <label>N°:</label><br/>
                                        <input name="numero" type="text" value="'.$resPJ->numero.'" size="4" class="form-control"/><br/>
                                        
                                            <label for="nome">Complemento:</label><br/>
                                            <input type="text" name="complemento"  value="'.$resPJ->complemento.'" class="form-control"/><br/>
                                            
                                                <label>Bairro:</label><br/>
                                                <input type="text" name="bairro" value="'.$resPJ->bairro.'" class="form-control" /><br/>
                                                
                                                    <label>Cidade:</label><br/>
                                                    <input type="text" name="cidade" value="'.$resPJ->cidade.'" class="form-control" /><br/>
                                                    
                                                <label>UF:</label><br/>
                                                <input type="text" name="uf" value="'.$resPJ->estado.'" size="1" class="form-control" /><br/>
                                            
                                    <label>Telefone:</label><br/>
                                                
                                        <div class="form-group row center">
                                        
                                            <div class="col-xs-2">                                                
                                                <input name="ddd_telefone" type="text" value="'.$resPJ->dddTel.'" size="5" placeholder="DDD" maxlength="2" class="form-control" />
                                            </div>
                                            
                                            <div class="col-xs-4">    
                                                <input type="text" name="telefone" value="'.$resPJ->tel.'" placeholder="Só Números" maxlength="8" size="40" class="form-control" /><br/>
                                            </div>
                                        </div>
                                        
                                                <label>Celular:</label><br/>
                                                
                                        <div class="form-group row center">        
                                            
                                            <div class="col-xs-2">            
                                                    <input name="ddd_celular" type="text" value="'.$resPJ->dddCel.'" size="5" placeholder="DDD" maxlength="2" class="form-control"/>
                                            </div>
                                            
                                            <div class="col-xs-4">   
                                                    <input type="text" name="celular" value="'.$resPJ->cel.'" placeholder="Só Números" maxlength="9" size="40" class="form-control" /><br/>
                                            </div>
                                        </div>
                                                
                                                <label>E-mail:</label><br/>
                                                <input name="email" type="text" value="'.$resPJ->email.'" size="30" class="form-control" /><br/>
                                        
                            ';
                        }   
                        echo'
                   
                            <input type="hidden" name="status" value="1" >
                            <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />
                            
                    	
                    
                    ';       
                    
                    if(isset($_SESSION['msg'])){
                        
                    echo '<div class="alert alert-warning" role="alert">' .$_SESSION['msg'].'</div><!--fim div "alert alert-warning"-->';
                    
                    }
                    
                    echo '
                    
                    

                <a href="?pg=0&acao=limpar">Voltar</a>

        </form>    

    </div><!--fim div "col-md-auto"-->
    
';  
}  
?>              
 
    </div><!--Fim Espaço central--> 
    
        <div class="col col-lg-2">
            <!--Espaço do lado direito-->
        </div>

</div>
  
</div>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Últimos Clientes Cadastrados</h6>
        <div class="media text-muted pt-3">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                     <th scope="col">E-mail</th>
                    <th scope="col">Tel.</th>
                    <th scope="col">Cel.</th>
                    <th scope="col">Ver</th>
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
$quantreg = $objCliente->querySelectTotal();
 $i = $quantreg;
           foreach($objCliente->querySelectPag($inicial, $numreg) as $res){

          echo ' <tr>
                    <td>'.$i--.'</td>
                    <td>'.$res->nomeCliente . '</td>
                ';
            if(empty($res->email)){
                
                echo '<td><a href="?pg='.$pg.'&acao=alterar&user='.$res->idCliente.'&tipoCliente='.$res->tipoCliente.'" title="Cadastrar e-mail">Cadastrar e-mail</td>';
                
            }else{
                
                echo '<td>'.$res->email . '</td>';
            }

            if($res->dddTel == ('0')){
                
                echo '<td><a href="?pg='.$pg.'&acao=alterar&user='.$res->idCliente.'&tipoCliente='.$res->tipoCliente.'" title="Cadastrar Telefone.">Cadastrar Telelefone</td>';
                
            }else{
                
                echo '<td>('.$res->dddTel .') '.$objFuncao->mask($res->tel, '####-####').'</td>';
                
            }
            
            if($res->dddCel == ('0')){
                
                echo '<td><a href="?pg='.$pg.'&acao=alterar&user='.$res->idCliente.'&tipoCliente='.$res->tipoCliente.'" title="Cadastrar Celular.">Cadastrar Celular</td>';
                
            }else{
                
                echo '<td>('.$res->dddCel .') '.$objFuncao->mask($res->cel, '#####-####').'</td>';
            }
                
        echo'   
                    
                    <td><a href="exibeCliente.php?pg='.$pg.'&acao=ver&user='.$objFuncao->base64($res->idCliente, 1).'&tipoCliente='.$res->tipoCliente.'" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
                    <td><a href="?pg='.$pg.'&acao=alterar&user='.$res->idCliente.'&tipoCliente='.$res->tipoCliente.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                    <td><a href="?pg='.$pg.'&acao=excluir&user='.$res->idCliente.'" title="Excluir esse dado" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
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
      
    </div>
        </div>
<!-- JavaScript (Opcional) -->
            <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
<!--            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                  -->
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script> 

</body>
</html>
    <?php
}else{
	$_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";
	header("Location: login.php");
}
