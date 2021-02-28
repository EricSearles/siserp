<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Usuario {
    
    private $conecta;
    private $objFuncao;
    private $idUsuario;
    private $user;
    private $nome;
    private $email;
    private $senha;
    private $dataCriacao;
    private $ultimoLog;
    private $permissao;
    private $identificador;
    private $agenda;
    private $compromisso; 
    private $vencimento; 
    private $valor;
    private $anotacao;
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
           
            $SQL = "SELECT * FROM usuario WHERE idUsuario = :idUser AND status = '1'";
            $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
            $idUser = $dado;
            $sqlSeleciona->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            if($sqlSeleciona->execute()){
                return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelecionaUser($dado){
        try{
            $SQL ="SELECT idUsuario, nomeUsuario, email, permissao FROM usuario WHERE idUsuario = :id "; 
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
 $SQL = "SELECT 
            usuario.idUsuario,
            usuario.nomeUsuario,
            usuario.email,
            idPermissao,
            permissao.permissao
            FROM usuario
            INNER JOIN permissao
            ON usuario.permissao = permissao.idPermissao
            WHERE usuario.status = :id AND permissao.idPermissao > 1
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function selecionaContato($dado, $tipo){
        try{
           $this->idUsuario = $dado;
           $this->identificador = $tipo;
           $SQL = "SELECT * FROM contato WHERE id = :id AND identificador = :ident AND status = '1'";
           $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
           $sqlSeleciona->bindParam(':id', $this->idUsuario, PDO::PARAM_STR);
           $sqlSeleciona->bindParam(':ident', $this->identificador, PDO::PARAM_STR);
           if($sqlSeleciona->execute()){
                return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
           
        } catch (PDOException $ex) {

        }
    }
        public function selecionaEndereco($dado, $tipo){
        try{
           $this->idUsuario = $dado;
           $this->identificador = $tipo;
           $SQL = "SELECT * FROM endereco WHERE id = :id AND identificador = :ident AND status = '1'";
           $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
           $sqlSeleciona->bindParam(':id', $this->idUsuario, PDO::PARAM_STR);
           $sqlSeleciona->bindParam(':ident', $this->identificador, PDO::PARAM_STR);
           if($sqlSeleciona->execute()){
                return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
           
        } catch (PDOException $ex) {

        }
    }
    
        public function querySelecionaAgenda($dado){
        try{
           $this->idUsuario = $dado;
           $SQL = "SELECT * FROM agenda WHERE idUsuario = :id AND status = '1'";
           $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
           $sqlSeleciona->bindParam(':id', $this->idUsuario, PDO::PARAM_STR);
           if($sqlSeleciona->execute()){
                return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
           
        } catch (PDOException $ex) {

        }
    }
    
            public function querySelecionaAgendaUp($dado){
        try{
           $this->idUsuario = $dado;
           $SQL = "SELECT * FROM agenda WHERE idAgenda = :id";
           $sqlSeleciona = $this->conecta->conectar()->prepare($SQL);
           $sqlSeleciona->bindParam(':id', $this->idUsuario, PDO::PARAM_STR);
           if($sqlSeleciona->execute()){
                return $sqlSeleciona->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSeleciona->errorInfo());
            }
           
        } catch (PDOException $ex) {

        }
    }
    
        public function querySelecionaFormaPagamento(){
        try{
            $SQL ="SELECT * FROM formapagamento WHERE status = :id "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
        function querySelecionaUpFormaPagamento($dado){
       //echo $dado;
        try{
            $SQL ="SELECT * FROM formapagamento WHERE idFormaPagamento = :id "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

    
        public function verificaNome($nome){
        try{
            $SQL ="SELECT * FROM usuario WHERE nomeUsuario = :nome "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $NOME = $nome;
            $sqlSelect->bindParam('nome', $NOME, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
            public function verificaEmail($email){
        try{
            $SQL ="SELECT * FROM usuario WHERE email = :email "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $EMAIL = $email;
            $sqlSelect->bindParam('email', $EMAIL, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
        public function verificaFormaPagamento($dado){
        try{
            $SQL ="SELECT * FROM formapagamento WHERE formaPagamento = :forma "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $FORMA = $dado;
            $sqlSelect->bindParam('forma', $FORMA, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function queryInsert($dados, $empresa){
        try{
            $this->nome = $this->objFuncao->tratarCaracter($dados['nome'], 1);
            $this->email = $dados['email'];
            $this->senha = $this->objFuncao->base64($dados['senha'], 1);
            $this->dataCriacao = $this->objFuncao->dataAtual(2);
            $this->ultimoLog = $this->objFuncao->dataAtual(1);
            $this->empresa = $empresa;
            $this->permissao = $dados['permissao'];
            $this->status = $dados['status'];
            $sqlUsuario = $this->conecta->conectar()->prepare("INSERT INTO `usuario` (`nomeUsuario`, `email`, `senha`, `dataCriacao`, `ultimoLog`, `empresa`, `permissao`, `status`) VALUES (:nome, :email, :senha, :dC, :uLog, :empresa, :per, :status);");
            $sqlUsuario->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":email", $this->email, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":senha", $this->senha, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":dC", $this->dataCriacao, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":uLog", $this->ultimoLog, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":empresa", $this->empresa, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":per", $this->permissao, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlUsuario->execute()){
                return "ok";
            }else{
                return "erro";
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
               public function queryInsertDocPF($dados){
       try{
            $ID = $dados['0'];
            $IDENTIFICADOR = $dados['1'];       
            $CPF = preg_replace("/[^0-9]/", "",$dados['2']);
            $RG = preg_replace("/[^0-9]/", "",$dados['3']);
            $STATUS = $dados['4'];
            $SQL = "INSERT INTO `documentopf` (`id`, `identificador`, `cpf`, `rg`, `status`) VALUES (?, ?, ?, ?, ?)";
            
           $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
          // print_r($sqlDocumento);
           
           $sqlDocumento->bindParam(1, $ID, PDO::PARAM_STR);
           $sqlDocumento->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
           $sqlDocumento->bindParam(3, $CPF, PDO::PARAM_STR);
           $sqlDocumento->bindParam(4, $RG, PDO::PARAM_STR);
            $sqlDocumento->bindParam(5, $STATUS, PDO::PARAM_STR);
            if($sqlDocumento->execute()){
                return "ok";
            }else{
               return "erro"; //print_r($sqlDocumento->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
     public function queryInsertEnd($dados){
       try{
            $ID = $dados['user'];
            $IDENTIFICADOR = $dados['identificador'];       
            $CEP = preg_replace("/[^0-9]/", "",$dados['cep']);
            $LOGRADOURO = $dados['rua'];
            $NUMERO = $dados['numero'];
            $COMPLEMENTO = $dados['complemento'];
            $BAIRRO = $dados['bairro'];
            $CIDADE = $dados['cidade'];
            $ESTADO = $dados['uf'];
            $PAIS = 'BRA';
            $STATUS = '1';
            $SQL = "INSERT INTO `endereco` (`id`, `identificador`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `pais`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";           
            $sqlEndereco = $this->conecta->conectar()->prepare($SQL);    
            $sqlEndereco->bindParam(1, $ID, PDO::PARAM_STR);
            $sqlEndereco->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
            $sqlEndereco->bindParam(3, $CEP, PDO::PARAM_STR);
            $sqlEndereco->bindParam(4, $LOGRADOURO, PDO::PARAM_STR);
            $sqlEndereco->bindParam(5, $NUMERO, PDO::PARAM_STR);
            $sqlEndereco->bindParam(6, $COMPLEMENTO, PDO::PARAM_STR);
            $sqlEndereco->bindParam(7, $BAIRRO, PDO::PARAM_STR);
            $sqlEndereco->bindParam(8, $CIDADE, PDO::PARAM_STR);
            $sqlEndereco->bindParam(9, $ESTADO, PDO::PARAM_STR);
            $sqlEndereco->bindParam(10, $PAIS, PDO::PARAM_STR);
            $sqlEndereco->bindParam(11, $STATUS, PDO::PARAM_STR);
            if($sqlEndereco->execute()){
                return "ok";
            }else{
                return "erro";//print_r($sqlEndereco->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
                public function queryInsertCTT($dados){
       try{
            $ID = $dados['user'];
            $IDENTIFICADOR = $dados['identificador'];       
            $DDDTEL = $dados['dddTel'];
            $TEL = preg_replace("/[^0-9]/", "",$dados['tel']);
            $DDDCEL = $dados['dddCel'];
            $CEL = preg_replace("/[^0-9]/", "",$dados['cel']);
            $EMAIL = $dados['email'];
            $STATUS = '1';
            $SQL = "INSERT INTO `contato` (`id`, `identificador`, `dddTel`, `tel`, `dddCel`, `cel`, `email`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";           
            $sqlCTT = $this->conecta->conectar()->prepare($SQL);    
            $sqlCTT->bindParam(1, $ID, PDO::PARAM_STR);
            $sqlCTT->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
            $sqlCTT->bindParam(3, $DDDTEL, PDO::PARAM_STR);
            $sqlCTT->bindParam(4, $TEL, PDO::PARAM_STR);
            $sqlCTT->bindParam(5, $DDDCEL, PDO::PARAM_STR);
            $sqlCTT->bindParam(6, $CEL, PDO::PARAM_STR);
            $sqlCTT->bindParam(7, $EMAIL, PDO::PARAM_STR);
            $sqlCTT->bindParam(8, $STATUS, PDO::PARAM_STR);
            if($sqlCTT->execute()){
                return "ok";
            }else{
                return print_r($sqlCTT->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }

                public function queryInsertAgenda($dados){
       try{
            $this->user = $dados['user'];
            $this->compromisso = $dados['compromisso'];
            $this->vencimento = $dados['vencimento'];
            $this->valor = $this->objFuncao->SubstituiVirgula($dados['valor']);
            $this->anotacao = $dados['anota'];
            $this->status = $dados['status'];
            $SQL = "INSERT INTO agenda (idUsuario, compromisso, vencimento, valor, anotacao, status) VALUES (?, ?, ?, ?, ?, ?)";           
            $sqlAgenda = $this->conecta->conectar()->prepare($SQL);    
            $sqlAgenda->bindParam(1, $this->user, PDO::PARAM_STR);
            $sqlAgenda->bindParam(2, $this->compromisso, PDO::PARAM_STR);
            $sqlAgenda->bindParam(3, $this->vencimento, PDO::PARAM_STR);
            $sqlAgenda->bindParam(4, $this->valor, PDO::PARAM_STR);
            $sqlAgenda->bindParam(5, $this->anotacao, PDO::PARAM_STR);
            $sqlAgenda->bindParam(6, $this->status, PDO::PARAM_STR);
            if($sqlAgenda->execute()){
                return "ok";
            }else{
                return print_r($sqlAgenda->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
        public function queryInsereFormaPagamento($dados){
        try{
            $FORMA = $dados['formaPagamento'];
            $STATUS = $dados['status'];
            $sqlFP = $this->conecta->conectar()->prepare("INSERT INTO formapagamento (formaPagamento, status) VALUES (:forma, :status)");
            $sqlFP->bindParam(":forma", $FORMA, PDO::PARAM_STR);
            $sqlFP->bindParam(":status", $STATUS, PDO::PARAM_STR);
            if($sqlFP->execute()){
            return 'ok'; //$sqlFP->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlFP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function queryUpdate($dados){
        try{
        $ID = $dados['user'];
        $NOME = $dados['nome'];
        $EMAIL = $dados['email'];
        $PER = $dados['permissao'];
        $SQL = "UPDATE usuario SET nomeUsuario = :nome, email = :email, permissao = :per WHERE idUsuario = :id";
        $sqlUsuario = $this->conecta->conectar()->prepare($SQL);  
        $sqlUsuario->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUsuario->bindParam(":nome", $NOME, PDO::PARAM_STR);
        $sqlUsuario->bindParam(":email", $EMAIL, PDO::PARAM_STR);
        $sqlUsuario->bindParam(":per", $PER, PDO::PARAM_STR);
        if($sqlUsuario->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }    

    }
    
    public function updateSenha($dados, $pass){
        try{
            $this->idUsuario = $dados['user'];
            $this->senha     = $pass;
            $SQL = "UPDATE usuario SET senha = :senha WHERE idUsuario = :id";
            $sqlUP = $this->conecta->conectar()->prepare($SQL);
            $sqlUP->bindParam(":senha", $this->senha, PDO::PARAM_STR);
            $sqlUP->bindParam(":id", $this->idUsuario, PDO::PARAM_STR);
            if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
            
            
        } catch (PDOException $ex) {

        }
    }
    
    
        public function queryUpdateCTT($user, $dados){
        try{
        $ID = $user;
        $DDDTEL = $dados['dddTel'];
        $TEL = preg_replace("/[^0-9]/", "",$dados['tel']);;
        $DDDCEL = $dados['dddCel'];
        $CEL = preg_replace("/[^0-9]/", "",$dados['cel']);;
        $SQL= "UPDATE contato SET dddTel = :dddtel, tel = :tel, dddCel = :dddcel, cel = :cel WHERE id = :id AND identificador = '5'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":dddtel", $DDDTEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":tel", $TEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":dddcel", $DDDCEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":cel", $CEL, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }
        
        
    }
    
    
            public function queryUpdateEmail($user, $dados){
        try{
        $ID = $user;
        $EMAIL = $dados['email'];
        $SQL= "UPDATE usuario SET email = :email WHERE idUsuario = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":email", $EMAIL, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }
        
        
    }
    
    
    
        public function queryUpdateAgenda($dados){
        try{
            $this->agenda = $dados['idAgenda'];
            $this->compromisso = $dados['compromisso'];
            $this->vencimento = date('Y-m-d', strtotime($dados['vencimento']));
            $this->valor = $this->objFuncao->SubstituiVirgula($dados['valor']);
            $this->anotacao = $dados['anota'];
            $SQL = "UPDATE agenda SET compromisso = :comp, vencimento = :venc, valor = :valor, anotacao = :anota WHERE idAgenda = :id";
            $sqlAgenda = $this->conecta->conectar()->prepare($SQL);  
            $sqlAgenda->bindParam(":id", $this->agenda, PDO::PARAM_STR);
            $sqlAgenda->bindParam(":comp", $this->compromisso, PDO::PARAM_STR);
            $sqlAgenda->bindParam(":venc", $this->vencimento, PDO::PARAM_STR);
            $sqlAgenda->bindParam(":valor", $this->valor, PDO::PARAM_STR);
            $sqlAgenda->bindParam(":anota", $this->anotacao, PDO::PARAM_STR);
        if($sqlAgenda->execute()){
                return 'ok';
            }else{
                return print_r($sqlAgenda->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }    

    }
    
        public function queryUpdateFormaPagamento($dados){
        try{
        $ID = $dados['idFormaPagamento'];
        $FORMA = $dados['formaPagamento'];
        $SQL = "UPDATE formapagamento SET formaPagamento = :forma WHERE idFormaPagamento = :id";
        $sqlUp = $this->conecta->conectar()->prepare($SQL);  
        $sqlUp->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUp->bindParam(":forma", $FORMA, PDO::PARAM_STR);
        if($sqlUp->execute()){
                return 'ok';
            }else{
                return print_r($sqlUp->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }
        }
    
    public function queryDelete($dado){
        try{
            
            $SQL = "UPDATE usuario SET status = 0 WHERE idUsuario = :id ";
            $sqlUsuario = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlUsuario->bindParam(":id", $ID, PDO::PARAM_INT);
            if($sqlUsuario->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function queryDeleteAgenda($dado){
        try{
            
            $SQL = "UPDATE agenda SET status = 0 WHERE idAgenda = :id ";
            $sqlAgenda = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlAgenda->bindParam(":id", $ID, PDO::PARAM_INT);
            if($sqlAgenda->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
    
        public function queryDeleteFormaPagamento($dado){
        try{
            $ID = $dado;
            $SQL = "DELETE FROM formapagamento WHERE idFormaPagamento = :id";
            $sqlDelete = $this->conecta->conectar()->prepare($SQL);
            $sqlDelete->bindParam(":id", $ID, PDO::PARAM_INT);
            if($sqlDelete->execute()){
                return 'ok';
            }else{
                return print_r($sqlDelete->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
}
    

