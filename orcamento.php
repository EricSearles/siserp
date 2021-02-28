<?php
session_start();
unset($_SESSION['erroResponsavel']);
unset($_SESSION['erroSolicitante']);
unset($_SESSION['erroCliente']);
if (!empty($_SESSION['id'])) {

    require_once 'classes/Funcoes.php';
    require_once 'classes/Orcamento.php';
    require_once 'classes/Permissao.php';
    require_once 'classes/ServicoDisponibilizado.php';

    $objOrcamento = new Orcamento();
    $objFuncao = new Funcoes();
    $objPermissao = new Permissao();
    $objServico = new ServicoDisponibilizado();

    if (isset($_POST['btnCadastrar']) && $_POST['btnCadastrar'] == 'Gerar Orçamento') {

        $dados = $_POST;
        $empresa = $_SESSION['empresa'];

        //valida campos Responsavel, Solicitante e Cliente
        if ($dados['idUsuario'] < '1') {

            $_SESSION['erroResponsavel'] = "Você preencheu o nome do Responsável manualmente, <br/>por favor utilize a opção de preenchimento do sistema.";
        } else if (empty($dados['idColaborador'])) {

            $_SESSION['erroSolicitante'] = "Você preencheu o nome do Colaborador manualmente, <br/>por favor utilize a opção de preenchimento do sistema.";
        } else if (empty($dados['idCliente'])) {

            $_SESSION['erroCliente'] = "Você preencheu o nome do Cliente manualmente, <br/>por favor utilize a opção de preenchimento do sistema.";
        }


        //para pegar valores das arrays aninhadas serviço      
        $servico = array($dados['servico']);
        foreach ($servico as $serv) {}
        $quantidade = array($dados['qtd']);

            foreach ($quantidade as $qtd) {
                $numQtd = count($qtd);
            }

        $total = array($dados['valorItem']);

            foreach ($total as $valor) {
                $numValor = count($valor);
                //substitui virgulas por pontos
                $valorTotal = str_replace(',', '.', $valor);
            }

        //multiplica o valor pela quantidade do item
        for ($i = 1; $i <= $numValor; $i++) {

            $soma[$i] = $qtd[$i] * $valorTotal[$i];
        }

        //soma os valores da array, valor total do orçamento
        $totalItem = array_sum($soma);

        //para pegar valores das arrays aninhadas produto     
        $produto = array($dados['produto']);

        foreach ($produto as $prod) {
        }

        $quantidade = array($dados['qtdProduto']);

        foreach ($quantidade as $qtdProduto) {

            $numQtdProduto = count($qtdProduto);
        }

        $unidadeMedida = array($dados['medida']);

        foreach ($unidadeMedida as $uniMedida) {

            $numUnidadeMedida = count($uniMedida);
        }

        $totalProduto = array($dados['valorItemProduto']);

        foreach ($totalProduto as $valorProduto) {

            $numValorProduto = count($valorProduto);

            //substitui virgulas por pontos
            $valorTotalProduto = str_replace(',', '.', $valorProduto);
        }

        //multiplica o valor pela quantidade do item
        for ($i = 1; $i <= $numValorProduto; $i++) {

            $somaProduto[$i] = $qtdProduto[$i] * $valorTotalProduto[$i];
        }

        //soma os valores da array, valor total do orçamento
        $totalItemProduto = array_sum($somaProduto);

        //calcula valor total do orçamento
        $final = $totalItemProduto + $totalItem;

        //insere dados na tabela orcamento e retorna o ultimo id inserido  
        $ultimoId = $objOrcamento->queryInsertOrcamento($dados, $final, $empresa);

        //verifica se os dados foram inseridos na tabela orçamento
        if (empty($ultimoId)) {

            echo 'Não foi possivel cadastrar o orçamento, tente novamente ou entre em contato com o desenvolvedor';
        } else {

            //verifica se a array serviço esta vazia
            if (in_array('0', $dados['servico'])) {
            } else {

                //conta o tamanho da array para inserir na tabela itenOrcamento (Serviços)
                $tamanho = count($valor);

                //monta a repetição para o insert
                for ($i = 1; $i <= $tamanho; $i++) {
                    //cria a array item que será passada para função
                    $itens = array('servico' => $serv[$i], 'qtd' => $qtd[$i], 'valor' => $valor[$i]);
                    //substitui as virgulas por pontos
                    $trataItens = str_replace(',', '.', $itens);
                    //insere dados na tabela itensorcamento
                    $insere = $objOrcamento->queryInsertItemServico($trataItens, $ultimoId);
                }
            }

            //verifica se a array produto esta vazia
            if (in_array('0', $dados['produto'])) {
            } else {

                $tamanhoProduto = count($valorProduto); //conta o tamanho da array para inserir na tabela itenOrcamento (Serviços)

                //monta a repetição para o insert
                for ($i = 1; $i <= $tamanhoProduto; $i++) {

                    //cria a array item que será passada para função
                    $itensProduto = array('produto' => $prod[$i], 'qtdProduto' => $qtdProduto[$i], 'uMedida' => $uniMedida[$i], 'valorProduto' => $valorProduto[$i]);

                    //substitui as virgulas por pontos
                    $trataItensProduto = str_replace(',', '.', $itensProduto);

                    //insere dados na tabela itensorcamento
                    $insereProduto = $objOrcamento->queryInsertItemProduto($trataItensProduto, $ultimoId);
                }
            }

            if (($insere == "ok") || ($insereProduto == "ok")) {
                $busca = $objFuncao->base64($ultimoId, 1);
                header("Location: imprimeOrcamento.php?pg=&busca=$busca");
                exit();
            }
        }
    }

    if (isset($_POST['btnCadastrar']) && $_POST['btnCadastrar'] == 'Alterar') {

        $dados = $_POST;

        //verifica se os campos idColaborador, idUsuario e idCliente estãao vazios, caso esteja atribui o valor de $dados ao campo
        if ($dados['idColaborador'] == "") {
            $dados['idColaborador'] = $dados['idcolaborador'];
        }

        if ($dados['idUsuario'] == "") {
            $dados['idUsuario'] = $dados['idusuario'];
        }

        if ($dados['idCliente'] == "") {
            $dados['idCliente'] = $dados['idcliente'];
        }


        if (empty($dados['servico'])) {

            $valorT = 0; //echo 'vazio';

        } else {

            //monta loop para inserir serviços onde $id é o ID do item e $item é o VALORR do item
            foreach ($dados['servico'] as $id => $item) {

                //aqui vai a funcao update serviço
                $upServico = $objOrcamento->queryUpdateItemServico($id, $item);
            }
            //seta a quantidade em "0"
            $qtdS = 0;

            //monta loop para inserir qtd
            foreach ($dados['qtd'] as $id => $qtd) {
                //aqui vai a funcao update item
                $upQTD = $objOrcamento->queryUpdateItemQtd($id, $qtd);

                //soma a quantidade para obter valor total
                $qtdS = $qtdS + $qtd;
            }

            //seta o valor total em "0"
            $valorT = 0;

            //monta loop para inserir valor
            foreach ($dados['valor'] as $id => $valor) {

                //aqui vai a funcao update valor
                $upValor = $objOrcamento->queryUpdateItemValor($id, $valor);

                //soma os valores para obter valor total
                $valorT = $valorT + $valor;
            }
        }

        if (empty($dados['produto'])) {

            $valorP = 0; //echo 'vazio';

        } else {

            //monta loop para inserir produtos
            foreach ($dados['produto'] as $id => $item) {

                //aqui vai a funcao update produto
                $upProduto = $objOrcamento->queryUpdateItemProduto($id, $item);
            }

            //seta a quantidade em "0"
            $qtdP = 0;

            //monta loop para inserir qtdProduto
            foreach ($dados['qtdProduto'] as $id => $qtdProduto) {
                //aqui vai a funcao update item
                $upQTD = $objOrcamento->queryUpdateItemQtdProduto($id, $qtdProduto);

                //soma a quantidade para obter valor total
                $qtdP = $qtdP + $qtdProduto;
            }

            //monta loop para alterar uMedida
            foreach ($dados['uMedida'] as $id => $uMedida) {
                //aqui vai a funcao update item
                $upUM = $objOrcamento->queryUpdateItemUMedida($id, $uMedida);
            }

            //seta o valor total em "0"
            $valorP = 0;

            //monta loop para inserir valor produto
            foreach ($dados['valorItemProduto'] as $id => $valorProduto) {

                //aqui vai a funcao update valor
                $upValorProduto = $objOrcamento->queryUpdateItemValorProduto($id, $valorProduto);

                //soma os valores para obter valor total
                $valorP = $valorP + $valorProduto;
            }
        }

        //altera dados na tabela orçamento    

        $valorTotalOrcamento = ($valorT * $qtdS) + ($valorP * $qtdP);

        $upOrcamento = $objOrcamento->queryUpdateOrcamento($dados, $valorTotalOrcamento);
    }

    if (isset($_GET['acao']) and $_GET['acao'] == 'excluir') {

        if ($objOrcamento->queryDeleteOrcamento($_GET['user']) == 'ok') {

            if ($objOrcamento->queryDeleteItemOrcamento($_GET['user']) == 'ok') {

                $_SESSION['msg'] = "Cliente Excluido com sucesso.";
            } else {

                $_SESSION['msg'] = "Não foi possivel excluir o orçamento.";
            }

            if ($objOrcamento->queryDeleteItemProduto($_GET['user']) == 'ok') {

                $_SESSION['msg'] = "Orçamento Excluido com sucesso.";
            } else {

                $_SESSION['msg'] = "Não foi possivel excluir o orçamento.";
            }
        } else {

            $_SESSION['msg'] = "Não foi possivel excluir os itens do orçamento.";
        }
    }

    if (isset($_GET['acao']) and $_GET['acao'] == 'deletaItem') {

        if ($objOrcamento->queryDeleteItemOrcamento($_GET['idItem']) == 'ok') {

            $_SESSION['msg'] = "Item Excluido com sucesso.";
        } else {

            $_SESSION['msg'] = "Não foi possivel excluir o item.";
        }
    }

    if (isset($_GET['acao']) and $_GET['acao'] == "limpar") {

        unset($_SESSION['msg']);
    }
?>
<!DOCTYPE html>
    <html>
        <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="offcanvas.css" rel="stylesheet">
        <title>Orçamento - MVArquitetura</title>
    </head>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

    <?php include 'pesquisa.php'; ?>
    <body>
        <?php include 'menu.php'; ?>
        <main role="main" class="container">
            <div class="d-flex align-items-center p-3 my-3 text-black-50 bg-purple rounded shadow-sm">
                <?php
                    echo 'Olá ' . $_SESSION['nome'] . ', seja bem vindo!<br/>';
                ?>
            </div>
            <div class="my-3 p-3 bg-white rounded shadow-sm">

                <br />
                <h6 class="border-bottom border-gray pb-2 mb-0"><a href="?pg=0&acao=novo">Novo Orçamento</a></h6>

                <div class="container">

                    <div class="row justify-content-md-center">

                        <div class="media text-muted pt-3">
                            <?php
                            if (isset($_POST['btnBusca']) && $_POST['btnBusca'] == "Buscar") {

                                $opcao = $_POST;

                                switch ($opcao) {

                                    case (!empty($_POST['nOrcamento'])):

                                        $busca = $opcao['idOrcamento'];

                                        $id = $busca;

                                        foreach ($objOS->querySelecionaImpressao($id) as $res) {
                                        }

                                        break;

                                    case (!empty($_POST['idCliente'])):

                                        $busca = $opcao['idCliente'];

                                        $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idCliente = ' . $busca;

                                        $pg = $_GET['pg'];

                                        break;

                                    case (!empty($_POST['idUsuario'])):

                                        echo 'Busca Usuario';

                                        $busca = $opcao['idUsuario'];

                                        $filtro = 'WHERE ordemservico.status > 0 AND ordemservico.idResponsavel = ' . $busca;

                                        $pg = $_GET['pg'];

                                        break;
                                }
                            }

                            if (isset($_GET['acao']) and $_GET['acao'] == 'novo') {

                                echo '
    <div class="media text-muted pt-3"> 
    
        <div class="col-md-auto">

    <form method="POST" action="" autocomplete="off" class="form-signin" >

        <div>

            <table>

                <tr>

                    <td>ORÇAMENTO DE SERVIÇO</td>

                </tr>

                <tr>
               
                    <td colspan="3"> <div class="alert alert-primary" role="alert">
                    Caso o orçamento possua mais de 1 serviço, <br/>clique em "incluir Serviço" antes de preencher os campos.</div>
                    </td>
                
                </tr>

                <tr>';

                                $ultimoOrcamento = $objOrcamento->querySelecionaOrcamento();

                                foreach ($ultimoOrcamento as $orcamento) {

                                    $nOrcamento = $orcamento->numero;
                                }

                                $e = $nOrcamento + 1;

                                echo '
                    <tr>
                        <td colspan="3">Nº Orçamento: <input type="text" readonly  name="nOrcamento" value="' . $orcamento->idOrcamento . '" class="form-control" />
                        </td>
                    </tr>

                <tr>
                    <td colspan="3" >Data: <input type="date" name="dataOrcamento" required="required" class="form-control"/></td>
                </tr>
                
                    <!--usuario-->
                    <tr>
                        <td colspan="3"><label>Responsável:</label>
    
                            <input type="text" name="responsavel" id="search-usuario" placeholder="Responsável pelo Orçamento" required="required" size="30" autocomplete="off" class="form-control"/>
                            <input type="hidden" name="idUsuario" id="search-idusuario" />
                            <div id="suggesstion-usuario"></div>     
    
                        </td>
                    </tr>
                                               ';
                                if (isset($_SESSION['erroResponsavel'])) {

                                    echo '<tr><td></td><td colspan="2"><div class="alert alert-warning" role="alert">' . $_SESSION['erroResponsavel'] . '</div><!--Fim div "alert alert-warning"--></td></tr> ';
                                }
                                echo '
                    
                                    <!--Solicitante-->
                                    <tr>               
                                        <td colspan="3"><label>Solicitante:</label>
                    
                                            <input type="text" name="colaborador" id="search-colaborador" placeholder=" Nome do solicitante" required="required" size="30" autocomplete="off" class="form-control"/>
                                            <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                                            <div id="suggesstion-colaborador"></div>                   
                    
                                        </td>
                                    </tr>
                                    ';

                                if (isset($_SESSION['erroSolicitante'])) {

                                    echo '<tr><td></td><td colspan="2"><div class="alert alert-warning" role="alert">' . $_SESSION['erroSolicitante'] . '</div><!--Fim div "alert alert-warning"--></td></tr> ';
                                }
                                echo '
                
                                <!--Cliente-->
                                <tr>
                                    <td colspan="3"><label>Cliente:</label> 
                
                                        <input type="text" name="cliente" id="search-cliente" placeholder=" Nome do cliente" required="required" size="30" autocomplete="off" class="form-control"/>
                                        <input type="hidden" name="idCliente" id="search-idcliente" />
                                        <div id="suggesstion-cliente"></div>
                
                                    </td>
                
                                </tr>
                                ';

                                if (isset($_SESSION['erroCliente'])) {

                                    echo '<tr><td></td><td colspan="2"><div class="alert alert-warning" role="alert">' . $_SESSION['erroCliente'] . '</div><!--Fim div "alert alert-warning"--></td></tr> ';
                                }
                                echo '

            <tr>
                <td><br/></td>
            </tr>    
        
                <tr>
                    <td>Item Serviço</td>
                </tr>    
            
            <tr>';

                                //inclui 3 ou mais serviços ao form
                                if (isset($_GET['form']) && $_GET['form'] == 'servico') {

                                    $i = $_GET['inclui'] + 1;

                                    $a = $i;

                                    for ($i = 0; $i < $_GET['inclui'] + 1; $i++) {

                                        $j = $i + 1;

                                        echo '<td><label>Serviço:</label> <select name="servico[' . $j . ']"  class="form-control">

                                     <option value="0">Selecione...</option>';

                                        foreach ($objServico->querySelect() as $res) {

                                            echo

                                                '<option value="' . $res->idServico . '">' . $res->sigla . '</option>';
                                        }

                                        echo '

                                 </select></td>          

                                <td><label>Qtd: </label><input type="text" name="qtd[' . $j . ']" size="1" class="form-control"/></td>

                                <td><label>Valor Item: </label><input type="text" name="valorItem[' . $j . ']" placeholder="R$" size="3" class="form-control"/></td>

                            </tr>

                        <tr>';
                                    }

                                    $produto    = $_GET['formProduto'];
                                    $qtdProduto = $_GET['incluiProduto'];

                                    if (($produto == "") && $qtdProduto == "") {

                                        echo '<td colspan="2"><a href="?pg=&acao=novo&form=servico&inclui=' . $a . '">Incluir Serviço</a></td>';
                                    } elseif (($produto == "produto") && $qtdProduto >= "1") {

                                        echo '<td colspan="2"><a href="?pg=&acao=novo&form=servico&inclui=' . $a . '&formProduto=produto&incluiProduto=' . $qtdProduto . '">Incluir Serviço</a></td>';
                                    }

                                    echo '      
                     
                     </tr>

                    ';
                                }

                                //se o form tiver apenas 1 serviço, inclui + 1 serviço
                                if (empty($_GET['inclui'])) {
                                    $i = 1;
                                    echo ' <tr>
                    <td><label>Serviço: </label><select name="servico[' . $i . ']"  class="form-control">

                    <option value="0">Selecione...</option>';

                                    foreach ($objServico->querySelect() as $res) {

                                        echo

                                            '<option value="' . $res->idServico . '">' . $res->sigla . '</option>';
                                    }

                                    echo '</select></td> 
                     
                        <td><label>Qtd: </label><input type="text" name="qtd[' . $i . ']" size="1" class="form-control"/></td>

                        <td><label>Valor Item: </label><input type="text" name="valorItem[' . $i . ']"  placeholder="R$" size="3" class="form-control"/></td>
                        
                    </tr>

                    <tr>';

                                    $produto    = $_GET['formProduto'];
                                    $qtdProduto = $_GET['incluiProduto'];

                                    if (($produto == "") && $qtdProduto == "") {

                                        echo '<td><a href="?pg=&acao=novo&form=servico&inclui=' . $i . '">Incluir Serviço</a></td>';
                                    } elseif (($produto == "produto") && $qtdProduto >= "1") {

                                        echo '<td><a href="?pg=&acao=novo&form=servico&inclui=' . $i . '&formProduto=produto&incluiProduto=' . $qtdProduto . '">Incluir Serviço</a></td>';
                                    }

                                    echo '
                    </tr>';
                                }

                                echo '
        <tr>
            <td><hr/></td>
        </tr>
        
            <tr>
                <td>Item Produto</td>
            </tr>    
    ';

                                //inclui 3 ou mais produtos ao form
                                if (isset($_GET['formProduto']) && $_GET['formProduto'] == 'produto') {

                                    //   $servico = $_GET['form'];
                                    //  $qtdServico = $_GET['inclui'];   

                                    $i = $_GET['incluiProduto'] + 1;

                                    $a = $i;

                                    for ($i = 0; $i < $_GET['incluiProduto'] + 1; $i++) {

                                        $j = $i + 1;

                                        echo '<td><label>Produto:</label> <select name="produto[' . $j . ']"  class="form-control">

                                     <option value="0">Selecione...</option>';

                                        foreach ($objServico->querySelectProduto() as $res) {

                                            echo

                                                '<option value="' . $res->idProduto . '">' . $res->codigo . ' - ' . $res->nomeProduto . '</option>';
                                        }

                                        echo '

                                 </select></td>          

                                <td><label>Qtd: </label><input type="text" name="qtdProduto[' . $j . ']" size="1" class="form-control"/></td>
                                
                        <td><label>Un.Med: </label><select name="medida[' . $j . ']"  class="form-control">
                        
                        <option value="6"> Un </option>';

                                        //Preenche o select unidade de medida
                                        foreach ($objServico->querySelecionaUnMedida() as $resMedida) {

                                            echo '<option value =' . $resMedida->idUnidadeMedida . '>' . $resMedida->sigla . '</option>';
                                        }

                                        echo '</select></td> 
                                
                                <td><label>Valor Item: </label><input type="text" name="valorItemProduto[' . $j . ']" placeholder="R$" size="3" class="form-control"/></td>

                             </tr>

                            <tr>';
                                    }

                                    echo '<tr>';

                                    if (($servico == "") & $qtdServico == "") {

                                        echo '<td colspan="2"><a href="?pg=&acao=novo&formProduto=produto&incluiProduto=' . $a . '">Incluir Produto</a></td>';
                                    } elseif (($servico == "servico") & $qtdServico >= "1") {

                                        echo '<td colspan="2"><a href="?pg=&acao=novo&form=servico&inclui=' . $qtdServico . '&formProduto=produto&incluiProduto=' . $a . '">Incluir Produto</a></td>';
                                    }

                                    echo '</tr>';
                                }

                                //se o form tiver apenas 1 produto
                                if (empty($_GET['incluiProduto'])) {
                                    $i = 1;
                                    echo '
                    <td><label>Produto: </label><select name="produto[' . $i . ']"  class="form-control">

                    <option value="0">Selecione...</option>';

                                    foreach ($objServico->querySelectProduto() as $res) {

                                        echo '<option value="' . $res->idProduto . '">' . $res->codigo . ' - ' . $res->nomeProduto . '</option>';
                                    }

                                    echo '</select></td> 
                     
                        <td><label>Qtd: </label><input type="text" name="qtdProduto[' . $i . ']" size="1" class="form-control"/></td>
                        
                         <td><label>Un.Med: </label><select name="medida[' . $i . ']"  class="form-control">
                         
                         <option value="6"> Un </option>';

                                    //Preenche o select unidade de medida
                                    foreach ($objServico->querySelecionaUnMedida() as $resMedida) {
                                        echo '<option value =' . $resMedida->idUnidadeMedida . '>' . $resMedida->sigla . '</option>';
                                    }

                                    echo '</select></td> 
                     
                        <td><label>Valor Item: </label><input type="text" name="valorItemProduto[' . $i . ']"  placeholder="R$" size="3" class="form-control"/></td>

                    </tr>

                    <tr>';

                                    $servico = $_GET['form'];
                                    $qtdServico = $_GET['inclui'];

                                    if (($servico == "") & $qtdServico == "") {

                                        echo '<td><a href="?pg=&acao=novo&formProduto=produto&incluiProduto=' . $i . '">Incluir Produto</a></td>';
                                    } elseif (($servico == "servico") & $qtdServico >= "1") {

                                        echo '<td><a href="?pg=&acao=novo&form=servico&inclui=' . $qtdServico . '&formProduto=produto&incluiProduto=' . $i . '">Incluir Produto</a></td>';
                                    }

                                    echo '
                    </tr>';
                                }

                                echo '

                <tr>

                    <td colspan="3"><label>Valido até: </label><input type="date" name="validade" required="required" class="form-control"/></td>

                </tr>
                
                <tr>

                <td colspan="3">

                <label>Forma de pagamento:</label>


                        <textarea class="md-textarea form-control" name="fPagamento" id="fPagamento" rows="3" class="form-control"></textarea>

                    </td>

                </tr>

                <tr>

                <td colspan="3">

                <label>Observações:</label>

                        <textarea class="md-textarea form-control" name="obsOrcamento" id="obsOrcamento" rows="3" ></textarea>

                    </td>

                </tr>

                 <tr> 
                
                     <td colspan="3">

                         <input type="hidden" name="status" value="1"/><!--status 1 = pendente -->

                         <br/><input type="submit" name="btnCadastrar" value="Gerar Orçamento" class="btn  btn-primary btn-block"/></td>

                </tr>

            </table>              

        <a href="?pg=0&acao=limpar">Voltar</a>

    </form>
    
 </div><!--fim div "col-md-auto"-->
 ';
                            }

                            //****************************ALTERA ORÇAMENTO**************************************************************************

                            if (isset($_GET['acao']) and $_GET['acao'] == 'alterar') {

                                $idOrc = $_GET['user'];

                                foreach ($objOrcamento->queryAtualizaOrcamento($_GET['user']) as $res) {
                                }

                                echo '        

           <form method="POST" action="" class="form-signin" >

            <table  cellpadding="6">
                <tr>
                    <th colspan="2">Alterar Orçamento</th>
                </tr>
            </table>
                    <label>Número:</label> <input type="text" name="nOrcamento" value="' . $res->numero . '" size="4" class="form-control"/>
                    
                        <label>Data: </label><input type="text" name="dataOrcamento" value="' . $objFuncao->formataData($res->data) . '" class="form-control"/>
                    
                        <!--usuario-->
                            <label>Responsável: </label>
        
                                <input type="text" name="responsavel" id="search-usuario" value="' . $res->nomeUsuario . '" size="30" class="form-control"/>
        
                                <input type="hidden" name="idUsuario" id="search-idusuario" />
                                
                                <input type="hidden" name="idusuario" value="' . $res->idUsuario . '" />
        
                                <div id="suggesstion-usuario"></div>     

                                    <!--Solicitante-->
                                    <label>Solicitante: </label>
                    
                                            <input type="text" name="colaborador" id="search-colaborador" value="' . $res->nomeColaborador . '" size="30" class="form-control"/>
                    
                                            <input type="hidden" name="idColaborador" id="search-idcolaborador" />
                                            
                                            <input type="hidden" name="idcolaborador" value="' . $res->idColaborador . '" />
                    
                                            <div id="suggesstion-colaborador"></div>                   
                                    
                                <!--Cliente-->
                                <label>Cliente: </label>
                                
                                        <input type="text" name="cliente" id="search-cliente" value="' . $res->nomeCliente . '" size="30" class="form-control"/>
                                    
                                        <input type="hidden" name="idCliente" id="search-idcliente" />
                                                            
                                        <input type="hidden" name="idcliente" value="' . $res->idCliente . '" />
                                    
                                        <div id="suggesstion-cliente"></div>
                                
                                    <label>Validade: </label><input type="text" name="dataValidade" value="' . $objFuncao->formataData($res->validade) . '" required="required" class="form-control"/>
                            
        ';

                                $i = 1;
                                $j = 1;

                                foreach ($objOrcamento->querySelecionaItemServico($_GET['user']) as $item) {

                                    echo '  
            <br/>
                <label><strong>Item Serviço</label> ' . $j++ . '</strong>  

                <div class="container">
                
                    <div class="row">
                    
                        <div class="col-4">

                            <label>Serviço: </label>
                            <select name="servico[' . $item->idItem . ']" class="form-control" required="required">

                                <option value="' . $item->idServicoDisponibilizado . '">' . $item->sigla . '</option>
                ';

                                    foreach ($objServico->querySelect() as $resServico) {

                                        echo '<option value="' . $resServico->idServico . '">' . $resServico->sigla . '</option>';
                                    }

                                    echo '</select>
                        </div>
                        
                            <div class="col-1.5">
    
                                <label>Qtd</label><input type="text" name="qtd[' . $item->idItem . ']" value="' . $item->qtd . '" size="5" class="form-control" />
                            
                            </div>
                        
                        <div class="col-3">
    
                            <label>Valor:</label> <input type="text" name="valor[' . $item->idItem . ']" value="' . str_replace('.', ',', $item->valorItem) . '" size="3" class="form-control"/>
                
                        </div>
            </div>
        </div>
                        ';
                                }

                                $i = 1;
                                $j = 1;

                                foreach ($objOrcamento->querySelecionaItemProduto($_GET['user']) as $itemProduto) {

                                    echo '  
                </br>
                <label><strong>Item Produto ' . $j++ . '</strong></label><br/>
                
                <div class="container">
                    <div class="row">
                    
                        <div class="col-4">
                        
                        <label>Produto: </label><select name="produto[' . $itemProduto->idItemOrcProduto . ']" class="form-control">
                        
                            <option value="' . $itemProduto->idProdutoDisponibilizado . '">' . $itemProduto->nomeProduto . '</option>
                    ';
                                    foreach ($objServico->querySelectProduto() as $resProduto) {

                                        echo '<option value="' . $resProduto->idProduto . '">' . $resProduto->codigo . ' - ' . $resProduto->nomeProduto . '</option>';
                                    }

                                    echo '</select>
                        </div>
                        
                            <div class="col-1.5">
                            
                                <label> Qtd:</label><br/>
    
                                    <input type="text" name="qtdProduto[' . $itemProduto->idItemOrcProduto . ']" value="' . $itemProduto->qtdProduto . '" size="5" class="form-control" /><br/>
                            
                            </div>
                        
                        <div class="col-1.5">
                        
                            <label>U.M.: </label><select name="uMedida[' . $itemProduto->idItemOrcProduto . ']" class="form-control">
                        
                                <option value="' . $itemProduto->uM . '">' . $itemProduto->sigla . '</option>
                    ';
                                    foreach ($objServico->querySelecionaUnMedida() as $resUM) {

                                        echo '<option value =' . $resUM->idUnidadeMedida . '>' . $resUM->sigla . '</option>';
                                    }

                                    echo '</select>
                            
                        </div>
                        
                            <div class="col-3">
                            
                                <label>Valor Item:</label>
                
                                    <input type="text" name="valorItemProduto[' . $itemProduto->idItemOrcProduto . ']" value="' . str_replace('.', ',', $itemProduto->valorItemProduto) . '" size="3" class="form-control"/>
                      
                            </div>
                        
                </div>
            </div>    
                        
        <hr>
                                ';
                                }
                                echo '
                    
                <label>Forma de Pagamento:</label>
                
                    <textarea type="text" name="fPagamento" class="form-control"  rows="4"  >' . $res->formaPagamento . '</textarea>

                        <label>Obs.:</label><textarea type="text" name="obs" class="form-control"  rows="4"  >' . $res->obsOrcamento . '</textarea>
        
                                <input type="hidden" name="status" value="1" >
                                <input type="hidden" name="idOrcamento" value="' . $idOrc . '" >
        
                                <br/><input type="submit" name="btnCadastrar" value="Alterar" class="btn btn-lg btn-primary btn-block" />

                    ';

                                if (isset($_SESSION['msg'])) {

                                    echo '<div class="alert alert-warning" role="alert">' . $_SESSION['msg'] . '</div>';

                                    unset($_SESSION['msg']);
                                }

                                echo '
            
                <a href="?pg=0&acao=limpar">Voltar</a>

    </form>';
                            }
                            ?>

                        </div>

                    </div>
                    <!--Fim Espaço central-->

                    <div class="col col-lg-2">
                        <!--Espaço do lado direito-->
                    </div>

                </div>

            </div>

            <div class="my-3 p-3 bg-white rounded shadow-sm">

                <h6 class="border-bottom border-gray pb-2 mb-0">Últimos Orçamentos</h6>

                <div class="media text-muted pt-3">

                    <table class="table">

                        <thead>

                            <tr>

                                <th scope="col">#</th>

                                <th scope="col">Cliente</th>

                                <th scope="col">Solicitante</th>

                                <th scope="col">Responsavel</th>

                                <th scope="col">Data</th>

                                <th scope="col">Valor</th>

                                <th scope="col">Validade</th>

                                <th scope="col">Status</th>

                                <th scope="col">Ver</th>

                                <th scope="col">Editar</th>

                                <th scope="col">Excluir</th>

                            </tr>

                        </thead>

                        <?php
                        $pg = $_GET['pg'];
                        $empresa = $_SESSION['empresa'];

                        if (!isset($pg)) {

                            $pg = "";
                        }

                        $numreg = 10;

                        $inicial = $pg * $numreg;

                        $proximo = $pg + 1;

                        $anterior = $pg - 1;

                        //conta a quantidade total de registros na tabela
                        $quantreg = $objOrcamento->querySelectTotalOrcamento();

                        $i = $quantreg;

                        foreach ($objOrcamento->querySeleciona($inicial, $numreg, $empresa) as $res) {

                            $busca = $objFuncao->base64($res->idOrcamento, 1);

                            echo ' <tr>
                    <td>' . $res->numero . '</td>
                    <td>' . $res->nomeCliente . '</td>
                    <td>' . $res->nomeColaborador . '</td>
                    <td>' . $res->nomeUsuario . '</td>
                    <td>' . $objFuncao->formataData($res->data) . '</td>
                    <td>R$ ' . $objFuncao->SubstituiPonto($res->total) . '</td>
                    <td>' . $objFuncao->formataData($res->validade) . '</td>
                    <td><a href="atualizaSituacaoOrcamento.php?pg=&acao=situacao&orcamento=' . $res->idOrcamento . '">' . $res->situacao . '</a></td>
                    <td><a href="imprimeOrcamento.php?pg=' . $pg . '&busca=' . $busca . '" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
                    <td><a href="?pg=' . $pg . '&acao=alterar&user=' . $res->idOrcamento . '" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
                    <td><a href="?pg=' . $pg . '&acao=excluir&user=' . $res->idOrcamento . '" onclick="return confirm(\'Tem certeza que deseja excluir esse registro?\');" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                </tr>';
                        }

                        $quant_pg = ceil($quantreg / $numreg);

                        $quant_pg++;        // Verifica se esta na primeira página, se nao estiver ele libera o link para anterior	

                        if ($pg > 0) {

                            echo '<tr><td colspan=2 align=right><a href=?pg=' . $anterior . ' ><b>« anterior </b></a>';
                        } else {

                            echo "<tr><td colspan=2 align=right> ";
                        }        // Faz aparecer os numeros das página entre o ANTERIOR e PROXIMO	

                        for ($i_pg = 1; $i_pg < $quant_pg; $i_pg++) {

                            // Verifica se a página que o navegante esta e retira o link do número para identificar visualmente		

                            if ($pg == ($i_pg - 1)) {

                                echo "<td colspan=5 align=left align=center>  [$i_pg] ";
                            } else {

                                $i_pg2 = $i_pg - 1;

                                echo ' <a href="?pg=' . $i_pg2 . '" ><b> ' . $i_pg . '  </b></a>';
                            }
                        }

                        // Verifica se esta na ultima página, se nao estiver ele libera o link para próxima	

                        if (($pg + 2) < $quant_pg) {

                            echo ' <a href=?pg=' . $proximo . ' ><b> próximo » </b></a></td>';
                        } else {

                            echo "<td colspan=8 align=center></td></tr>";
                        }
                        ?>

                    </table>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>

    </body>

    </html>

<?php
} else {

    $_SESSION['msg'] = "Área Restrita, é necessário estar logado para ter acesso";

    header("Location: login.php");
}
