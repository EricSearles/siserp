<?php

/**
 * Description of Busca
 *
 * @author Searles
 */

require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Busca {
    
    
    private $conecta;
    private $objBusca;
    
    public function __construct(){
        $this->conecta = new Conexao();
        $this->objBusca = new Funcoes();
    }
    
    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    public function __get($atributo){
        return $this->$atributo;
    }
    
    function buscaCliente($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM cliente WHERE nomeCliente like :nome AND status > 0 ORDER BY nomeCliente LIMIT 0,6";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $sqlCliente->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlCliente->execute()){
            return $sqlCliente->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlCliente->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
        function buscaUsuario($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM usuario WHERE nomeUsuario like :nome AND status > 0 ORDER BY nomeUsuario LIMIT 0,6";
            $sqlBusca = $this->conecta->conectar()->prepare($SQL);
            $sqlBusca->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlBusca->execute()){
            return $sqlBusca->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlBusca->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
            function buscaColaborador($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM colaborador WHERE nomeColaborador like :nome AND status > 0 ORDER BY nomeColaborador LIMIT 0,6";
            $sqlBusca = $this->conecta->conectar()->prepare($SQL);
            $sqlBusca->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlBusca->execute()){
            return $sqlBusca->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlBusca->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
                function buscaFornecedor($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM fornecedor WHERE nomeFornecedor like :nome AND status > 0 ORDER BY nomeFornecedor LIMIT 0,6";
            $sqlBusca = $this->conecta->conectar()->prepare($SQL);
            $sqlBusca->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlBusca->execute()){
            return $sqlBusca->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlBusca->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
    
    
                function buscaDocumento($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM itemordemservico WHERE nDocumento like :nome AND status > 0 ORDER BY nDocumento LIMIT 0,6";
            $sqlBusca = $this->conecta->conectar()->prepare($SQL);
            $sqlBusca->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlBusca->execute()){
            return $sqlBusca->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlBusca->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
    
                function buscaProcesso($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{
            $SQL = "SELECT * FROM itemordemservico WHERE nProcesso like :nome AND status > 0 ORDER BY nProcesso LIMIT 0,6";
            $sqlBusca = $this->conecta->conectar()->prepare($SQL);
            $sqlBusca->bindParam(":nome", $dado, PDO::PARAM_STR);
           if($sqlBusca->execute()){
            return $sqlBusca->fetchALL(PDO::FETCH_OBJ);
            //print_r($sqlCliente);
            }else{
                return print_r($sqlBusca->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
            

        }
        
    }
    
        //seleciona registros validos da tabela orçamento
    public function buscaOrcamento($nome){
        $dado = $nome.'%';
 //       return $nome;
        try{           
            $SQL = "SELECT * FROM orcamento WHERE numero like :nome AND status > 0 ORDER BY numero LIMIT 0,6";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $sqlOrcamento->bindParam(":nome", $dado, PDO::PARAM_STR);
            if($sqlOrcamento->execute()){
            return $sqlOrcamento->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlOrcamento->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
    
    
    
    function runQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
    
}
