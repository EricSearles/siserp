<?php
require_once "classes/Conexao.php";
require_once "classes/Funcoes.php";

class Orcamento {
    
    private $conecta;
    private $objOrcamento;
    private $numOrcamento;
    private $dataOrcamento;
    private $validade;
    private $total;
    private $idResponsavel;//usuario
    private $idSolicitante;//colaborador
    private $idCliente;
    private $idServico;
    private $idProduto;
    private $qtd;
    private $qtdProduto;
    private $valorItemProduto;
    private $uniMedida;
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
    
       //mostra resultados de orçamento para paginaão
    public function querySelecionaAntigo($inicial, $numreg) {
        try {
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
                    WHERE orcamento.status > 0 AND orcamento.empresa = 0 ORDER BY orcamento.idOrcamento DESC LIMIT :inicial, :numreg";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $sqlOrcamento->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlOrcamento->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            if ($sqlOrcamento->execute()) {
                return $sqlOrcamento->fetchALL(PDO::FETCH_OBJ);
            } else {
                return print_r($sqlOrcamento->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }
    
     //mostra resultados de orçamento para paginaão
    public function querySeleciona($inicial, $numreg, $empresa) {
        try {
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
                    WHERE orcamento.status > 0 AND orcamento.empresa = :empresa ORDER BY orcamento.idOrcamento DESC LIMIT :inicial, :numreg";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $INICIAL = $inicial;
            $NUMREG = $numreg;
            $EMPRESA = $empresa;
            $sqlOrcamento->bindParam("inicial", $INICIAL, PDO::PARAM_INT);
            $sqlOrcamento->bindParam("numreg", $NUMREG, PDO::PARAM_INT);
            $sqlOrcamento->bindParam("empresa", $EMPRESA, PDO::PARAM_INT);
            if ($sqlOrcamento->execute()) {
                return $sqlOrcamento->fetchALL(PDO::FETCH_OBJ);
            } else {
                return print_r($sqlOrcamento->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }
    
        //numero de registros encontrados para paginação
           public function querySelectTotalOrcamento(){
        try{           
            $SQL = "SELECT * FROM orcamento WHERE status > 0";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $sqlOrcamento->execute();
            $rowCount = $sqlOrcamento->rowCount();
            return $rowCount;
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    } 
    
    //seleciona registros validos da tabela orçamento
    public function querySelecionaOrcamento(){
        try{           
            $SQL = "SELECT * FROM orcamento WHERE status > 0 ORDER BY idOrcamento DESC LIMIT 0,1";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            if($sqlOrcamento->execute()){
            return $sqlOrcamento->fetchALL(PDO::FETCH_OBJ);               
            }else{
                return print_r($sqlOrcamento->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
        public function queryAtualizaOrcamento($dado){
        try{     
            $ID = $dado;
            $SQL = "SELECT * FROM orcamento WHERE idOrcamento = :id AND status > 0";
             $SQL = "SELECT 
                    orcamento.idOrcamento,
                    orcamento.numero,
                    orcamento.data,                    
                    orcamento.cliente,
                    orcamento.responsavel,
                    orcamento.colaborador,
                    orcamento.total,
                    orcamento.validade,
                    orcamento.formaPagamento,
                    orcamento.obsOrcamento,
                    orcamento.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,
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
                    WHERE orcamento.idOrcamento = :id AND orcamento.status > 0 ";
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
    
    //seleciona dados para imprimir orçamento
     public function querySelecionaImpressao($dado){
        try{          
            $SQL = "SELECT 
                    orcamento.idOrcamento,
                    orcamento.numero,
                    orcamento.data,
                    orcamento.cliente,
                    orcamento.responsavel,
                    orcamento.colaborador,
                    orcamento.total,
                    orcamento.validade,
                    orcamento.formaPagamento,
                    orcamento.obsOrcamento,
                    orcamento.status,
                    cliente.idCliente,
                    cliente.nomeCliente,
                    cliente.razaoSocial,
                    colaborador.idColaborador,
                    colaborador.nomeColaborador,
                    usuario.idUsuario,
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
                    WHERE  orcamento.idOrcamento = :id";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
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
    
        //seleciona itens do orçamento
               public function querySelecionaItemServico($dado){
        try{           
            $SQL = "SELECT 
                    itemorcamento.idItem,
                    itemorcamento.idServicoDisponibilizado,
                    itemorcamento.qtd,
                    itemorcamento.valorItem,
                    servicodisponibilizado.sigla,                   
                    servicodisponibilizado.descricao
                    FROM itemorcamento 
                    INNER JOIN servicodisponibilizado
                    ON itemorcamento.idServicoDisponibilizado = servicodisponibilizado.idServico
                    WHERE itemorcamento.idOrcamento = :id";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
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
    
    
                   public function querySelecionaItemProduto($dado){
        try{           
            $SQL = "SELECT 
                    itemorcamentoproduto.idItemOrcProduto,
                    itemorcamentoproduto.idProdutoDisponibilizado,
                    itemorcamentoproduto.qtdProduto,
                    itemorcamentoproduto.uM,
                    itemorcamentoproduto.valorItemProduto,
                    produto.codigo,     
                    produto.nomeProduto,  
                    produto.medida,  
                    produto.unidadeMedida,  
                    produto.descricao,
                    produto.idFornecedor,
                    unidademedida.sigla,
                    fornecedor.nomeFornecedor
                    FROM itemorcamentoproduto
                    INNER JOIN produto
                    ON itemorcamentoproduto.idProdutoDisponibilizado = produto.idProduto
                    INNER JOIN unidademedida
                    ON itemorcamentoproduto.uM = unidademedida.idUnidadeMedida
                    INNER JOIN fornecedor
                    ON produto.idFornecedor = fornecedor.idFornecedor
                    WHERE itemorcamentoproduto.idOrcamento = :id";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
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
    
    
    
    
     public function querySelecionaRespCliente($dado){
        try{

            $SQL = "SELECT * FROM responsavelcliente WHERE idCliente = :id";
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
  
    public function queryInsertOrcamento($dados, $total, $empresa){
        try{
            $this->numOrcamento = $dados['nOrcamento'];
            $this->dataOrcamento = $dados['dataOrcamento'];       
            $this->idResponsavel = $dados['idUsuario'];
            $this->idSolicitante = $dados['idColaborador'];
            $this->idCliente = $dados['idCliente'];
            $this->total = $total;
            $fPag = $dados['fPagamento'];
            $obs = $dados['obsOrcamento'];
            $this->validade = $dados['validade'];
            $this->empresa = $empresa;
            $this->status = $dados['status'];
            $sqlInserir = $this->conecta->conectar()->prepare("INSERT INTO `orcamento` (`numero`, `data`, `responsavel`, `colaborador`, `cliente`, `total`, `validade`, `formaPagamento`, `obsOrcamento`, `empresa`, `status`) VALUES (:num, :data, :responsavel, :solicitante, :cliente, :total, :validade, :pag, :obs, :empresa, :status)");
            $sqlInserir->bindParam(":num", $this->numOrcamento , PDO::PARAM_STR);
            $sqlInserir->bindParam(":data", $this->dataOrcamento, PDO::PARAM_STR);
            $sqlInserir->bindParam(":responsavel", $this->idResponsavel, PDO::PARAM_STR);
            $sqlInserir->bindParam(":solicitante", $this->idSolicitante, PDO::PARAM_STR);
            $sqlInserir->bindParam(":cliente", $this->idCliente, PDO::PARAM_STR);
            $sqlInserir->bindParam(":validade", $this->validade, PDO::PARAM_STR);
            $sqlInserir->bindParam(":total", $this->total, PDO::PARAM_STR);
            $sqlInserir->bindParam(":pag", $fPag, PDO::PARAM_STR);
            $sqlInserir->bindParam(":obs", $obs, PDO::PARAM_STR);
            $sqlInserir->bindParam(":empresa", $this->empresa, PDO::PARAM_STR);
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
    
public function querySelecionaContatoCliente($dado){
        try{           
            $SQL = "SELECT * FROM contato WHERE id = :id AND identificador = '1'";
            $sqlOrcamento = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
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
    
     public function querySelecionaAssinaturaEmail($dado){
        try{
 $SQL = "SELECT 
            usuario.nomeUsuario,
            usuario.email,
            contato.dddcel,
            contato.cel
            FROM usuario
            INNER JOIN contato
            ON usuario.idUsuario = contato.id
            WHERE contato.id = :id AND contato.identificador = '5' AND usuario.status = 1
            ";
            $sqlSelect = $this->conecta->conectar()->prepare($SQL);
            $ID = $dado;
            $sqlSelect->bindParam('id', $ID, PDO::PARAM_STR);
            if($sqlSelect->execute()){
            return $sqlSelect->fetchALL(PDO::FETCH_OBJ);
             }else{
                return print_r($sqlSelect->errorInfo());
            }
        }catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
 public function queryInsertItemServico($dados, $id){
     try{
     $this->idOrcamento = $id;
     $this->idServico = $dados['servico'];
     $this->qtd = $dados['qtd'];
     $this->valorItem = $dados['valor'];
     $this->status = '1';
     $SQL = "INSERT itemorcamento (idOrcamento, idServicoDisponibilizado, qtd, valorItem, status) VALUES (:orcamento, :servico, :qtd, :valor, :status)"; 
     $sqlInserir = $this->conecta->conectar()->prepare($SQL);
     $sqlInserir->bindParam(":orcamento", $this->idOrcamento , PDO::PARAM_STR);
     $sqlInserir->bindParam(":servico", $this->idServico, PDO::PARAM_STR);
     $sqlInserir->bindParam(":qtd", $this->qtd, PDO::PARAM_STR);
     $sqlInserir->bindParam(":valor", $this->valorItem, PDO::PARAM_STR);
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
 
 
  public function queryInsertItemProduto($dados, $id){
     try{
     $this->idOrcamento = $id;
     $this->idProduto = $dados['produto'];
     $this->qtdProduto = $dados['qtdProduto'];
     $this->valorItemProduto = $dados['valorProduto'];
     $this->uniMedida = $dados['uMedida'];
     $this->status = '1';
     $SQL = "INSERT itemorcamentoproduto (idOrcamento, idProdutoDisponibilizado, qtdProduto, uM, valorItemProduto, status) VALUES (:orcamento, :produto, :qtdProduto, :uMedida, :valorProduto, :status)"; 
     $sqlInserir = $this->conecta->conectar()->prepare($SQL);
     $sqlInserir->bindParam(":orcamento", $this->idOrcamento , PDO::PARAM_STR);
     $sqlInserir->bindParam(":produto", $this->idProduto, PDO::PARAM_STR);
     $sqlInserir->bindParam(":qtdProduto", $this->qtdProduto, PDO::PARAM_STR);
     $sqlInserir->bindParam(":uMedida", $this->uniMedida, PDO::PARAM_STR);
     $sqlInserir->bindParam(":valorProduto", $this->valorItemProduto, PDO::PARAM_STR);
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
 
         public function queryUpdateOrcamento($dados, $vTotal){
        try{
        $ID = $dados['idOrcamento'];
        $NUM = $dados['nOrcamento'];
        $DATA    =  date('Y-m-d', strtotime(str_replace("/", "-",($dados['dataOrcamento']))));
        $USUARIO    = $dados['idUsuario'];
        $COLABORADOR    = $dados['idColaborador'];
        $CLIENTE    = $dados['idCliente'];
        $VALIDADE    = date('Y-m-d', strtotime(str_replace("/", "-",($dados['dataValidade']))));
        $TOTAL    = $this->objFuncao->SubstituiVirgula($vTotal);
        $FPAG = $dados['fPagamento'];
        $OBS = $dados['obs'];
        $SQL= "UPDATE orcamento SET numero = :num, data = :data , responsavel = :responsavel, colaborador = :colaborador, cliente = :cliente , total = :total, validade = :validade, formaPagamento= :pag, obsOrcamento= :obs  WHERE idOrcamento = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":num", $NUM, PDO::PARAM_STR);
        $sqlUP->bindParam(":data", $DATA, PDO::PARAM_STR);        
        $sqlUP->bindParam(":responsavel", $USUARIO, PDO::PARAM_STR);
        $sqlUP->bindParam(":colaborador", $COLABORADOR, PDO::PARAM_STR);
        $sqlUP->bindParam(":cliente", $CLIENTE, PDO::PARAM_STR);
        $sqlUP->bindParam(":total", $TOTAL, PDO::PARAM_STR);
        $sqlUP->bindParam(":validade", $VALIDADE, PDO::PARAM_STR);
        $sqlUP->bindParam(":pag", $FPAG, PDO::PARAM_STR);
        $sqlUP->bindParam(":obs", $OBS, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }
        
        
    }
    
/*##################################################################*/
    
   public function queryUpdateItemServico($id, $dado){
        try{
        $ID = $id;
        $SERVICO = $dado;
        $SQL= "UPDATE itemorcamento SET idServicoDisponibilizado = :servico WHERE idItem = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":servico", $SERVICO, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
    
   public function queryUpdateItemProduto($id, $dado){
        try{
        $ID = $id;
        $PRODUTO = $dado;
        $SQL= "UPDATE itemorcamentoproduto SET idProdutoDisponibilizado = :produto WHERE idItemOrcProduto = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":produto", $PRODUTO, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }   
   
        public function queryUpdateItemQtd($id, $dado){
        try{
        $ID = $id;
        $QTD = $dado;
        $SQL= "UPDATE itemorcamento SET qtd = :qtd WHERE idItem = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":qtd", $QTD, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
    
        public function queryUpdateItemQtdProduto($id, $dado){
        try{
        $ID = $id;
        $QTD = $dado;
        $SQL= "UPDATE itemorcamentoproduto SET qtdProduto = :qtd WHERE idItemOrcProduto = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":qtd", $QTD, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
    
     public function queryUpdateItemUMedida($id, $dado){
        try{
        $ID = $id;
        $UM = $dado;
        $SQL= "UPDATE itemorcamentoproduto SET uM = :um WHERE idItemOrcProduto = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":um", $UM, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
    
    
        public function queryUpdateItemValor($id, $dado){
        try{
        $ID = $id;
        $VALOR = $this->objFuncao->SubstituiVirgula($dado);
        $SQL= "UPDATE itemorcamento SET valorItem = :valor WHERE idItem = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":valor", $VALOR, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
    
            public function queryUpdateItemValorProduto($id, $dado){
        try{
        $ID = $id;
        $VALOR = $this->objFuncao->SubstituiVirgula($dado);
        $SQL= "UPDATE itemorcamentoproduto SET valorItemProduto = :valor WHERE  idItemOrcProduto = :id";
        $sqlUP = $this->conecta->conectar()->prepare($SQL);  
        $sqlUP->bindParam(":id", $ID, PDO::PARAM_INT);
        $sqlUP->bindParam(":valor", $VALOR, PDO::PARAM_STR);
        if($sqlUP->execute()){
                return 'ok';
            }else{
                return print_r($sqlUP->errorInfo());
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
                
        }   
    }
 
 
 
 /*###############################################################*/
 
 
    public function queryUpdateClientePF($user, $dados){
        try{
        $ID = $user;
        $NOME = $dados;
        $SQL= "UPDATE cliente SET nome = :nome WHERE idCliente = :id";
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
        $SQL= "UPDATE cliente SET nome = :nome, razaoSocial = :rSocial WHERE idCliente = :id";
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
           public function queryUpdateSituacaoOrcamento($dado, $situacao){
        try{
        $ID = $dado;
        $SIT = $situacao;
        $SQL= "UPDATE orcamento SET status = :situacao WHERE idOrcamento = :id";
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

   public function queryDeleteOrcamento($dado){
        try{
                                $SQL = "UPDATE orcamento SET status = 0 WHERE idOrcamento = :id ";
                                $sqlContato = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlContato->bindParam(":id", $ID, PDO::PARAM_INT);  
                                 if($sqlContato->execute()){
                                 return 'ok';
                                }else{
                                    return 'erro deleta orcamento';
                                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
    
           public function queryDeleteItemOrcamento($dado){
        try{
                                $SQL = "UPDATE itemorcamento SET status = 0 WHERE idOrcamento = :id";
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
    
               public function queryDeleteItemProduto($dado){
        try{
                                $SQL = "UPDATE itemorcamentoproduto SET status = 0 WHERE idOrcamento = :id";
                                $sqlExclui = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlExclui->bindParam(":id", $ID, PDO::PARAM_INT);  
                                 if($sqlExclui->execute()){
                                 return 'ok';
                                }else{
                                    return 'erro deleta contato';
                                }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
    
    
    
       public function queryDeleteItem($dado){
        try{
                                $SQL = "UPDATE itemorcamento SET status = 0 WHERE idItem = :id";
                                $sqlItem = $this->conecta->conectar()->prepare($SQL);
                                $ID = $dado;
                                $sqlItem->bindParam(":id", $ID, PDO::PARAM_INT);  
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
    

