<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class ServicoDisponibilizado {
    
    private $conecta;
    private $objFuncao;
    private $objServico;
    private $idServico;
    private $servico;
    private $nomeServico;
    private $siglaServico;
    private $descricaoServico;
    private $valorServico;
    private $codigo;
    private $produto;
    private $sigla;
    private $descricao;
    private $valor;
    private $capacidade;
    private $unidadeMedida;
    private $fornecedor;
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
    
       public function querySeleciona($dado){
        try{
           
            $SQL = "SELECT idUsuario, nome, email, dataCriacao, ultimoLog,permissao FROM 'usuario' WHERE idUsuario = :idUser;";
            $sqlServico = $this->conecta->conectar()->prepare($SQL);
            $idUser = 35;
            $sqlServico->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $sqlServico->execute();
            return $sqlServico->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelect(){
        try{
 $SQL = "SELECT * FROM servicodisponibilizado WHERE status > 0
            ";
           // $SQL ="SELECT * FROM usuario, permissao WHERE usuario.status = :id";
            $sqlServico = $this->conecta->conectar()->prepare($SQL);
            //$ID = 1;
            //$sqlServico->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlServico->execute();
            return $sqlServico->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

    
        public function verificaServico($dado){
        try{
            $SQL ="SELECT * FROM servicodisponibilizado WHERE nomeServico = :servico "; 
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
           $this->servico = mb_strtoupper($dados['servico']);
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
    
    public function queryUpdate($dado){
        try{
 $SQL = "SELECT * FROM servicodisponibilizado WHERE idServico = :id";
           // $SQL ="SELECT * FROM usuario, permissao WHERE usuario.status = :id";
            $sqlUpdate = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlUpdate->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlUpdate->execute();
            return $sqlUpdate->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }  
        //print_r($dados);
        //echo $SQL;
        
        
            public function queryUpdateServico($dados, $id){
        try{
           $ID = $id;
           $this->nomeServico = $dados['servico']; 
           $this->siglaServico = $dados['sigla'];
           $this->descricaoServico = $dados['descricao'];
           $this->valorServico = $dados['valor'];


           $SQL = "UPDATE servicodisponibilizado SET nomeServico = :nome, sigla = :sigla, descricao = :desc, valor = :valor WHERE idServico = :id ";
           $sqlProduto = $this->conecta->conectar()->prepare($SQL);
           $sqlProduto->bindParam(":id", $ID, PDO::PARAM_INT);
           $sqlProduto->bindParam(":nome", $this->nomeServico, PDO::PARAM_STR);
           $sqlProduto->bindParam(":sigla", $this->siglaServico, PDO::PARAM_STR);
           $sqlProduto->bindParam(":desc", $this->descricaoServico, PDO::PARAM_STR);
           $sqlProduto->bindParam(":valor", $this->valorServico, PDO::PARAM_STR);

            if($sqlProduto->execute()){
               return "ok";
           }else{
                return print_r($sqlProduto ->errorInfo());
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
    /*********************Classes Produtos****************/
    
    
           public function querySelectProduto(){
        try{
           
            $SQL = "SELECT * FROM produto WHERE status = :id";
            $sqlProduto = $this->conecta->conectar()->prepare($SQL);
            $ID = '1';
            $sqlProduto->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlProduto->execute();
            if($sqlProduto->execute()){
            return $sqlProduto->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlProduto->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
   
    
    public function querySelectPagProdutoSemFornecedor($inicial, $numreg){
        try{
 $SQL = "SELECT 
            produto.idProduto,
            produto.codigo,
            produto.nomeProduto,
            produto.descricao,
            produto.medida,
            produto.unidadeMedida,
            produto.status,
            unidademedida.unidadeMedida,
            unidademedida.sigla
            FROM produto
            INNER JOIN unidademedida
            ON produto.unidadeMedida = unidademedida.idUnidadeMedida
            WHERE produto.status = :id 
            ORDER BY produto.idProduto DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            $sqlSelect->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlSelect->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    
        public function querySelectPagProduto($inicial, $numreg){
        try{
 $SQL = "SELECT 
            produto.idProduto,
            produto.codigo,
            produto.nomeProduto,
            produto.descricao,
            produto.medida,
            produto.unidadeMedida,
            produto.status,
            fornecedor.idFornecedor,
            fornecedor.nomeFornecedor,
            unidademedida.unidadeMedida,
            unidademedida.sigla
            FROM produto
            INNER JOIN fornecedor
            ON produto.idFornecedor = fornecedor.idFornecedor
            INNER JOIN unidademedida
            ON produto.unidadeMedida = unidademedida.idUnidadeMedida
            WHERE produto.status = :id 
            ORDER BY produto.idProduto DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            $sqlSelect->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlSelect->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }


       public function querySelectTotalProduto(){
        try{
           
            $SQL = "SELECT * FROM produto WHERE status = :id";
            $sqlProduto = $this->conecta->conectar()->prepare($SQL);
            $ID = '1';
            $sqlProduto->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlProduto->execute();
            $rowCount = $sqlProduto->rowCount();
            return $rowCount;
            //$sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }

    
           public function querySelecionaFornecedor(){
        try{
           
            $SQL = "SELECT * FROM fornecedor WHERE status = :id";
            $sqlFornecedor = $this->conecta->conectar()->prepare($SQL);
            $ID = '1';
            $sqlFornecedor->bindParam("id", $ID, PDO::PARAM_INT);
            if($sqlFornecedor->execute()){
            return $sqlFornecedor->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlFornecedor->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
               public function querySelecionaUnMedida(){
        try{
           
            $SQL = "SELECT * FROM unidademedida";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            if($sqlSeleciona ->execute()){
            return $sqlSeleciona ->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona ->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
    
    
    
            public function verificaProduto($dados){
        try{
            $SQL ="SELECT * FROM produto WHERE codigo = :cod AND nomeProduto = :produto AND status = 1"; 
            $sqlProduto = $this->conecta->conectar()->prepare($SQL);
            $COD = $dados['codigoProduto'];
            $PROD = $dados['nomeProduto'];
            $sqlProduto->bindParam(':cod', $COD, PDO::PARAM_STR);
            $sqlProduto->bindParam(':produto', $PROD, PDO::PARAM_STR);
            if($sqlProduto->execute()){
            return $sqlProduto->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlProduto ->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    
        public function queryInsertProduto($dados){
        try{
           $this->codigo = $dados['codigoProduto']; 
           $this->produto = $dados['nomeProduto'];
           $this->descricao = $dados['descricao'];
           $this->capacidade = $dados['qtdProduto'];
           $this->unidadeMedida = $dados['unidadeMedida'];
           $this->fornecedor = $dados['fornecedor'];
           $this->status = $dados['status'];
           $SQL = "INSERT INTO `produto` (`codigo`,`nomeProduto`, `descricao`, `medida`, `unidadeMedida`, `idFornecedor`, `status`) VALUES (:cod, :produto, :desc, :medida, :uM, :fornecedor, :status)";
           $sqlProduto = $this->conecta->conectar()->prepare($SQL);
           $sqlProduto->bindParam(":cod", $this->codigo, PDO::PARAM_STR);
           $sqlProduto->bindParam(":produto", $this->produto, PDO::PARAM_STR);
           $sqlProduto->bindParam(":desc", $this->descricao, PDO::PARAM_STR);
           $sqlProduto->bindParam(":medida", $this->capacidade, PDO::PARAM_STR);
           $sqlProduto->bindParam(":uM", $this->unidadeMedida, PDO::PARAM_STR);
           $sqlProduto->bindParam(":fornecedor", $this->fornecedor, PDO::PARAM_STR);
           $sqlProduto->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlProduto->execute()){
               return "ok";
           }else{
                return print_r($sqlProduto ->errorInfo());
            }
       } catch (PDOException $ex) {
           return 'error '.$ex->getMessage();
        }
    }
    
    
     public function queryExibeUpdateProduto($dado){
        try{
 $SQL = "SELECT 
            produto.idProduto,
            produto.codigo,
            produto.nomeProduto,
            produto.descricao,
            produto.medida,
            produto.unidadeMedida,
            produto.status,
            fornecedor.idFornecedor,
            fornecedor.nomeFornecedor,
            unidademedida.idUnidadeMedida,
            unidademedida.unidadeMedida,
            unidademedida.sigla
            FROM produto
            INNER JOIN fornecedor
            ON produto.idFornecedor = fornecedor.idFornecedor
            INNER JOIN unidademedida
            ON produto.unidadeMedida = unidademedida.idUnidadeMedida
            WHERE produto.idProduto = :id AND produto.status = '1'           
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function queryUpdateProduto($dados, $id){
        try{
           $ID = $id;
           $this->codigo = $dados['codigoProduto']; 
           $this->produto = $dados['nomeProduto'];
           $this->descricao = $dados['descricao'];
           $this->capacidade = $dados['qtdProduto'];
           $this->unidadeMedida = $dados['unidadeMedida'];
           $this->fornecedor = $dados['fornecedor'];

           $SQL = "UPDATE produto SET codigo = :cod, nomeProduto = :produto, descricao = :desc, medida = :medida, unidadeMedida = :uM, idFornecedor = :fornecedor WHERE idProduto = :id ";
           $sqlProduto = $this->conecta->conectar()->prepare($SQL);
           $sqlProduto->bindParam(":id", $ID, PDO::PARAM_INT);
           $sqlProduto->bindParam(":cod", $this->codigo, PDO::PARAM_STR);
           $sqlProduto->bindParam(":produto", $this->produto, PDO::PARAM_STR);
           $sqlProduto->bindParam(":desc", $this->descricao, PDO::PARAM_STR);
           $sqlProduto->bindParam(":medida", $this->capacidade, PDO::PARAM_STR);
           $sqlProduto->bindParam(":uM", $this->unidadeMedida, PDO::PARAM_STR);
           $sqlProduto->bindParam(":fornecedor", $this->fornecedor, PDO::PARAM_STR);

            if($sqlProduto->execute()){
               return "ok";
           }else{
                return print_r($sqlProduto ->errorInfo());
            }
       } catch (PDOException $ex) {
           return 'error '.$ex->getMessage();
        }
    }
    

    
        public function queryDeleteProduto($dado){
        try{
            $ID = $dado;
            $SQL = "UPDATE produto SET status = '0' WHERE idProduto = :id";
            $sqlDel = $this->conecta->conectar()->prepare($SQL);
            $sqlDel->bindParam(":id", $ID, PDO::PARAM_INT);
            if($sqlDel->execute()){
                return 'ok';
            }else{
                return print_r($sqlProduto ->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
}
