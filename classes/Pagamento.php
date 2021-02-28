<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Pagamento {
    
    private $conecta;
    private $objFuncao;
    private $objServico;
    private $idServico;
    private $servico;
    private $sigla;
    private $descricao;
    private $valor;
    private $status;
    
    public function __construct(){
        $this->conecta = new Conexao();
        $this->objFuncao = new Funcoes();
    }
    
        public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    public function __get($atributo){
        return $this->$atributo;
    }
    
        //seleciona registros validos da tabela orçamento
               public function querySelecionaPagamento($dado){
        try{           
            $SQL = "SELECT 
                    pagamento.idPagamento,
                    pagamento.idOrdemServico,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM pagamento 
                    INNER JOIN status
                    ON pagamento.status = status.cod
                    WHERE pagamento.idOrdemServico = :id
                    ORDER BY pagamento.dataVencimento ASC";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSeleciona->bindParam("id", $ID, PDO::PARAM_INT);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
                   public function querySelecionaPagamentoParcela($OS, $pagamento){
        try{           
            $SQL = "SELECT 
                    pagamento.idPagamento,
                    pagamento.idOrdemServico,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM pagamento 
                    INNER JOIN status
                    ON pagamento.status = status.cod
                    WHERE pagamento.idOrdemServico = :id AND pagamento.idPagamento = :pag
                    ORDER BY pagamento.dataVencimento ASC";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $ID = $OS;
            $PAG = $pagamento;
            $sqlSeleciona->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlSeleciona->bindParam("pag", $PAG, PDO::PARAM_STR);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
       public function querySelecionaFormaPagamento(){
        try{
           
            $SQL = "SELECT * FROM formapagamento WHERE status > 0";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelecionaRelatorioPagamento($id){
        try{           
            $SQL = "SELECT 
                    pagamento.idPagamento,
                    pagamento.idOrdemServico,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM pagamento 
                    INNER JOIN status
                    ON pagamento.status = status.cod
                    WHERE pagamento.idOrdemServico = :id 
                    ORDER BY pagamento.dataVencimento ASC";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $ID = $id;
            $sqlSeleciona->bindParam("id", $ID, PDO::PARAM_INT);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
        public function querySelecionaPagamentoRealizadoPeriodo($id, $inicio, $fim){
        try{           
            $SQL = "SELECT 
                    pagamento.idPagamento,
                    pagamento.idOrdemServico,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM pagamento 
                    INNER JOIN status
                    ON pagamento.status = status.cod
                    WHERE pagamento.idOrdemServico = :id AND pagamento.dataVencimento BETWEEN :d1 AND :d2 And pagamento.status = '5'
                    ";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $ID = $id;
            $INICIO = $inicio;
            $FIM = $fim;
            $sqlSeleciona->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlSeleciona->bindParam("d1", $INICIO, PDO::PARAM_STR);
            $sqlSeleciona->bindParam("d2", $FIM, PDO::PARAM_STR);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelecionaPagamentoPendentePeriodo($id, $inicio, $fim){
        try{           
            $SQL = "SELECT 
                    pagamento.idPagamento,
                    pagamento.idOrdemServico,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM pagamento 
                    INNER JOIN status
                    ON pagamento.status = status.cod
                    WHERE pagamento.idOrdemServico = :id AND pagamento.dataVencimento BETWEEN :d1 AND :d2 And pagamento.status = '1'
                    ";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $ID = $id;
            $INICIO = $inicio;
            $FIM = $fim;
            $sqlSeleciona->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlSeleciona->bindParam("d1", $INICIO, PDO::PARAM_STR);
            $sqlSeleciona->bindParam("d2", $FIM, PDO::PARAM_STR);
            if($sqlSeleciona->execute()){
            return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
     public function queryInsertPagamento($dados, $id, $parcela, $vencimento, $valor){
     try{
     $this->idOS = $id;
     $this->parcela = $parcela;
     $this->valorParcela = $valor;
     $this->vencimento = $vencimento;
     $this->status = $dados['statusPagamento'];
     $SQL = "INSERT pagamento (idOrdemServico, parcela, valor, dataVencimento, status) VALUES (:os, :parcela, :valor, :vencimento, :status)"; 
     $sqlInserir = $this->conecta->conectar()->prepare($SQL);
     $sqlInserir->bindParam(":os", $this->idOS , PDO::PARAM_STR);
     $sqlInserir->bindParam(":parcela", $this->parcela, PDO::PARAM_STR);
     $sqlInserir->bindParam(":valor", $this->valorParcela, PDO::PARAM_STR);
     $sqlInserir->bindParam(":vencimento", $this->vencimento, PDO::PARAM_STR);
     $sqlInserir->bindParam(":status", $this->status, PDO::PARAM_STR);
     if($sqlInserir->execute()){
                return 'ok';
            }else{
                return print_r($sqlInserir->errorInfo());
            }
       } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }     
 } 
 
 
    public function querySelect(){
        try{
 $SQL = "SELECT * FROM servicodisponibilizado WHERE status = :id
            ";
           // $SQL ="SELECT * FROM usuario, permissao WHERE usuario.status = :id";
            $sqlServico = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $sqlServico->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlServico->execute();
            return $sqlServico->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

    
        public function verificaServico($dado){
        try{
            $SQL ="SELECT * FROM servicodisponibilizado WHERE servico = :servico "; 
            $sqlServico = $this->conecta->conectar()->prepare($SQL);
            $SERV = $dado;
            $sqlServico->bindParam('servico', $SERV, PDO::PARAM_STR);
            $sqlServico->execute();
            return $sqlServico->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
 
    public function queryInsert($dados){
//        try{
 //        return   print_r($dados['servico']);
           $this->servico = $dados['servico'];
           $this->sigla = $dados['sigla'];
           $this->descricao = $dados['descricao'];
           $this->valor = $dados['valor'];
            $this->status = $dados['status'];
             // $SQL = "INSERT INTO `servicodisponibilizado` (`nomeServico`, `sigla`, `descricao`, `valor`, `status`) VALUES (:servico, :sigla, :descricao, :valor, :status);";
             $SQL = "INSERT INTO `servicodisponibilizado` (`nomeServico`, `sigla`, `descricao`, `valor`, `status`) VALUES (:servico, :sigla, :descricao, :valor, :status)";
           $sqlServico = $this->conecta->conectar()->prepare($SQL);
  //         $SERVICO = $dados;
            $sqlServico->bindParam(":servico", $this->servico, PDO::PARAM_STR);
            $sqlServico->bindParam(":sigla", $this->sigla, PDO::PARAM_STR);
            $sqlServico->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
            $sqlServico->bindParam(":valor", $this->valor, PDO::PARAM_STR);
            $sqlServico->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlServico->execute()){
               return "ok";
           }else{
                return "erro";
            }
//        } catch (PDOException $ex) {
//           return 'error '.$ex->getMessage();
//        }
    }
    
    public function queryUpdate($dados){
        try{
        $ID = $dados['user'];
        $NOME = $this->objFuncao->tratarCaracter($dados['nome'], 1);
        $EMAIL = $dados['email'];
        $PER = $dados['permissao'];
        $SQL = "UPDATE usuario SET nome = :nome, email = :email, permissao = :per WHERE idUsuario = :id";
        $sqlServico = $this->conecta->conectar()->prepare($SQL);  
        $sqlServico->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlServico->bindParam(":nome", $NOME, PDO::PARAM_STR);
        $sqlServico->bindParam(":email", $EMAIL, PDO::PARAM_STR);
        $sqlServico->bindParam(":per", $PER, PDO::PARAM_STR);
        if($sqlServico->execute()){
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
    
    
         public function queryUpdateSituacaoPagamento($dado, $situacao){
        try{
        $ID = $dado;
        $SIT = $situacao;
        $SQL= "UPDATE pagamento SET status = :situacao WHERE idPagamento = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":situacao", $SIT, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }
        
        
    }
    
    
    public function queryDelete($dado){
        try{
            $ID = $dado;
            $SQL = "DELETE FROM servicodisponibilizado WHERE idServico = :id";
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
