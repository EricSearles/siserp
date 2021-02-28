<?php

class Conexao{
    private $usuario;
    private $senha;
    private $banco;
    private $servidor;
    private $char;
    private static $pdo;
    
    public function __construct(){
        $this->servidor = "192.185.176.239";
        $this->banco = "tamagnin_dev";
        $this->usuario = "tamagnin_searl90";
        $this->senha = "searles102030";
    }
    
    public function conectar(){
        try{
            if(is_null(self::$pdo)){
                self::$pdo = new PDO("mysql:host=$this->servidor;dbname=$this->banco;charset=utf8", $this->usuario, $this->senha,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            }
            return self::$pdo;
        } catch (PDOException $ex) {
			echo $ex->getMessage();
        }
    }
    
}

?>
