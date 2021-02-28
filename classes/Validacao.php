<?php
require_once "Conexao.php";
require_once "Funcoes.php";

class Validacao {
    private $objValida;
    private $conecta;
    private $nome;
    private $email;
    private $senha;
    Private $consulta;
    Private $password;
    
    public function __construct() {
        $this->conecta = new Conexao();
        $this->objFuncao = new Funcoes();
        
    }

    public function querySelect($email){
        try{
            $sqlSelect = $this->conecta->conectar()->prepare("SELECT idUsuario, nomeUsuario, email, senha, permissao FROM `usuario`WHERE email = :email AND status = 1");
            $sqlSelect->bindParam('email', $email, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
      
    public function getValidar($consulta, $senha){      
                    if($senha === $consulta){
                        return true;
                    }
 
            }
    }
    
    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }


    


