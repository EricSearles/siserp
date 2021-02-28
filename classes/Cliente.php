<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Cliente {
    
    private $conecta;
    private $objFuncao;
    private $idCliente;
    private $tipo;
    private $nome;
    private $razaoSocial;
    private $dataCriacao;
    private $status;
    private $doc;
    private $iEstadual;
    private $iMunicipal;
    
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
    

    //mesma que public function querySelecionaUser em Usuario
    public function querySeleciona($dado){
        try{
           
            $SQL = "SELECT idUsuario, nomeUsuario, email, dataCriacao, ultimoLog,permissao FROM 'usuario' WHERE idCliente = :id";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $id = $idCliente;
            $sqlCliente->bindParam(":id", $id, PDO::PARAM_INT);
            $sqlCliente->execute();
            return $sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelecionaClientePJ($dado, $tipo){
        try{
            $SQL = "SELECT 
            cliente.idCliente,
            cliente.tipoCliente,
            cliente.nomeCliente,
            cliente.razaoSocial,
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
            FROM cliente
            INNER JOIN documentopj ON cliente.idCliente = documentopj.id
            INNER JOIN contato ON cliente.idCliente = contato.id
            INNER JOIN endereco ON cliente.idCliente = endereco.id
            WHERE cliente.idCliente = :id AND cliente.tipoCliente = :tipo  AND contato.Identificador = '1' AND endereco.Identificador = '1'     
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOCLIENTE = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOCLIENTE, PDO::PARAM_STR);
           if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                return print_r($sqlSelect->errorInfo());
            }
            }catch (PDOException $ex) {
                return 'erro '.$ex->getMessage();
            }
        }
        
        public function querySelecionaClientePF($dado, $tipo){
        try{
           // $SQL ="SELECT idCliente, , tipoCliente, nome, razaoSocial, dataCriacao FROM usuario WHERE idCliente = :id "; 
            $SQL = "SELECT 
            cliente.idCliente,
            cliente.tipoCliente,
            cliente.nomeCliente,
            cliente.razaoSocial,
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
            FROM cliente
            INNER JOIN documentopf ON cliente.idCliente = documentopf.id
            INNER JOIN contato ON cliente.idCliente = contato.id
            INNER JOIN endereco ON cliente.idCliente = endereco.id
            WHERE cliente.idCliente = :id AND cliente.tipoCliente = :tipo AND contato.Identificador = '1' AND endereco.Identificador = '1'
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $USER = $dado;
            $TIPOCLIENTE = $tipo;
            $sqlSelect->bindParam('id', $USER, PDO::PARAM_STR);
            $sqlSelect->bindParam('tipo', $TIPOCLIENTE, PDO::PARAM_STR);
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
            cliente.idCliente,
            cliente.tipoCliente,
            cliente.nomeCliente,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel
            FROM cliente
            INNER JOIN contato
            ON cliente.idCliente = contato.id
            WHERE cliente.status = :id AND contato.identificador = '1'
            ORDER BY cliente.idCliente DESC LIMIT :inicial, :numreg
            
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
           
            $SQL = "SELECT * FROM cliente WHERE status = :id";
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
    
        public function querySelecionaImpressao($dado){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.nomeCliente,
                    colaborador.nomeColaborador,
                    usuario.nomeUsuario,
                    pagamento.idPagamento,
                    pagamento.parcela,
                    pagamento.valor,
                    pagamento.dataVencimento,
                    pagamento.status,
                    formapagamento.formaPagamento,
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
                    INNER JOIN formapagamento
                    ON ordemservico.idFormaPagamento = formapagamento.idFormaPagamento
                    INNER JOIN status
                    ON ordemservico.status = status.cod
                    WHERE  ordemservico.idCliente = :id";
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
    
     public function querySelecionaRelatorio($id, $inicio, $fim){
        try{          
            $SQL = "SELECT 
                    ordemservico.idOrdemServico,
                    ordemservico.data,
                    ordemservico.total,
                    ordemservico.validade,
                    ordemservico.status,
                    cliente.nomeCliente,
                    colaborador.nomeColaborador,
                    usuario.nomeUsuario,
                    formapagamento.formaPagamento,
                    status.situacao
                    FROM ordemservico 
                    INNER JOIN cliente
                    ON ordemservico.idCliente = cliente.idCliente
                    INNER JOIN colaborador
                    ON ordemservico.idColaborador = colaborador.idColaborador
                    INNER JOIN usuario
                    ON ordemservico.idResponsavel = usuario.idUsuario
                    INNER JOIN formapagamento
                    ON ordemservico.idFormaPagamento = formapagamento.idFormaPagamento
                    INNER JOIN status
                    ON ordemservico.status = status.cod
                    WHERE  ordemservico.idCliente = :id AND ordemservico.data BETWEEN :d1 AND :d2 AND ordemservico.status = 1";
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
    
    
            //seleciona registros validos da tabela orçamento
               public function querySelecionaItem($dado){
        try{           
            $SQL = "SELECT 
                    itemordemservico.idItemOS,
                    itemordemservico.nDocumento,
                    itemordemservico.nProcesso,
                    itemordemservico.qtd,
                    itemordemservico.valorItem,
                    itemordemservico.obs,
                    itemordemservico.entrega,
                    itemordemservico.status,
                    servicodisponibilizado.sigla,                   
                    servicodisponibilizado.descricao,
                    status.situacao
                    FROM itemordemservico 
                    INNER JOIN servicodisponibilizado
                    ON itemordemservico.idServicoDisponibilizado = servicodisponibilizado.idServico
                    INNER JOIN status
                    ON itemordemservico.status = status.cod
                    WHERE itemordemservico.idOrdemServico = :id";
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
                    WHERE orcamento.cliente = :id AND orcamento.status > 0 
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
            colaborador.nomeColaborador,
            usuario.nomeUsuario,
            status.situacao
            FROM ordemservico
            INNER JOIN cliente
            ON ordemservico.idCliente = cliente.idCliente
            INNER JOIN colaborador
            ON ordemservico.idColaborador = colaborador.idColaborador
            INNER JOIN usuario
            ON ordemservico.idResponsavel = usuario.idUsuario
            INNER JOIN status
            ON ordemservico.status = status.cod
            WHERE ordemservico.idCliente = :id AND ordemservico.status > 0 
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
    
        public function querySelecionaRespCliente($dado){
        try{
 $SQL = "SELECT 
            responsavelcliente.idResponsavel,
            responsavelcliente.idCliente,
            responsavelcliente.nomeResponsavel,
            responsavelcliente.status,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel
            FROM responsavelcliente
            INNER JOIN contato
            ON responsavelcliente.idResponsavel = contato.id
            WHERE responsavelcliente.idCliente = :id AND contato.identificador = '4' AND responsavelcliente.status = 1
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            if($sqlSelect->execute()){
                return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                print_r($sqlSelect->errorInfo());
             }

        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    
            public function querySelecionaRespClienteUp($dado){
        try{
 $SQL = "SELECT 
            responsavelcliente.idResponsavel,
            responsavelcliente.idCliente,
            responsavelcliente.nomeResponsavel,
            responsavelcliente.status,
            contato.email,
            contato.dddTel,
            contato.tel,
            contato.dddCel,
            contato.cel
            FROM responsavelcliente
            INNER JOIN contato
            ON responsavelcliente.idResponsavel = contato.id
            WHERE responsavelcliente.idResponsavel = :id AND contato.identificador = '4' AND responsavelcliente.status = 1
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam("id", $ID, PDO::PARAM_STR);
            if($sqlSelect->execute()){
                return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
            }else{
                print_r($sqlSelect->errorInfo());
             }

        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
     //mesma que verifica Nome em Usuario
        public function verificaNome($nome){
        try{
            $SQL ="SELECT * FROM cliente WHERE nomeCliente = :nome  AND status > 0"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $NOME = $nome;
            $sqlSelect->bindParam('nome', $NOME, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
            public function verificaNomeResponsavel($nome){
        try{
            $SQL ="SELECT * FROM responsavelcliente WHERE nomeResponsavel = :nome  AND status > 0"; 
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $NOME = $nome;
            $sqlSelect->bindParam('nome', $NOME, PDO::PARAM_STR);
            $sqlSelect->execute();
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
            public function verificaRSocial($dado, $tipo){
        try{
            $SQL ="SELECT * FROM cliente WHERE razaoSocial = :dado AND tipoCliente = :tipo AND status > 0 "; 
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
            $sqlUsuario = $this->conecta->conectar()->prepare("INSERT INTO `cliente` (`tipoCliente`, `nomeCliente`, `razaoSocial`, `dataCriacao`, `status`) VALUES (:tipo, :nome, :rSocial, :dC, :status)");
            $sqlUsuario->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":rSocial", $this->razaoSocial, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":dC", $this->dataCriacao, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlUsuario->execute()){
               $lastId = $this->conecta->conectar()->lastInsertId();
                return $lastId;
                }else{
                    print_r($sqlUsuario->errorInfo());
                
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
        public function queryInsertDoc($dados){
       try{
            $ID = $dados['0'];
            $IDENTIFICADOR = $dados['1'];       
            $TIPO = $dados['2'];
            $NUMERO = $dados['3'];
            $STATUS = $dados['4'];
            $SQL = "INSERT INTO `documento` (`id`, `identificador`, `tipoDocumento`, `numero`, `status`) VALUES (?, ?, ?, ?, ?)";
            
           $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
          // print_r($sqlDocumento);
           
            $sqlDocumento->bindParam(1, $ID, PDO::PARAM_STR);
           $sqlDocumento->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
           $sqlDocumento->bindParam(3, $TIPO, PDO::PARAM_STR);
           $sqlDocumento->bindParam(4, $NUMERO, PDO::PARAM_STR);
            $sqlDocumento->bindParam(5, $STATUS, PDO::PARAM_STR);
            if($sqlDocumento->execute()){
                return "ok";
            }else{
                print_r($sqlDocumento->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
    //mesma que queryInsertDocPF em Usuario
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
            }//else{
              //  print_r($sqlDocumento->errorInfo());
         // }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
    
    
            public function queryInsertDocPJ($dados){
       try{
            $ID = $dados['0'];
            $IDENTIFICADOR = $dados['1'];       
            $CNPJ = preg_replace("/[^0-9]/", "",$dados['2']);
            $IMUNICIPAL = preg_replace("/[^0-9]/", "",$dados['3']);
            $IESTADUAL = preg_replace("/[^0-9]/", "",$dados['4']);
            $STATUS = $dados['5'];
            $SQL = "INSERT INTO `documentopj` (`id`, `identificador`, `cnpj`, `iMunicipal`, `iEstadual`, `status`) VALUES (?, ?, ?, ?, ?, ?)";
            
           $sqlDocumento = $this->conecta->conectar()->prepare($SQL);
          // print_r($sqlDocumento);
           
            $sqlDocumento->bindParam(1, $ID, PDO::PARAM_STR);
           $sqlDocumento->bindParam(2, $IDENTIFICADOR, PDO::PARAM_STR);
           $sqlDocumento->bindParam(3, $CNPJ, PDO::PARAM_STR);
           $sqlDocumento->bindParam(4, $IMUNICIPAL, PDO::PARAM_STR);
            $sqlDocumento->bindParam(5, $IESTADUAL, PDO::PARAM_STR);
            $sqlDocumento->bindParam(6, $STATUS, PDO::PARAM_STR);
            if($sqlDocumento->execute()){
                return "ok";
            }else{
                "erro";//print_r($sqlDocumento->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
    
    //mesma que queryInsertEnd em Usuario
            public function queryInsertEnd($dados){
       try{
            $ID = $dados['0'];
            $IDENTIFICADOR = $dados['1'];       
            $CEP = preg_replace("/[^0-9]/", "",$dados['2']);
            $LOGRADOURO = $dados['3'];
            $NUMERO = $dados['4'];
            $COMPLEMENTO = $dados['5'];
            $BAIRRO = $dados['6'];
            $CIDADE = $dados['7'];
            $ESTADO = $dados['8'];
            $PAIS = $dados['9'];
            $STATUS = $dados['10'];
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
                return print_r($sqlEndereco->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
    // mesma que queryInsertCTT em Usuario
                public function queryInsertCTT($dados){
       try{
            $ID = $dados['0'];
            $IDENTIFICADOR = $dados['1'];       
            $DDDTEL = $dados['2'];
            $TEL = preg_replace("/[^0-9]/", "",$dados['3']);
            $DDDCEL = $dados['4'];
            $CEL = preg_replace("/[^0-9]/", "",$dados['5']);
            $EMAIL = $dados['6'];
            $STATUS = $dados['7'];
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
    
    public function queryInsereResponsavelCliente($idCliente,$dados){
        try{
            $ID = $idCliente;
            $this->nomeResponsavel = $_POST['nomeResponsavel'];       
            $this->status = $_POST['status'];
            $sqlUsuario = $this->conecta->conectar()->prepare("INSERT INTO `responsavelcliente` (idCliente, nomeResponsavel, status) VALUES (:id, :nomeResponsavel, :status)");
            $sqlUsuario->bindParam(":id", $ID, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":nomeResponsavel", $this->nomeResponsavel, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlUsuario->execute()){
               $lastId = $this->conecta->conectar()->lastInsertId();
                return $lastId;
                }else{
                    print_r($sqlUsuario->errorInfo());
                
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function queryInsereCTTResponsavel($id, $dados){
       try{
            $ID = $id;
            $IDENTIFICADOR = $dados['cttIdent'];       
            $DDDTEL = $dados['cttDDDtel'];
            $TEL = preg_replace("/[^0-9]/", "", $dados['cttTel']);
            $DDDCEL = $dados['cttDDDcel'];;
            $CEL = preg_replace("/[^0-9]/", "", $dados['cttCel']);
            $EMAIL = $dados['cttEmail'];
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
                return $dados;//print_r($sqlCTT->errorInfo());
          }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
       }
    }
    
    
    public function queryUpdateClientePF($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados;
        $SQL= "UPDATE cliente SET nomeCliente = :nome WHERE idCliente = :id";
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
    
        public function queryUpdateClientePJ($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados['nome'];
        $RSOCIAL = $dados['razaoSocial'];
        $SQL= "UPDATE cliente SET nomeCliente = :nome, razaoSocial = :rSocial WHERE idCliente = :id";
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
        $SQL= "UPDATE documentopf SET cpf = :cpf WHERE id = :id AND identificador = '1'";
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
            $SQL= "UPDATE documentopj SET cnpj = :cnpj, iMunicipal = :imun, iEstadual = :iest WHERE id = :id AND identificador = '1'";
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
        $SQL= "UPDATE endereco SET cep = :cep, logradouro = :rua, numero = :num, complemento = :compl, bairro = :bairro, cidade = :cidade, estado = :uf WHERE id = :id AND identificador = '1'";
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
    
    //mesma que queryUpdateCTT em Usuario
        public function queryUpdateCTT($user, $dados){
        try{
        $ID = $user;
        $DDDTEL = $dados['ddd_telefone'];
        $TEL = preg_replace("/[^0-9]/", "",$dados['telefone']);;
        $DDDCEL = $dados['ddd_celular'];
        $CEL = preg_replace("/[^0-9]/", "",$dados['celular']);;
        $EMAIL = $dados['email'];
        $SQL= "UPDATE contato SET dddTel = :dddtel, tel = :tel, dddCel = :dddcel, cel = :cel, email = :email WHERE id = :id AND identificador = '1'";
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
    
        public function updateResponsavelCliente($dado, $id){
        try{
            
            $SQL = "UPDATE responsavelcliente SET nomeResponsavel = :nome WHERE idResponsavel = :id ";
            $sqlResponsavel = $this->conecta->conectar()->prepare($SQL);
            $ID = $id;
            $NOME = $dado['nomeResponsavel'];
            $sqlResponsavel->bindParam(":id", $ID, PDO::PARAM_INT);
            $sqlResponsavel->bindParam(":nome", $NOME, PDO::PARAM_STR);
                if($sqlResponsavel->execute()){
                return 'ok';
                }else{
                    return print_r($sqlResponsavel->errorInfo());
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
    
     public function queryUpdateCTTResponsavel($dados, $id){
        try{
        $ID = $id;
        $DDDTEL = $dados['dddTelResponsavel'];
        $TEL = preg_replace("/[^0-9]/", "",$dados['telResponsavel']);;
        $DDDCEL = $dados['dddCelResponsavel'];
        $CEL = preg_replace("/[^0-9]/", "",$dados['celResponsavel']);;
        $EMAIL = $dados['emailResponsavel'];
        $SQL= "UPDATE contato SET dddTel = :dddtel, tel = :tel, dddCel = :dddcel, cel = :cel, email = :email WHERE id = :id AND identificador = '4'";
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
    
    //mesma que queryDelete em Usuario
    public function queryDeleteCliente($dado){
        try{
            
            $SQL = "UPDATE cliente SET status = 0 WHERE idCliente = :id ";
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
            
                $SQL = "UPDATE endereco SET status = 0 WHERE id = :id AND identificador = '1' ";
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
            
                         $SQL = "UPDATE documentopf SET status = 0 WHERE id = :id AND identificador = '1'";
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
            
                         $SQL = "UPDATE documentopj SET status = 0 WHERE id = :id AND identificador = '1'";
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
                                $SQL = "UPDATE contato SET status = 0 WHERE id = :id AND identificador = '1'";
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
    
    public function queryDeleteResponsavelCliente($dado){
        try{
            
            $SQL = "UPDATE responsavelcliente SET status = 0 WHERE idResponsavel = :id ";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlCliente->bindParam(":id", $ID, PDO::PARAM_INT);
                if($sqlCliente->execute()){
                return 'ok';
                }else{
                    return print_r($sqlCliente->errorInfo());
                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
      public function queryDeleteContatoResponsavel($dado){
        try{
                                $SQL = "UPDATE contato SET status = 0 WHERE id = :id AND identificador = '4'";
                                $sqlContato = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlContato->bindParam(":id", $ID, PDO::PARAM_INT);  
                                 if($sqlContato->execute()){
                                 return 'ok';
                                }else{
                                    return print_r($sqlCliente->errorInfo());
                                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }


    
}
    

