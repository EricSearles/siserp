<?php 

session_start();
require_once 'classes/Conexao.php';
require_once 'classes/Validacao.php';
$objValida = new Validacao();
$objFuncao = new Funcoes();
$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_STRING);
        //verifica se o btnLogin foi clickado
        if ($btnLogin){
            $dados=$_POST;
            $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
            $login = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);

            foreach ($objValida->querySelect($dados['usuario']) as $res){

         $consulta =  $objFuncao->base64($res->senha, 2);         
            }

            $validacao = $objValida->getValidar($consulta, $senha);

            if(!$validacao){
//Direciona para login e da mensagem de errro            	
                $_SESSION['msg'] = "Usuário ou senha inválidos";
		header("Location: login.php");
            }else{
//Direciona para admin e inicia sessão            	
                $_SESSION['id'] = $res->idUsuario;
		$_SESSION['nome'] = $res->nomeUsuario;
		$_SESSION['email'] = $res->email;
		$_SESSION['permissao'] = $res->permissao;
                $_SESSION['empresa'] = '1';

                        if($_SESSION['permissao']>'2'){               
                            header("Location: administrativo.php");
                        }else{

                            header("Location: selecionaEmpresa.php");

                        }
            }
        }else{
            $_SESSION['msg'] = "Página não encontrada";
			header("Location: login.php");
        }

?>