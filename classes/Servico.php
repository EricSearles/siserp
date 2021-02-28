<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Servico {
    
    private $conecta;
    private $objOS;
    private $idOS;
    private $dataOS;
    private $validade;
    private $total;
    private $idResponsavel;//usuario
    private $idSolicitante;//colaborador
    private $idCliente;
    private $idServico;
    private $qtd;
    private $tipoPagamento;
    private $formaPagamento;
    private $servicoDisponibilizado;
    private $numDocumento;
    private $numProcesso;
    private $valorItem;
    private $obs;
    private $status;
    private $parcela;
    private $valorParcela;
    private $vencimento;
    
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
    

    
    public function querySelecionaOS(){
        try{
           
            $SQL = "SELECT idOrdemServico FROM ordemservico ORDER BY idOrdemServico DESC LIMIT 1";
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
    
        
    public function querySelectPag($inicial, $numreg){
        try{
          //  $SQL ="SELECT * FROM cliente WHERE status = :id LIMIT :inicial, :numreg";

 $SQL = "SELECT 
            ordemservico.idOrdemServico,
            ordemservico.data,
            ordemservico.idCliente,
            ordemservico.idResponsavel,
            ordemservico.idColaborador,
            ordemservico.validade,
            ordemservico.status,
            cliente.nomeCliente,
            status.situacao
            FROM ordemservico
            INNER JOIN cliente
            ON ordemservico.idCliente = cliente.idCliente
            INNER JOIN status
            ON ordemservico.status = status.cod
            WHERE ordemservico.status > '0' 
            ORDER BY ordemservico.idOrdemServico DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $INICIAL = $inicial;
            $NUMREG = $numreg;
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

       public function querySelectTotal(){
        try{
           
            $SQL = "SELECT * FROM ordemservico WHERE ordemservico.status > '0'";
            $sqlCliente = $this->conecta->conectar()->prepare($SQL);
            $sqlCliente->execute();
            $rowCount = $sqlCliente->rowCount();
            return $rowCount;
            //$sqlCliente->fetchALL(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    } 
    
    
    public function querySelecionaServico($dado){
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
                    WHERE  ordemservico.idOrdemServico = :id";
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
            public function querySelecionaServicoVencimento(){
        try{
          //  $SQL ="SELECT * FROM cliente WHERE status = :id LIMIT :inicial, :numreg";
            
 $SQL = "SELECT 
            ordemservico.idOrdemServico,
            ordemservico.idCliente,
            ordemservico.idResponsavel,
            ordemservico.idColaborador,
            ordemservico.validade,
            ordemservico.status,
            cliente.nomeCliente,
            status.situacao
            FROM ordemservico
            INNER JOIN cliente
            ON ordemservico.idCliente = cliente.idCliente
            INNER JOIN status
            ON ordemservico.status = status.cod
            WHERE ordemservico.status > '0' 
            ORDER BY ordemservico.idOrdemServico DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $INICIAL = $inicial;
            $NUMREG = $numreg;
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
    
        public function querySelecionaServico1($inicial, $numreg, $filtro){
        try{
          //  $SQL ="SELECT * FROM cliente WHERE status = :id LIMIT :inicial, :numreg";
            
 $SQL = "SELECT 
            ordemservico.idOrdemServico,
            ordemservico.data,
            ordemservico.idCliente,
            ordemservico.idResponsavel,
            ordemservico.idColaborador,
            ordemservico.validade,
            ordemservico.status,
            cliente.nomeCliente,
            status.situacao
            FROM ordemservico
            INNER JOIN cliente
            ON ordemservico.idCliente = cliente.idCliente
            INNER JOIN status
            ON ordemservico.status = status.cod
            WHERE ordemservico.status > '0' 
            ORDER BY ordemservico.idOrdemServico DESC LIMIT :inicial, :numreg
            
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $INICIAL = $inicial;
            $NUMREG = $numreg;
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
    
     public function queryInsertOS($dados, $total){
        try{
            //$this->numOrcamento = $dados['nOrcamento'];
            $this->dataOS = $dados['dataOrdemServico'];       
            $this->idResponsavel = $dados['idUsuario'];
            $this->idSolicitante = $dados['idColaborador'];
            $this->idCliente = $dados['idCliente'];
            $this->tipoPagamento = $dados['TipoPagamento'];
            $this->formaPagamento = $dados['formaPagamento'];
            $this->total = $total;
            $this->validade = $dados['validade'];
            $this->status = $dados['status'];
            $sqlInserir = $this->conecta->conectar()->prepare("INSERT INTO `ordemservico` (`data`, `idCliente`, `idResponsavel`, `idColaborador`, `idTipoPagamento`, `idFormaPagamento`, `total`, `validade`, `status`) VALUES (:data, :cliente, :responsavel, :solicitante, :tipo, :forma, :total, :validade, :status)");
            //$sqlInserir->bindParam(":num", $this->numOrcamento , PDO::PARAM_STR);
            $sqlInserir->bindParam(":data", $this->dataOS, PDO::PARAM_STR);
            $sqlInserir->bindParam(":cliente", $this->idCliente, PDO::PARAM_STR);
            $sqlInserir->bindParam(":responsavel", $this->idResponsavel, PDO::PARAM_STR);
            $sqlInserir->bindParam(":solicitante", $this->idSolicitante, PDO::PARAM_STR);          
            $sqlInserir->bindParam(":validade", $this->validade, PDO::PARAM_STR);
            $sqlInserir->bindParam(":tipo", $this->tipoPagamento, PDO::PARAM_STR);
            $sqlInserir->bindParam(":forma", $this->formaPagamento, PDO::PARAM_STR);
            $sqlInserir->bindParam(":total", $this->total, PDO::PARAM_STR);
            $sqlInserir->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlInserir->execute()){
                return $sqlInserir = $this->conecta->conectar()->lastInsertId();
            }else{
                return print_r($sqlInserir->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
 
 
 public function queryInsertItem($dados, $id){
     try{
     $this->idOS = $id;
     $this->idServico = $dados['servico'];
     $this->numDocumento = $dados['nDocumento'];
     $this->numProcesso = $dados['nProcesso'];
     $this->qtd = $dados['qtd'];
     $this->valorItem = $dados['valor'];
     $this->obs = $dados['obs'];
     $this->status = '1';
     $SQL = "INSERT itemordemservico (idOrdemServico, idServicoDisponibilizado, nDocumento, nProcesso, qtd, valorItem, obs, status) VALUES (:os, :servico, :nDoc, :nProc, :qtd, :valor, :obs, :status)"; 
     $sqlInserir = $this->conecta->conectar()->prepare($SQL);
     $sqlInserir->bindParam(":os", $this->idOS , PDO::PARAM_STR);
     $sqlInserir->bindParam(":servico", $this->idServico, PDO::PARAM_STR);
     $sqlInserir->bindParam(":nDoc", $this->numDocumento, PDO::PARAM_STR);
     $sqlInserir->bindParam(":nProc", $this->numProcesso, PDO::PARAM_STR);
     $sqlInserir->bindParam(":qtd", $this->qtd, PDO::PARAM_STR);
     $sqlInserir->bindParam(":valor", $this->valorItem, PDO::PARAM_STR);
     $sqlInserir->bindParam(":obs", $this->obs, PDO::PARAM_STR);
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
    

        public function verificaNome($nome){
        try{
            $SQL ="SELECT * FROM cliente WHERE nome = :nome"; 
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
            $SQL ="SELECT * FROM cliente WHERE razaoSocial = :dado AND tipoCliente = :tipo "; 
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
            $sqlUsuario = $this->conecta->conectar()->prepare("INSERT INTO `cliente` (`tipoCliente`, `nome`, `razaoSocial`, `dataCriacao`, `status`) VALUES (:tipo, :nome, :rSocial, :dC, :status)");
            $sqlUsuario->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":rSocial", $this->razaoSocial, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":dC", $this->dataCriacao, PDO::PARAM_STR);
            $sqlUsuario->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($sqlUsuario->execute()){
                return $sqlUsuario = $this->conecta->conectar()->lastInsertId();
            }else{
                return "erro";
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


    
}
    

