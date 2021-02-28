<?php
session_start();

    //verifica Session
    if(!empty($_SESSION['id'])){
            require_once 'classes/Funcoes.php';
            require_once 'classes/OrdemServico.php';
            require_once 'classes/Permissao.php';
            require_once 'classes/Usuario.php';
            
            $objOS = new OrdemServico();
            $objUsuario = new Usuario();
            $objFuncao = new Funcoes();
            $objPermissao = new Permissao();
            
                //seleciona empresa
               if(isset($_GET['empresa'])){
                   $empresa = $_GET['empresa'];
               }else{
                   $empresa = '1';
               }
           
                    //cadastra compromisso na agenda
                   if(isset($_POST['envia'])&& $_POST['envia']== "Cadastrar"){
                       $dados = $_POST;
                       if($objUsuario->queryInsertAgenda($dados) == 'ok'){
                           
                           $_SESSION['msg'] = "Compromisso Cadastrado.";
                       }else{
                           
                           $_SESSION['msg'] = "Não foi possível cadastrar o compromisso, tente novamente.";
                       }
                   }  
                   
            // Altera dados na agenda   
               if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Alterar'){  
                    $dados = $_POST;
                    if($objUsuario->queryUpdateAgenda($dados) == "ok"){
                        echo "Dados Alterados";
                        
                    }else{
                        
                        echo "Não foi possível alterar os dados";
                    }
                    
               }
        
        //Exclui dados na agenda     
              if(isset($_GET['acao']) AND $_GET['acao'] == 'excluir'){     
                  
                  if($objUsuario->queryDeleteAgenda($_GET['id']) == 'ok'){
                      
                      $_SESSION['msg'] = "Compromisso excluido.";
                  }else{
                      
                      $_SESSION['msg'] = "Não foi possível excluir o compromisso.";
                  }
          }
            
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

<body>
<?php include 'menu.php'; ?>
<!--********************FIM DO MENU*******************************-->  
    <main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
    <?php   echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';?>
    </div>  
    <div class="container">
    <div class="row justify-content-md-center">
    <div class="col col-lg-auto">
    <br/><br/>

    <?php
      //Exibe o form agenda 
      if(isset($_GET['agenda'])&& $_GET['agenda']=='1'){
          
          echo '<div class="col-md-auto">
          
                    <table class="table table-sm">
                        <thead>
                            <tr class="table-success" align="center">
                              <th colspan="6">Agenda de Compromissos</td>
                            </tr>
                    </table>
                    
                        <form name="agenda" action="" method="POST" enctype="multipart/form-data">
                        
                            <label>Compromisso:</label> <input type="text" name="compromisso" size="45"  required="required" class="form-control" /> <br/>
                                <label>Vencimento:</label> <input type="date" name="vencimento" size="5" required="required" maxlength="10" name="date" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" min="2019-01-01" max="2030-12-31"  class="form-control"/> <br/>
                                    <label>Valor: </label> R$ <input type="text" name="valor" size="4" required="required" placeholder="000,00 - Use vírgula para separar centavos" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$" class="form-control"/><br/>
                                        <label>Anotação</label>
                                        <textarea name="anota" class="md-textarea form-control"  rows="3"></textarea><br/><br/>
                                    <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
                                <input type="hidden" name="status" value="1" />
                            <input type="submit" name="envia" value="Cadastrar" class="btn btn-lg btn-primary btn-block"/>
                            
                        </form> <br/><br/>';
      }
                            //Exibe o form edita agenda
                            if(isset($_GET['acao'])&& $_GET['acao']=='alteraAgenda'){
                                
                            $up = $objUsuario->querySelecionaAgendaUp($_GET['id']);
                            
                                foreach($up as $update){
                                    
                                    echo '<div class="col-md-auto">
                                    
                                        <table class="table table-sm">
                                            <thead>
                                                <tr class="table-success" align="center">
                                                  <th colspan="6">Agenda de Compromissos</td>
                                                </tr>
                                        </table>
                                        
                                            <form name="agenda" action="" method="POST" enctype="multipart/form-data">
                                            
                                                <label>Compromisso:</label> <input type="text" name="compromisso" size="45" value="'.$update->compromisso.'" required="required" class="form-control" /> <br/>
                                                    <label>Vencimento:</label> <input type="text" name="vencimento" size="5" value="'.date('d/m/Y', strtotime($update->vencimento)).'" required="required" class="form-control"/> <br/>
                                                        <label>Valor: </label> R$ <input type="text" name="valor" value="'.$objFuncao->SubstituiPonto($update->valor).'" size="4" required="required" class="form-control"/><br/>
                                                            <label>Anotação</label>
                                                        <textarea name="anota" class="md-textarea form-control"  rows="3">'.$update->anotacao.'</textarea><br/><br/>
                                                    <input type="hidden" name= "idAgenda" value="'.$_GET['id'].'" />
                                                <input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block"/>
                                            
                                            </form> <br/><br/>';
                                }
                            }
//Quando altera a data não esta funcionando a data só está funcionando se for colocada em formato americano                      

 ?>
    <!--Exibe tabela agenda-->
        <table class="table table-sm">
            <thead>
                <tr class="table-success" align="center">
                  <th colspan="6">Serviços a vencer</td>
                </tr>       
                    <tr align="center">
                      <th scope="col">#</th>
                          <th scope="col">Tipo</th>
                              <th scope="col">Nº Documento</th>
                              <th scope="col">Nº Processo</th>
                          <th scope="col">Cliente</th>
                      <th scope="col">Vencimento</th>
                    </tr>
            </thead>
                    <?php
                        // Prazo esta definido na query de consulta
                        $i = 1;
                            foreach ($objOS->querySelecionaItemVencimento() as $servico){
                                
                                if(empty($servico)){
                                    
                                echo'
                                    <tr class="table-success" align="center">
                                        <th colspan="6">Não há serviços vencendo nos próximos 15 dias.</td>
                                    </tr>';
                                }else{
                                    
                                    echo '       
                                        <tr align="center">
                                            <th scope="row">'.$i++.'</th>
                                                <td>'.$servico->sigla.'</td>
                                                    <td>'.$servico->nDocumento.'</td>
                                                    <td>'.$servico->nProcesso.'</td>
                                                <td>'.$servico->nomeCliente.'</td>
                                            <td>'.$objFuncao->formataData($servico->entrega).'</td>
                                        </tr>';
                                }
                            }
                         
                    ?>
    
        </table>
        
        <hr>
        
        <!--Exibe ordens de serviço emitidas-->
        <table class="table table-sm">
            <tr  class="table-success" align="center">
              <th colspan="9">Ordem de serviço Emitida</td>
            </tr>
                <tr align="center">
                    <th scope="col">#</th>
                        <th scope="col"> Nº OS</th>
                            <th scope="col"> Cliente</th>          
                            <th scope="col"> Emissão</th>
                         <th scope="col"> Itens</th>
                    <th scope="col">Situação da OS</th>
                </tr>
            
            <?php
            
                $i = 1;
                
                    foreach($objOS->querySelecionaOSpagInicial() as $res){
                        
                        //Caso a data retornada seja 0000-00-00 ou vazia, coloca um - na exibição
                        if($res->data == "0000-00-00" OR $res->data == ""){
                            
                            $resData = " - ";
                        
                        }else{
                            $resData = $objFuncao->formataData($res->data);
                        }
                        
                            echo'           
                                <tr align="center">
                                    <td>'.$i++.'</td>
                                        <td>'.$res->idOrdemServico.'</td>
                                            <td>'.$res->nomeCliente.'</td>
                                                <td>'.$resData.'</td>
                                                    <td><a href=visualizaItemOS.php?pg=&acao=ver&idOS='.$res->idOrdemServico.'&solicitante=inicio>Ver</a></td>
                                                <td><a href="atualizaSituacaoOS.php?pg=&acao=situacao&solicitante=inicio&os='.$res->idOrdemServico.'">'.$res->situacao.'</a></td>
                                            <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'&solicitante=inicio" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
                                        <td><a href="ordemServico.php?pg=&acao=alterar&busca='.$res->idOrdemServico.'&solicitante=inicio" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                                    <td><a href="ordemServico.php?pg=&acao=excluir&busca='.$res->idOrdemServico.'&solicitante=inicio" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
                                </tr>
                             ';
                    }      
            ?>
        </table>
        
        <hr>
        
        <!--Exibe compromissos agendados-->
        <table class="table table-sm">
            <tr  class="table-success" align="center">
              <th colspan="9">Agenda de compromissos</td>
            </tr>
                <tr align="center">
                    <th scope="col">#</th>
                        <th scope="col"> Evento</th>
                            <th scope="col"> Valor</th>
                        <th scope="col"> Anotação</th>
                    <th scope="col">Data Limite</th>
                </tr>
            
                <?php
                //Associa agenda ao id do usuario logado
                $agenda = $objUsuario->querySelecionaAgenda($_SESSION['id']);
                
                    $i = 1;
                    
                        foreach($agenda as $nota){
                            
                            echo '        
                                <tr align="center">
                                    <td scope="col">'.$i++.'</td>
                                        <td scope="col">'.$nota->compromisso.'</td>
                                            <td scope="col">R$ '.$objFuncao->SubstituiPonto($nota->valor).'</td>
                                                <td scope="col">'.$nota->anotacao.'</td>
                                            <td scope="col">'.$objFuncao->formataData($nota->vencimento).'</td>
                                        <td scope="col"><a href="?pag=&acao=alteraAgenda&id='.$nota->idAgenda.'" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                                    <td scope="col"><a href="?pag=&acao=excluir&id='.$nota->idAgenda.'" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>       
                                </tr>
                            ';
                        }
                     
                ?>      
        </table>
     
    </div><!--fim div col col-lg-auto-->
  </div><!--fim div rol justify-content-md-center-->
  
  <br/><br/>            
              
<footer class="footer">

</footer>           
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>                  
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