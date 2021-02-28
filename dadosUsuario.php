<?php 
session_start();
unset ($_SESSION['msg']);
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/Usuario.php';
        require_once 'classes/Permissao.php';
        
        include 'cep.php';
        
        $objUsuario = new Usuario();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
        
//Incluir contato        
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='Salvar'){
    unset($_SESSION['msg']);
        $dados = $_POST;
        if($objUsuario->queryInsertCTT($dados)== 'ok'){
            $_SESSION['msg'] = "Contato inserido com sucesso";
        }else{
            $_SESSION['msg'] = "Não foi possível cadastrar seu contato, tente novamente ou entre em contato com o desenvolvedor";
        }
  //completar cadastro do usuario - endereço e contatos
}  


//Incluir endereço
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='incluir'){
    unset($_SESSION['msg']);
        $dados = $_POST;
        //$teste = $objUsuario->queryInsertEnd($dados);
        print_r($_POST);
        if($objUsuario->queryInsertEnd($dados)== 'ok'){
            $_SESSION['msg'] = "Endereço inserido com sucesso";
        }else{
            $_SESSION['msg'] = "Não foi possível cadastrar seu endereço, tente novamente ou entre em contato com o desenvolvedor";
        }
  //completar cadastro do usuario - endereço e contatos
}  


//Alterar senha
    if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar']=='AlteraSenha'){
 unset($_SESSION['msg']);
        $pass = $objFuncao->base64($dados['senha'], 1);
     // alterar senha do usuario
        if($objUsuario->updateSenha($dados, $pass)== 'ok'){
            ?>
            <script>
            window.location.href = "dadosUsuario.php";
            </script>
            <!--header("Location: dadosUsuario.php");-->
            <?php
           $_SESSION['msg'] = "Senha alterada com sucesso.";
        }else{
            $_SESSION['msg'] = "Não foi possível alterar sua senha, tente novamente ou entre em contato com o desenvolvedor";
        }
        
    }  
    
//alterar contato    
if(isset($_POST['btnAlterarContato']) AND $_POST['btnAlterarContato'] == "Alterar Contato"){
    
    $dados = $_POST;
    $id = $_POST['user'];

    if($objUsuario->queryUpdateCTT($id, $dados)== 'ok'){
        
        $_SESSION['msg'] = "Dados alterados com sucesso.";
        
    }else{
        
        $_SESSION['msg'] = "Não foi possível alterar seus dados, tente novamente ou entre em contato com o desenvolvedor";
        
    }
    
}

//alterar email   
if(isset($_POST['btnAlterarEmail']) AND $_POST['btnAlterarEmail'] == "Alterar"){
    print_r($dados);
    $dados = $_POST;
    $id = $_POST['user'];

    if($objUsuario->queryUpdateEmail($id, $dados)== 'ok'){
        
        $_SESSION['msg'] = "Dados alterados com sucesso.";
        
    }else{
        
        $_SESSION['msg'] = "Não foi possível alterar seus dados, tente novamente ou entre em contato com o desenvolvedor";
        
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
    
	<title>Dados do Usuário - MVArquitetura</title>
</head>
<body>
<?php 
if($_SESSION['permissao'] <= 1){
include 'menu.php';
}else{
include 'menu1.php'; 
}
?>
<!--********************FIM DO MENU*******************************-->
    <main role="main" class="container">
      <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
    <?php   
    //Saudaçõ da página
    echo 'Olá ' .$_SESSION['nome'].', seja bem vindo!<br/>';
    ?>
      </div>            
         <div class="my-3 p-3 bg-white rounded shadow-sm">
             <h6 class="border-bottom border-gray pb-2 mb-0">Dados do Usuário</h6>
<!--****************************Corte**********************************  -->
<div class="container">
  <div class="row justify-content-md-center">

    <div class="col-md-auto">     
        <?php
foreach($objUsuario->querySeleciona($_SESSION['id']) as $res){

}
 if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];

            }
echo'
<br/>
    <table>
    <tr><th colspan="3">Meus Dados</th></tr>
    <tr>
      <td colspan="3"><hr/></td>
    </tr>
    <tr>
      <td>Nome:</td><td> '.$res->nomeUsuario.'</td>
    </tr>
    <tr>
      <td>E-mail:</td><td> '.$res->email.'</td>
    </tr>
        <tr>
      <td colspan="3"><hr/></td>
    </tr>
    <tr>
      <td colspan="3"><a href=?acao=pass>Alterar senha</a></td>
    </tr>

    ';
      if(isset($_GET['acao'])&& $_GET['acao'] == 'pass'){
          echo'
          <form name="ateraSenha" method="POST" action="" >
    <tr>      
          <td colspan="3"><label>Nova senha:</label></td>
    </tr>
    <tr>
        <td colspan="3"><input type="password" name="senha" maxlength="8" size="10" placeholder="Máximo 8 caracteres" class="form-control"/></td>
    </tr>
    <tr>
          <td colspan="3"><label>Confirma senha:</label></td>
    </tr>
        <td colspan="3"><input type="password" name="confirmaSenha" maxlength="8" size="10" placeholder="Máximo 8 caracteres" class="form-control"/></td>
    </tr>
    <tr>
          <td colspan="2">
          <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
            <br/><input type="submit" name="btnCadastrar" value="AlteraSenha" class="btn  btn-primary btn-block"/>
            </td>
    </tr>
    

          </form>
    
   
          ';
          
      }
      echo'
      <tr>
      <td colspan="3"><hr/></td>
    </tr>

    <tr>
      <td colspan="3"><a href=?acao=email>Alterar e-mail</a></td>
    </tr>
    ';
      if(isset($_GET['acao'])&& $_GET['acao'] == 'email'){
          echo'
          <form name="ateraEmail" method="POST" action="" >
    <tr>      
          <td colspan="3"><label>E-mail:</label></td>
    </tr>
    <tr>
        <td colspan="3"><input type="text" name="email"  value="'.$res->email.'" class="form-control"/></td>
    </tr>
    
    <tr>
          <td colspan="2">
          <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
            <br/><input type="submit" name="btnAlterarEmail" value="Alterar" class="btn  btn-primary btn-block"/>
            </td>
    </tr>
    

          </form>
    
   
          ';
          
      }

      $ctt = $objUsuario->selecionaContato($_SESSION['id'], '5');
      if(empty($ctt)){         
          echo '
          
              <table>
                  <tr>
      <td colspan="3"><hr/></td>
    </tr>
              <tr>
                <td colspan="2"><a href="?acao=contato">Adicionar contato</a></td>
               </tr>';
                
      }else{
          foreach ($ctt as $contato) {
              $tel = $objFuncao->mask($contato->tel, '####-####');
              $cel = $objFuncao->mask($contato->cel, '#####-####');
           echo'
               <tr>
      <td colspan="3"><hr/></td>
    </tr>
               <tr>
                <th colspan="3">Meus Contatos</th>
                </tr>
                    <tr>
      <td colspan="3"><hr/></td>
    </tr>';
            
            if($contato->dddTel == '0'){
                
                
                echo '
                <tr>
                <td><a href=?acao=alterarContato&user='.$_SESSION['id'].'>Cadastrar Telefone</a></td>
                </tr>';
                
            }else{
                
        
             echo '
                <tr>
                <td>Telefone:</td><td>( '.$contato->dddTel.' ) '.$tel.'</td>
                </tr>';
                
            }
           
            
            if($contato->dddCel == '0'){
                
                
                echo '
                <tr>
                <td><a href=?acao=alterarContato&user='.$_SESSION['id'].'>Cadastrar Celular</a></td>
                </tr>';
                
            }else{
                
        
                echo '
                <tr>
                <td>Celular:</td><td>( '.$contato->dddCel.' ) '.$cel.'</td>
                </tr>';
            }
            echo '
                    <tr>
      <td colspan="3"><hr/></td>
    </tr>
                <tr>
                <td><a href=?acao=alterarContato&user='.$_SESSION['id'].'>Alterar Contato</a></td>
                </tr>                
                ';   
          }

      }
      
            if(isset($_GET['acao'])&& $_GET['acao'] == 'alterarContato'){
          echo'
          <form name="contato" method="POST" action="" >
            <tr>      
                <td><label>Telefone:</label></td>
            </tr>
            <tr>
                <td><input type="text" name="dddTel" maxlength="2" size="1" placeholder="DDD" class="form-control" value="'.$contato->dddTel.'"/> </td>
                <td><input type="text" name="tel" maxlength="8" size="10" placeholder="Só numeros" class="form-control" value="'.$contato->tel.'"/></td>
            </tr>
            <tr>      
                <td><label>Celular:</label></td>
            </tr>
            <tr>
                <td><input type="text" name="dddCel" maxlength="2" size="1" placeholder="DDD" class="form-control" value="'.$contato->dddCel.'"/></td>
                <td><input type="text" name="cel" maxlength="9" size="10" placeholder="Só numeros" class="form-control" value="'.$contato->cel.'"/></td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
                    <input type="hidden" name="identificador" value="5" />
                    <br/><input type="submit" name="btnAlterarContato" value="Alterar Contato" class="btn  btn-primary btn-block"/>
                </td>
            </tr>

            </table>
          </form>
          ';
      }
      
      
      if(isset($_GET['acao'])&& $_GET['acao'] == 'contato'){
          echo'
          <form name="contato" method="POST" action="" >
            <tr>      
                <td><label>Telefone:</label><input type="text" name="dddTel" maxlength="2" size="1" placeholder="DDD" class="form-control"/> 
                <input type="text" name="tel" maxlength="8" size="10" placeholder="Só numeros" class="form-control"/></td>
            </tr>
            <tr>      
                <td><label>Celular:</label><input type="text" name="dddCel" maxlength="2" size="1" placeholder="DDD" class="form-control"/>
                <input type="text" name="cel" maxlength="9" size="10" placeholder="Só numeros" class="form-control"/></td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
                        <input type="hidden" name="email" value="'.$_SESSION['email'].'" />
                    <input type="hidden" name="identificador" value="5" />
                    <br/><input type="submit" name="btnCadastrar" value="Salvar" />
                </td>
            </tr>
            <tr>
                <td><a href="?">Voltar</a></td>
            </tr>
            </table>
          </form>
          ';
      }
        $end = $objUsuario->selecionaEndereco($_SESSION['id'], '5');
            if(empty($end)){

          echo '
<table>     
    <tr>
      <td colspan="3"><hr/></td>
    </tr>
<tr>
                <td colspan="2"><a href="?acao=end">Adicionar endereço</a></td>
                </tr>';
      }else{
          foreach ($end as $endereco) {

              $cep = $objFuncao->mask($endereco->cep, '#####-###');
             echo '      
                 <tr>
      <td colspan="3"><hr/></td>
    </tr>
                <tr>
                <th>Endereço</th>
                </tr>
                <tr>
                <td>Cep:</td><td>'.$cep.'</td>
                </tr>
                <tr>
                <td>Rua:</td><td>'.$endereco->logradouro.', '.$endereco->numero.'</td>
                </tr>
                <tr>
                <td>Bairro:</td><td>'.$endereco->bairro.'</td>
                </tr>
                <tr>
                <td>Cidade:</td><td>'.$endereco->cidade.'</td>
                </tr>
                <tr>
                <td>Estado:</td><td>'.$endereco->estado.'</td>
                </tr>
                <tr>
                <td><a href=#>Alterar Endereço</a></td>
                </tr>';
          }  

      }
      
           if(isset($_GET['acao'])&& $_GET['acao'] == 'end'){
          echo'
          <form name="endereco" method="POST" action="" >
 <tr>
                        <td><labeL>CEP:</labeL>
                        <input type="text" name="cep" id="cep" size="10" maxlength="9"
               onblur=pesquisacep(this.value) class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>Logradouro:</label>
                        <input type="text" name="rua" id="rua" size="40" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>Numero:</label>
                        <input type="text" name="numero" id="numero" size="4" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>Complemento:</label>
                        <input type="text" name="complemento" id="complemento" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>Bairro:</label>
                        <input type="text" name="bairro" id="bairro" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>Cidade:</label>
                        <input type="text" name="cidade" id="cidade" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label>UF:</label>
                        <input type="text" name="uf" id="uf" size="1" class="form-control"/></td>
                    </tr>      
            <tr>
                <td>
                    <input type="hidden" name="user" value="'.$_SESSION['id'].'" />
                        <input type="hidden" name="email" value="'.$_SESSION['email'].'" />
                    <input type="hidden" name="identificador" value="5" />
                    <br/><input type="submit" name="btnCadastrar" value="incluir" class="btn btn-lg btn-primary btn-block"/>
                </td>
            </tr>

            </table>
          </form>
          ';
      }
                       
   echo'         
       
</table>';
?>
            
        <a href="?">Voltar</a>
    
    </div><!--Fim Espaço central--> 

  </div>

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
