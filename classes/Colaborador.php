<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Colaborador {
    
    private $conecta;
    private $objFuncao;
    private $idColaborador;
    private $nome;
    private $idFornecedor;
    private $nomeFornecedor;
    private $dataCriacao;
    private $status;
    private $doc;

    
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
           
            $SQL = "SELECT idUsuario, nome, email, dataCriacao, ultimoLog,permissao FROM 'usuario' WHERE idCliente = :id";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $id = $idCliente;
            $sqlCliente->bindParam(":id", $id, PDO::PARAM_INT);
            $sqlCliente->execute();
            return $sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelecionaColaboradorPJ($dado, $tipo){
        try{
            $SQL = "SELECT 
            colaborador.idColaborador,
            colaborador.tipoColaborador,
            colaborador.nomeColaborador,
            colaborador.razaoSocial,
            documentopj.cnpj,
            documentopj.iMunicipal,
            documentopj.iEstadual,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel,
            endereco.cep,
            endereco.numero,
            endereco.logradouro,
            endereco.complemento,
            endereco.bairro,
            endereco.cidade,
            endereco.estado,
            endereco.pais
            FROM colaborador
            INNER JOIN documentopj ON colaborador.idColaborador = documentopj.id
            INNER JOIN contato ON colaborador.idColaborador = contato.id
            INNER JOIN endereco ON colaborador.idColaborador = endereco.id
            WHERE colaborador.idColaborador = :id AND colaborador.tipoColaborador = :tipo  AND contato.Identificador = '2' AND endereco.Identificador = '2'     
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOCOLABORADOR = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOCOLABORADOR, PDO::PARAM_STR);
           if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
            }catch (PDOException $ex) {
                return 'erro '.$ex->getMessage();
            }
        }
        
        public function querySelecionaColaboradorPF($dado, $tipo){
        try{
           // $SQL ="SELECT idCliente, , tipoCliente, nome, razaoSocial, dataCriacao FROM usuario WHERE idCliente = :id "; 
            $SQL = "SELECT 
            colaborador.idColaborador,
            colaborador.tipoColaborador,
            colaborador.nomeColaborador,
            colaborador.razaoSocial,
            documentopf.cpf,
            documentopf.rg,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel,
            endereco.cep,
            endereco.numero,
            endereco.logradouro,
            endereco.complemento,
            endereco.bairro,
            endereco.cidade,
            endereco.estado,
            endereco.pais
            FROM colaborador
            INNER JOIN documentopf ON colaborador.idColaborador = documentopf.id
            INNER JOIN contato ON colaborador.idColaborador = contato.id
            INNER JOIN endereco ON colaborador.idColaborador = endereco.id
            WHERE colaborador.idColaborador = :id AND colaborador.tipoColaborador = :tipo AND contato.Identificador = '2' AND endereco.Identificador = '2'
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOCOLABORADOR = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOCOLABORADOR, PDO::PARAM_STR);
           if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
            }catch (PDOException $ex) {
                return 'erro '.$ex->getMessage();
            }
        }
    
    
    public function querySelectPag($inicial, $numreg){
        try{
          //  $SQL ="SELECT * FROM cliente WHERE status = :id LIMIT :inicial, :numreg";
 $SQL = "SELECT 
            colaborador.idColaborador,
            colaborador.tipoColaborador,
            colaborador.nomeColaborador,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel
            FROM colaborador
            INNER JOIN contato
            ON colaborador.idColaborador = contato.id
            WHERE colaborador.status = :id AND contato.identificador = '2'
            ORDER BY colaborador.idColaborador DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            $sqlSelect->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlSelect->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

       public function querySelectTotal(){
        try{
           
            $SQL = "SELECT * FROM colaborador WHERE status = :id";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $ID = '1';
            $sqlCliente->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlCliente->execute();
            $rowCount = $sqlCliente->rowCount();
            return $rowCount;
            //$sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
        //seleciona registros validos da tabela orçamento
    public function querySelecionaOrcamento($dado){
        try{   
            $ID = $dado;
            //$SQL = "SELECT * FROM orcamento WHERE colaborador = :id AND status > 0";
             $SQL = "SELECT 
                    orcamento.idOrcamento,
                    orcamento.numero,
                    orcamento.data,
                    orcamento.cliente,
                    orcamento.responsavel,
                    orcamento.colaborador,
                    orcamento.total,
                    orcamento.validade,
                    orcamento.status,
                    cliente.nomeCliente,
                    colaborador.nomeColaborador,
                    usuario.nomeUsuario,
                    status.situacao
                    FROM orcamento 
                    INNER JOIN cliente
                    ON orcamento.cliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON orcamento.colaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON orcamento.responsavel = usuario.idUsuario
                    INNER JOIN status
                    ON orcamento.status = status.cod
                    WHERE orcamento.colaborador = :id AND orcamento.status > 0 
                    ORDER BY orcamento.idOrcamento DESC LIMIT 0,5";
             
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $sqlOrcamento->bindParam("id", $ID, PDO::PARAM_INT);
            if($sqlOrcamento->execute()){
            return $sqlOrcamento->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlOrcamento->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
     public function querySelecionaServico($dado){
        try{
          //  $SQL ="SELECT * FROM cliente WHERE status = :id LIMIT :inicial, :numreg";
             $ID = $dado;

 $SQL = "SELECT 
            ordemservico.idOrdemServico,
            ordemservico.data,
            ordemservico.idCliente,
            ordemservico.idResponsavel,
            ordemservico.idColaborador,
            ordemservico.validade,
            ordemservico.status,
            cliente.nomeCliente,
            usuario.nomeUsuario,
            status.situacao
            FROM ordemservico
            INNER JOIN cliente
            ON ordemservico.idCliente = cliente.idCliente
            INNER JOIN usuario
            ON ordemservico.idResponsavel = usuario.idUsuario
            INNER JOIN status
            ON ordemservico.status = status.cod
            WHERE ordemservico.idColaborador = :id AND ordemservico.status > 0 
            ORDER BY ordemservico.idOrdemServico DESC LIMIT 0,5";

            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_INT);
             if($sqlSelect->execute()){
                return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    
        public function verificaNome($nome){
        try{
            $SQL ="SELECT * FROM colaborador WHERE nomeColaborador = :nome"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $NOME = $nome;
            $sqlSelect->bindParam('nome', $NOME, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
            public function verificaDocumentoPF($dado){
        try{
            $SQL ="SELECT * FROM documentopf WHERE cpf = :dado AND identificador = '2'"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $DADO = $dado;
            $sqlSelect->bindParam('dado', $DADO, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
                public function verificaRSocial($dado, $tipo){
        try{
            $SQL ="SELECT * FROM colaborador WHERE razaoSocial = :dado AND tipoColaborador = :tipo AND status > 0 "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $DADO = $dado;
            $TIPO = $tipo;
            $sqlSelect->bindParam('dado', $DADO, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPO, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function queryInsert($dados){
        try{
            $this->tipo = $dados['TipoPessoa'];
            $this->nome = $dados['nome'];       
            $this->razaoSocial = $dados['razaoSocial'];
            $this->dataCriacao = $this->objFuncao->dataAtual(2);
            $this->status = $dados['status'];
            $sqlInsert = $this->conecta->conectar()->prepare("INSERT INTO colaborador (tipoColaborador, nomeColaborador, razaoSocial, dataCriacao, status) VALUES (:tipo, :nome, :rSocial, :dC, :status)");
            $sqlInsert->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $sqlInsert->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $sqlInsert->bindParam(":rSocial", $this->razaoSocial, PDO::PARAM_STR);
            $sqlInsert->bindParam(":dC", $this->dataCriacao, PDO::PARAM_STR);
            $sqlInsert->bindParam(":status", $this->status, PDO::PARAM_STR);
           if($sqlInsert->execute()){             
            $lastId = $this->conecta->conectar()->lastInsertId();
                return $lastId;
                }else{
                    print_r($sqlInsert->errorInfo());
                }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    
           public function queryInsertDocPF($dados){
       try{
            $ID = $dados['id'];
            $IDENTIFICADOR = $dados['identificador'];       
            $CPF = preg_replace("/[^0-9]/", "",$dados['CPF']);
            $RG = preg_replace("/[^0-9]/", "",$dados['RG']);
            $STATUS = $dados['status'];
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
    
    
    
            public function queryInsertDocPJ($dados){
       try{
            $ID = $dados['id'];
            $IDENTIFICADOR = $dados['identificador'];       
            $CNPJ = preg_replace("/[^0-9]/", "",$dados['CNPJ']);
            $IMUNICIPAL = preg_replace("/[^0-9]/", "",$dados['IMUN']);
            $IESTADUAL = preg_replace("/[^0-9]/", "",$dados['IEST']);
            $STATUS = $dados['status'];
            $SQL = "INSERT INTO `documentopj` (`id`, `identificador`, `cnpj`, `iMunicipal`, `iEstadual`, `status`) VALUES (?, ?, ?, ?, ?, ?)";           
            $sqlDocumento = $this->conecta->conectar()->prepare($SQL);          
            $sqlDocumento->bindParam(1, $ID, PDO::PARAM_STR);
            $sqlDocumento->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
            $sqlDocumento->bindParam(3, $CNPJ, PDO::PARAM_STR);
            $sqlDocumento->bindParam(4, $IMUNICIPAL, PDO::PARAM_STR);
            $sqlDocumento->bindParam(5, $IESTADUAL, PDO::PARAM_STR);
            $sqlDocumento->bindParam(6, $STATUS, PDO::PARAM_STR);
            if($sqlDocumento->execute()){
                return "ok";
            }else{
                print_r($sqlDocumento->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
     
            public function queryInsertEnd($dados){
       try{
            $ID = $dados['endId'];
            $IDENTIFICADOR = $dados['endIdent'];       
            $CEP = preg_replace("/[^0-9]/", "",$dados['endCep']);
            $LOGRADOURO = $dados['endRua'];
            $NUMERO = $dados['endNum'];
            $COMPLEMENTO = $dados['endComp'];
            $BAIRRO = $dados['endBairro'];
            $CIDADE = $dados['endCidade'];
            $ESTADO = $dados['endEst'];
            $PAIS = $dados['endPais'];
            $STATUS = $dados['endStatus'];
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
            $ID = $dados['cttId'];
            $IDENTIFICADOR = $dados['cttIdent'];       
            $DDDTEL = $dados['cttDDDtel'];
            $TEL = preg_replace("/[^0-9]/", "",$dados['cttTel']);
            $DDDCEL = $dados['cttDDDcel'];
            $CEL = preg_replace("/[^0-9]/", "",$dados['cttCel']);
            $EMAIL = $dados['cttEmail'];
            $STATUS = $dados['cttStatus'];
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
    
    
    public function queryUpdateColaboradorPF($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados;
        $SQL= "UPDATE colaborador SET nomeColaborador = :nome WHERE idColaborador = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":nome", $NOME, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
        
    }
    
        public function queryUpdateColaboradorPJ($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados['nome'];
        $RSOCIAL = $dados['razaoSocial'];
        $SQL= "UPDATE colaborador SET nomeColaborador = :nome, razaoSocial = :rSocial WHERE idColaborador = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":nome", $NOME, PDO::PARAM_STR);
        $sqlUP->bindParam(":rSocial", $RSOCIAL, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
        
    }
    
        public function queryUpdateDocPF($user, $cpf){
         try{
        $ID = $user;
        $CPF = $cpf;
        $SQL= "UPDATE documentopf SET cpf = :cpf WHERE id = :id AND identificador = '2'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":cpf", $CPF, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
    }
    
            public function queryUpdateDocPJ($user, $dados){
            try{
            $ID = $user;
            $CNPJ = preg_replace("/[^0-9]/", "",$dados['Cnpj']);
            $IMUN = $dados['insc_municipal'];
            $IEST = $dados['insc_estadual'];
            $SQL= "UPDATE documentopj SET cnpj = :cnpj, iMunicipal = :imun, iEstadual = :iest WHERE id = :id AND identificador = '2'";
            $sqlUP = $this->conecta->conectar()->prepare($SQL);  
            $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
            $sqlUP->bindParam(":cnpj", $CNPJ, PDO::PARAM_STR);
            $sqlUP->bindParam(":imun", $IMUN, PDO::PARAM_STR);
            $sqlUP->bindParam(":iest", $IEST, PDO::PARAM_STR);
            if($sqlUP->execute()){
                    return 'ok';
                }else{
                    return print_r($sqlUP->errorInfo());
                }
            } catch (PDOException $ex) {
                return 'error '.$ex->getMessage();
                
        }
        
    }
    
        public function queryUpdateEnd($user, $dados){
        try{
        $ID = $user;
        $CEP = preg_replace("/[^0-9]/", "",$dados['cep']);;
        $RUA = $dados['rua'];
        $NUM = $dados['numero'];
        $COMPL = $dados['complemento'];
        $BAIRRO = $dados['bairro'];
        $CIDADE = $dados['cidade'];
        $UF = $dados['uf'];
        $SQL= "UPDATE endereco SET cep = :cep, logradouro = :rua, numero = :num, complemento = :compl, bairro = :bairro, cidade = :cidade, estado = :uf WHERE id = :id AND identificador = '2'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":cep", $CEP, PDO::PARAM_STR);
        $sqlUP->bindParam(":rua", $RUA, PDO::PARAM_STR);
        $sqlUP->bindParam(":num", $NUM, PDO::PARAM_STR);
        $sqlUP->bindParam(":compl", $COMPL, PDO::PARAM_STR);
        $sqlUP->bindParam(":bairro", $BAIRRO, PDO::PARAM_STR);
        $sqlUP->bindParam(":cidade", $CIDADE, PDO::PARAM_STR);
        $sqlUP->bindParam(":uf", $UF, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
    }
    
        public function queryUpdateCTT($user, $dados){
        try{
        $ID = $user;
        $DDDTEL = $dados['ddd_telefone'];
        $TEL = preg_replace("/[^0-9]/", "",$dados['telefone']);;
        $DDDCEL = $dados['ddd_celular'];
        $CEL = preg_replace("/[^0-9]/", "",$dados['celular']);;
        $EMAIL = $dados['email'];
        $SQL= "UPDATE contato SET dddTel = :dddtel, tel = :tel, dddCel = :dddcel, cel = :cel, email = :email WHERE id = :id AND identificador = '2'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":dddtel", $DDDTEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":tel", $TEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":dddcel", $DDDCEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":cel", $CEL, PDO::PARAM_STR);
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
    
    
    public function queryDeleteColaborador($dado){
        try{
            
            $SQL = "UPDATE colaborador SET status = 0 WHERE idColaborador = :id ";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlCliente->bindParam(":id", $ID, PDO::PARAM_INT);
                if($sqlCliente->execute()){
                return 'ok';
                }else{
                    return 'erro deleta cliente';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function queryDeleteEndereco($dado){
        try{
            
                $SQL = "UPDATE endereco SET status = 0 WHERE id = :id AND identificador = '2'";
                $sqlEndereco = $this->conecta->conectar()->prepare($SQL);
                $ID = $dado;
                $sqlEndereco->bindParam(":id", $ID, PDO::PARAM_INT);  
                if($sqlEndereco->execute()){
                return 'ok';
                }else{
                    return 'erro deleta endereco';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function queryDeleteDocumentoPF($dado){
        try{
            
                         $SQL = "UPDATE documentopf SET status = 0 WHERE id = :id AND identificador = '2'";
                        $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
                        $ID = $dado;
                        $sqlDocumento->bindParam(":id", $ID, PDO::PARAM_INT);                       
                        // return 'ok';
                                if($sqlDocumento->execute()){
                return 'ok';
                }else{
                    return 'erro deleta documento';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
                public function queryDeleteDocumentoPJ($dado){
        try{
            
                         $SQL = "UPDATE documentopj SET status = 0 WHERE id = :id AND identificador = '2'";
                        $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
                        $ID = $dado;
                        $sqlDocumento->bindParam(":id", $ID, PDO::PARAM_INT);                       
                        // return 'ok';
                                if($sqlDocumento->execute()){
                return 'ok';
                }else{
                    return 'erro deleta documento';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
   public function queryDeleteContato($dado){
        try{
                                $SQL = "UPDATE contato SET status = 0 WHERE id = :id AND identificador = '2'";
                                $sqlContato = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlContato->bindParam(":id", $ID, PDO::PARAM_INT);  
                                 if($sqlContato->execute()){
                                 return 'ok';
                                }else{
                                    return 'erro deleta contato';
                                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }


        public function queryRelatorioPagamentoAberto($id, $inicio, $fim){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,                   
                    usuario.nomeUsuario,
                    pagamentocolaborador.idPagamentoColaborador,
                    pagamentocolaborador.valorPagamento,
                    pagamentocolaborador.dataPagamento,
                    pagamentocolaborador.status,
                    status.situacao
                    FROM ordemservico 
                    INNER JOIN cliente
                    ON ordemservico.idCliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON ordemservico.idColaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON ordemservico.idResponsavel = usuario.idUsuario
                    INNER JOIN pagamentocolaborador
                    ON ordemservico.idOrdemServico = pagamentocolaborador.idOrdemServico
                    INNER JOIN status
                    ON ordemservico.status = status.cod                  
                    WHERE pagamentocolaborador.dataPagamento BETWEEN :d1 AND :d2 AND colaborador.idColaborador = :id AND pagamentocolaborador.status = '1'";
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
    
    
    public function queryRelatorioPagamentoRealizado($id, $inicio, $fim){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,                   
                    usuario.nomeUsuario,
                    pagamentocolaborador.idPagamentoColaborador,
                    pagamentocolaborador.valorPagamento,
                    pagamentocolaborador.dataPagamento,
                    pagamentocolaborador.status,
                    status.situacao
                    FROM ordemservico 
                    INNER JOIN cliente
                    ON ordemservico.idCliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON ordemservico.idColaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON ordemservico.idResponsavel = usuario.idUsuario
                    INNER JOIN pagamentocolaborador
                    ON ordemservico.idOrdemServico = pagamentocolaborador.idOrdemServico
                    INNER JOIN status
                    ON ordemservico.status = status.cod                  
                    WHERE pagamentocolaborador.dataPagamento BETWEEN :d1 AND :d2 AND colaborador.idColaborador = :id AND pagamentocolaborador.status = '5'";
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
    

    public function SomaPagamento(){

        $SQL = "SELECT SUM(valorPagamento) as total 
                FROM pagamentocolaborador 
                WHERE pagamentocolaborador.status = '1'";
        $stmte = $this->conecta->conectar()->query($SQL);
        $stmte->execute();
        $count = $stmte->fetchColumn();
        return $count;
        

    }

    

         public function queryRelatorioPagamentoAreceber($id, $inicio, $fim){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,                   
                    usuario.nomeUsuario,
                    pagamento.idPagamento,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM ordemservico 
                    INNER JOIN cliente
                    ON ordemservico.idCliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON ordemservico.idColaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON ordemservico.idResponsavel = usuario.idUsuario
                    INNER JOIN pagamento
                    ON ordemservico.idOrdemServico = pagamento.idOrdemServico
                    INNER JOIN status
                    ON ordemservico.status = status.cod                  
                    WHERE pagamento.dataVencimento BETWEEN :d1 AND :d2 AND colaborador.idColaborador = :id AND pagamento.status = '1' AND ordemservico.status = 1 ORDER BY pagamento.dataVencimento DESC ";
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
     

public function queryRelatorioPagamentoRecebido($id, $inicio, $fim){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,                   
                    usuario.nomeUsuario,
                    pagamento.idPagamento,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    status.situacao
                    FROM ordemservico 
                    INNER JOIN cliente
                    ON ordemservico.idCliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON ordemservico.idColaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON ordemservico.idResponsavel = usuario.idUsuario
                    INNER JOIN pagamento
                    ON ordemservico.idOrdemServico = pagamento.idOrdemServico
                    INNER JOIN status
                    ON ordemservico.status = status.cod                  
                    WHERE pagamento.dataVencimento BETWEEN :d1 AND :d2 AND colaborador.idColaborador = :id AND pagamento.status = '5' AND ordemservico.status = 1 ORDER BY pagamento.dataVencimento ASC";
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
    
    
        public function SomaPag(){

        $SQL = "SELECT SUM(valorPagamento) as total 
                FROM pagamento 
                WHERE pagamento.status = '1'";
        $stmte = $this->conecta->conectar()->query($SQL);
        $stmte->execute();
        $count = $stmte->fetchColumn();
        return $count;
        

    }
    
    
    
    /************Inicio Fornecedor**********************/
    
    
    public function verificaNomeFornecedor($nome){
        try{
            $SQL ="SELECT * FROM fornecedor WHERE nomeFornecedor = :nome"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $NOME = $nome;
            $sqlSelect->bindParam('nome', $NOME, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
            public function verificaDocumentoPFFornecedor($dado){
        try{
            $SQL ="SELECT * FROM documentopf WHERE cpf = :dado AND identificador = '2'"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $DADO = $dado;
            $sqlSelect->bindParam('dado', $DADO, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
                public function verificaRSocialFornecedor($dado, $tipo){
        try{
            $SQL ="SELECT * FROM fornecedor WHERE razaoSocial = :dado AND tipoFornecedor = :tipo AND status > 0 "; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $DADO = $dado;
            $TIPO = $tipo;
            $sqlSelect->bindParam('dado', $DADO, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPO, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    
       public function queryInsertFornecedor($dados){
        try{
            $this->tipo = $dados['TipoPessoa'];
            $this->nomeFornecedor = $dados['nome'];       
            $this->razaoSocial = $dados['razaoSocial'];
            $this->dataCriacao = $this->objFuncao->dataAtual(2);
            $this->status = $dados['status'];
            $sqlInsert = $this->conecta->conectar()->prepare("INSERT INTO fornecedor (tipoFornecedor, nomeFornecedor, razaoSocial, dataCriacao, status) VALUES (:tipo, :nome, :rSocial, :dC, :status)");
            $sqlInsert->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $sqlInsert->bindParam(":nome", $this->nomeFornecedor, PDO::PARAM_STR);
            $sqlInsert->bindParam(":rSocial", $this->razaoSocial, PDO::PARAM_STR);
            $sqlInsert->bindParam(":dC", $this->dataCriacao, PDO::PARAM_STR);
            $sqlInsert->bindParam(":status", $this->status, PDO::PARAM_STR);
           if($sqlInsert->execute()){             
            $lastId = $this->conecta->conectar()->lastInsertId();
                return $lastId;
                }else{
                    print_r($sqlInsert->errorInfo());
                }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
     
      public function querySelecionaFornecedorPJ($dado, $tipo){
        try{
            $SQL = "SELECT 
            fornecedor.idFornecedor,
            fornecedor.tipoFornecedor,
            fornecedor.nomeFornecedor,
            fornecedor.razaoSocial,
            documentopj.cnpj,
            documentopj.iMunicipal,
            documentopj.iEstadual,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel,
            endereco.cep,
            endereco.numero,
            endereco.logradouro,
            endereco.complemento,
            endereco.bairro,
            endereco.cidade,
            endereco.estado,
            endereco.pais
            FROM fornecedor
            INNER JOIN documentopj ON fornecedor.idFornecedor = documentopj.id
            INNER JOIN contato ON fornecedor.idFornecedor = contato.id
            INNER JOIN endereco ON fornecedor.idFornecedor = endereco.id
            WHERE fornecedor.idFornecedor = :id AND fornecedor.tipoFornecedor = :tipo  AND contato.Identificador = '6' AND endereco.Identificador = '6'     
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOFORNECEDOR = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOFORNECEDOR, PDO::PARAM_STR);
           if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
            }catch (PDOException $ex) {
                return 'erro '.$ex->getMessage();
            }
        }
        
        public function querySelecionaFornecedorPF($dado, $tipo){
        try{
            $SQL = "SELECT 
            fornecedor.idFornecedor,
            fornecedor.tipoFornecedor,
            fornecedor.nomeFornecedor,
            fornecedor.razaoSocial,
            documentopf.cpf,
            documentopf.rg,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel,
            endereco.cep,
            endereco.numero,
            endereco.logradouro,
            endereco.complemento,
            endereco.bairro,
            endereco.cidade,
            endereco.estado,
            endereco.pais
            FROM fornecedor
            INNER JOIN documentopf ON fornecedor.idFornecedor = documentopf.id
            INNER JOIN contato ON fornecedor.idFornecedor = contato.id
            INNER JOIN endereco ON fornecedor.idFornecedor = endereco.id
            WHERE fornecedor.idFornecedor = :id AND fornecedor.tipoFornecedor = :tipo AND contato.Identificador = '6' AND endereco.Identificador = '6'
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOFORNECEDOR = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOFORNECEDOR, PDO::PARAM_STR);
           if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
            }catch (PDOException $ex) {
                return 'erro '.$ex->getMessage();
            }
        }
    
    
    public function querySelectPagFornecedor($inicial, $numreg){
        try{
 $SQL = "SELECT 
            fornecedor.idFornecedor,
            fornecedor.tipoFornecedor,
            fornecedor.nomeFornecedor,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel
            FROM fornecedor
            INNER JOIN contato
            ON fornecedor.idFornecedor = contato.id
            WHERE fornecedor.status = :id AND contato.identificador = '6'
            ORDER BY fornecedor.idFornecedor DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = 1;
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            $sqlSelect->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlSelect->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

       public function querySelectTotalFornecedor(){
        try{
           
            $SQL = "SELECT * FROM fornecedor WHERE status = :id";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $ID = '1';
            $sqlCliente->bindParam("id", $ID, PDO::PARAM_INT);
            $sqlCliente->execute();
            $rowCount = $sqlCliente->rowCount();
            return $rowCount;
            //$sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }

       
    public function queryUpdateFornecedorPF($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados;
        $SQL= "UPDATE fornecedor SET nomeFornecedor = :nome WHERE idFornecedor = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":nome", $NOME, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
        
    }
    
        public function queryUpdateFornecedorPJ($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados['nome'];
        $RSOCIAL = $dados['razaoSocial'];
        $SQL= "UPDATE fornecedor SET nomeFornecedor = :nome, razaoSocial = :rSocial WHERE idFornecedor = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":nome", $NOME, PDO::PARAM_STR);
        $sqlUP->bindParam(":rSocial", $RSOCIAL, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
        
    }
    
            public function queryUpdateDocPFFornecedor($user, $cpf){
         try{
        $ID = $user;
        $CPF = $cpf;
        $SQL= "UPDATE documentopf SET cpf = :cpf WHERE id = :id AND identificador = '6'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":cpf", $CPF, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
    }
    
            public function queryUpdateDocPJFornecedor($user, $dados){
            try{
            $ID = $user;
            $CNPJ = preg_replace("/[^0-9]/", "",$dados['Cnpj']);
            $IMUN = $dados['insc_municipal'];
            $IEST = $dados['insc_estadual'];
            $SQL= "UPDATE documentopj SET cnpj = :cnpj, iMunicipal = :imun, iEstadual = :iest WHERE id = :id AND identificador = '6'";
            $sqlUP = $this->conecta->conectar()->prepare($SQL);  
            $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
            $sqlUP->bindParam(":cnpj", $CNPJ, PDO::PARAM_STR);
            $sqlUP->bindParam(":imun", $IMUN, PDO::PARAM_STR);
            $sqlUP->bindParam(":iest", $IEST, PDO::PARAM_STR);
            if($sqlUP->execute()){
                    return 'ok';
                }else{
                    return print_r($sqlUP->errorInfo());
                }
            } catch (PDOException $ex) {
                return 'error '.$ex->getMessage();
                
        }
        
    }
    
        public function queryUpdateEndFornecedor($user, $dados){
        try{
        $ID = $user;
        $CEP = preg_replace("/[^0-9]/", "",$dados['cep']);;
        $RUA = $dados['rua'];
        $NUM = $dados['numero'];
        $COMPL = $dados['complemento'];
        $BAIRRO = $dados['bairro'];
        $CIDADE = $dados['cidade'];
        $UF = $dados['uf'];
        $SQL= "UPDATE endereco SET cep = :cep, logradouro = :rua, numero = :num, complemento = :compl, bairro = :bairro, cidade = :cidade, estado = :uf WHERE id = :id AND identificador = '6'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":cep", $CEP, PDO::PARAM_STR);
        $sqlUP->bindParam(":rua", $RUA, PDO::PARAM_STR);
        $sqlUP->bindParam(":num", $NUM, PDO::PARAM_STR);
        $sqlUP->bindParam(":compl", $COMPL, PDO::PARAM_STR);
        $sqlUP->bindParam(":bairro", $BAIRRO, PDO::PARAM_STR);
        $sqlUP->bindParam(":cidade", $CIDADE, PDO::PARAM_STR);
        $sqlUP->bindParam(":uf", $UF, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }  
        
    }
    
        public function queryUpdateCTTFornecedor($user, $dados){
        try{
        $ID = $user;
        $DDDTEL = $dados['ddd_telefone'];
        $TEL = preg_replace("/[^0-9]/", "",$dados['telefone']);;
        $DDDCEL = $dados['ddd_celular'];
        $CEL = preg_replace("/[^0-9]/", "",$dados['celular']);;
        $EMAIL = $dados['email'];
        $SQL= "UPDATE contato SET dddTel = :dddtel, tel = :tel, dddCel = :dddcel, cel = :cel, email = :email WHERE id = :id AND identificador = '6'";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":dddtel", $DDDTEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":tel", $TEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":dddcel", $DDDCEL, PDO::PARAM_STR);
        $sqlUP->bindParam(":cel", $CEL, PDO::PARAM_STR);
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
    
     public function queryDeleteFornecedor($dado){
        try{
            
            $SQL = "UPDATE fornecedor SET status = 0 WHERE idFornecedor = :id ";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlCliente->bindParam(":id", $ID, PDO::PARAM_INT);
                if($sqlCliente->execute()){
                return 'ok';
                }else{
                    return 'erro deleta cliente';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function queryDeleteEnderecoFornecedor($dado){
        try{
            
                $SQL = "UPDATE endereco SET status = 0 WHERE id = :id AND identificador = '6'";
                $sqlEndereco = $this->conecta->conectar()->prepare($SQL);
                $ID = $dado;
                $sqlEndereco->bindParam(":id", $ID, PDO::PARAM_INT);  
                if($sqlEndereco->execute()){
                return 'ok';
                }else{
                    return 'erro deleta endereco';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function queryDeleteDocumentoPFFornecedor($dado){
        try{
            
                         $SQL = "UPDATE documentopf SET status = 0 WHERE id = :id AND identificador = '6'";
                        $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
                        $ID = $dado;
                        $sqlDocumento->bindParam(":id", $ID, PDO::PARAM_INT);                       
                        // return 'ok';
                                if($sqlDocumento->execute()){
                return 'ok';
                }else{
                    return 'erro deleta documento';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
                public function queryDeleteDocumentoPJFornecedor($dado){
        try{
            
                         $SQL = "UPDATE documentopj SET status = 0 WHERE id = :id AND identificador = '6'";
                        $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
                        $ID = $dado;
                        $sqlDocumento->bindParam(":id", $ID, PDO::PARAM_INT);                       
                        // return 'ok';
                                if($sqlDocumento->execute()){
                return 'ok';
                }else{
                    return 'erro deleta documento';
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
   public function queryDeleteContatoFornecedor($dado){
        try{
                                $SQL = "UPDATE contato SET status = 0 WHERE id = :id AND identificador = '6'";
                                $sqlContato = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlContato->bindParam(":id", $ID, PDO::PARAM_INT);  
                                 if($sqlContato->execute()){
                                 return 'ok';
                                }else{
                                    return 'erro deleta contato';
                                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }

    
}
    

