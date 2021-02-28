<?php 
session_start();
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Pagamento.php';
        require_once("dompdf/dompdf_config.inc.php");
        require_once 'classes/Colaborador.php';
        
        $objColaborador = new Colaborador();
        $objOS = new OrdemServico();
        $objOrcamento = new Orcamento();
        $objPagamento = new Pagamento(); 
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
        /* Cria a instância */
$dompdf = new DOMPDF();

$idColaborador = $_POST['idColaborador'];
$colaborador = $_POST['colaborador'];
$dInicio = $_POST['inicio'];
$dFim = $_POST['fim'];
$inicioMesAnterior = $_POST['inicioAnterior'];
$fimMesAnterior = $_POST['fimAnterior'];
$recebidoMesAnterior = $_POST['recebidoAnterior'];
$recebido = $_POST['recebido'];
$areceber = $_POST['areceber'];



/* Carrega seu HTML */
$html = '<table width="70%">
';

if($_SESSION['empresa']=='1'){
    $html .='<tr><td><img src ="images/mveiga-arquitetura-logo.jpg" width="150" align="bottom" /></td>';
}else{
    $html .='<td><img src ="images/logo-tcassessoria.fw.png" width="200" align="bottom" /></td></tr>';
}
$html .='
<td colspan="8" align="left">RELATÓRIO</td>
</tr>
<tr>
<td colspan="8">Colaborador: <strong>'.$colaborador.'</strong></td>
</tr>
<tr>
<td colspan="8">Período do Relatorio: de <strong>'.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</strong></td>
</tr>
<tr>
<td colspan="8">Total a receber - R$ '.$areceber.'</td>
</tr>
<tr>
<td colspan="8">Total recebido - R$ '.$recebido.'</td>
</tr>
</table>
<hr/>
';

$mesAtual = $objColaborador->queryRelatorioPagamentoAReceber($idColaborador, $dInicio, $dFim);

$mesAnterior = $objColaborador->queryRelatorioPagamentoRecebido($idColaborador, $inicioMesAnterior, $fimMesAnterior);



$html .='
        <table class="table table-sm">
        <thead>
        <tr class="table-success" align="center">
        <td colspan="8" align="left"><br/>Pagamentos a receber '.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</td>
        </tr>
        <tr><td><br/></td></tr>
        <tr align="center">
          <th scope="col">OS</th>
          <th scope="col">Data OS</th>
          <th scope="col">Valor OS</th>
          <th scope="col">a receber</th>
          <th scope="col">Parcela</th>
          <th scope="col">Vencimento</th>
          <th scope="col">Cliente</th>
          <th scope="col">Responsavel OS</th>
        </tr>
        </thead>
       '; 
    foreach ($mesAtual as $relatorioAtual) {
        $html .='
          <tr align="center">
          <td scope="col">'.$relatorioAtual->idOrdemServico.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAtual->data).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAtual->total).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAtual->valor).'</td>
          <td scope="col">'.$relatorioAtual->parcela.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAtual->dataVencimento).'</td>
          <td scope="col">'.$relatorioAtual->nomeCliente.'</td>
          <td scope="col">'.$relatorioAtual->nomeUsuario.'</td>
        </tr>
         ';       
    }

     $html .='
     <tr><td colspan="8"><hr/></td></tr>
        <table class="table table-sm">
        <thead>
        <tr class="table-success" align="center">
        <td colspan="8" align="left"><br/>Pagamentos recebidos '.$objFuncao->formataData($dInicio).' a '.$objFuncao->formataData($dFim).'</td>
        </tr>
        <tr><td><br/></td></tr>
        <tr align="center">
          <th scope="col">Nº OS</th>
          <th scope="col">Data OS</th>
          <th scope="col">Valor OS</th>
          <th scope="col">Valor Pago</th>
          <th scope="col">Vencimento</th>
          <th scope="col">Cliente</th>
          <th scope="col">Responsavel OS</th>
        </tr>
        </thead>
       '; 
    foreach ($mesAnterior as $relatorioAnterior) {
        $html .='
          <tr align="center">
          <td scope="col">'.$relatorioAnterior->idOrdemServico.'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAnterior->data).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAnterior->total).'</td>
          <td scope="col">R$ '.$objFuncao->SubstituiPonto($relatorioAnterior->valor).'</td>
          <td scope="col">'.$objFuncao->formataData($relatorioAnterior->dataVencimento).'</td>    
          <td scope="col">'.$relatorioAnterior->nomeCliente.'</td>
          <td scope="col">'.$relatorioAnterior->nomeUsuario.'</td>
        </tr>
         '; 
    }
    
            

                foreach($objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario) as $res){

                }

                $html.='<tr>
                            <td colspan="8"><hr/></td>
                        </tr>
                
                <tr>

                    <td colspan="8"><br/>'. $res->nomeUsuario.'</td>
                </tr>';
                if($_SESSION['empresa']=='1'){
                    
                $html.='<tr>
                
                    <td colspan="8">Mareza Veiga Arquitetura</td>
                </tr>
                <tr>
               
                    <td colspan="8">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                </tr>
                <tr>
                
                    <td colspan="8">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
               
                    <td colspan="8">E-mail: '.$res->email.'</td>
                </tr>


                <tr align="center">
              
                    <td colspan="8"><a href="http://www.mveigaarquitetura.com.br">www.mveigaarquitetura.com.br</a></td>
                </tr>
                ';
                    }else{
                 $html.='<tr>
                    <td colspan="8">TC Assessoria</td>
                </tr>
                <tr>
                    <td colspan="8">Tels: (11) 3996-3356 </td>  
                </tr>
                <tr>
                    <td colspan="8">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
                    <td colspan="8">E-mail: comercial@tcassessoria.com.br</td>
                </tr>
                <tr>
                    <td colspan="8"><a href="http://www.tcassessoria.com.br">tcassessoria.com.br</a></td>
                </tr>';
                    }

                

$html .='</table>';

$dompdf->load_html($html);
$dompdf->set_paper('A4', 'portraid');
$dompdf->render();
$dompdf->stream('orcamento.pdf', array('Attachment'=> false));
}

?>