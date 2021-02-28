<?php
require_once 'Conexao.php';
require_once 'Funcoes.php';

class Permissao {
    private $conecta;
    private $objPermissao;
    private $objFuncao;
    private $idPermissao;
    private $permissao;
    
    public function __construct(){
        $this->conecta = new Conexao();
        $this->objFuncao = new Funcoes();
       // $this->objPermissao = new Permissao();
    }
    
    function getConecta() {
        return $this->conecta;
    }

    function getObjPermissao() {
        return $this->objPermissao;
    }

    function getObjFuncao() {
        return $this->objFuncao;
    }

    function getIdPermissao() {
        return $this->idPermissao;
    }

    function getPermissao() {
        return $this->permissao;
    }

    function setConecta($conecta) {
        $this->conecta = $conecta;
    }

    function setObjPermissao($objPermissao) {
        $this->objPermissao = $objPermissao;
    }

    function setObjFuncao($objFuncao) {
        $this->objFuncao = $objFuncao;
    }

    function setIdPermissao($idPermissao) {
        $this->idPermissao = $idPermissao;
    }

    function setPermissao($permissao) {
        $this->permissao = $permissao;
    }
    
            public function verificaPermissao($permissao){
        try{
            $SQL ="SELECT * FROM permissao WHERE permissao = :per "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $PER = $permissao;
            $sqlSelect->bindParam('per', $PER, PDO::PARAM_STR);
            if($sqlSelect->execute()){
                return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

        
    function querySeleciona($dado){
       //echo $dado;
        try{
            $SQL ="SELECT * FROM permissao WHERE idPermissao = :id "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    } 
    
    public function querySelect(){
        try{
            $SQL ="SELECT * FROM permissao WHERE status = :id "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function queryInsert($dados){
        try{
            $this->permissao = $dados['permissao'];
            $this->status = $dados['status'];
            $sqlPermissao = $this->conecta->conectar()->prepare("INSERT INTO `permissao` (`permissao`, `status`) VALUES (:permissao, :status);");
            $sqlPermissao->bindParam(":permissao", $this->permissao, PDO::PARAM_STR);
            $sqlPermissao->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlPermissao->execute()){
                return "ok";
            }else{
                return "erro";
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
     public function queryUpdate($dados){
        try{
        $ID = $dados['idPermissao'];
        $PER = $dados['permissao'];
        $SQL = "UPDATE permissao SET permissao = :per WHERE idPermissao = :id";
        $sqlPermissao = $this->conecta->conectar()->prepare($SQL);  
        $sqlPermissao->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlPermissao->bindParam(":per", $PER, PDO::PARAM_STR);
        if($sqlPermissao->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }    
        //print_r($dados);
        //echo $SQL;
    }
    
    public function queryDelete($dado){
        try{
            $ID = $dado;
            $SQL = "DELETE FROM permissao WHERE idPermissao = :id";
            $sqlPermissao = $this->conecta->conectar()->prepare($SQL);
            $sqlPermissao->bindParam(":id", $ID, PDO::PARAM_INT);
            if($sqlPermissao->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
    
    
}
