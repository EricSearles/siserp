<?php
session_start();
if(!empty($_SESSION['id'])){
//unset($_SESSION['msg']);
        require_once 'classes/Funcoes.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Usuario.php';
        /* Carrega a classe DOMPdf */
        require_once("dompdf/dompdf_config.inc.php");

        $objOrcamento = new Orcamento();
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        $objUsuario = new Usuario();

/* Cria a instância */
$dompdf = new DOMPDF();

/* Carrega seu HTML */


$html = '<table width="70%">
    

<tr>';

if($_SESSION['empresa']=='1'){
    $html .='<td><img src ="images/mveiga-arquitetura-logo.jpg" width="150" align="bottom" /></td>';
}else{
    $html .='<td><img src ="images/logo-tcassessoria.fw.png" width="200" align="bottom" /></td>';
}
$html .='
<td  align="left">PROPOSTA COMERCIAL</td>
</tr>
</table><br/><br/>';
$busca = $_GET['busca'];
$id = $objFuncao->base64($busca, 2);
        foreach($objOrcamento->querySelecionaImpressao($id) as $dados){
       
//Cabeçalho Orçamento
 $html .='<table cellpadding="6" align="center"></tr>
                <tr>
                    <td colspan="6">Nº Orçamento: '.$dados->numero.'</td>
                    <td colspan="4" align="right">Data: '.$objFuncao->formataData($dados->data).'</td>
                </tr>
                

<tr>
    <td colspan="10"><!--Elaborado por:  $dados->nomeUsuario--></td>
</tr>
';    
                  foreach($objOrcamento->querySelecionaRespCliente($dados->idCliente) as $responsavel) {
                      

                 $html.='<tr>

                    <td colspan="9">Cliente: '.$dados->nomeCliente.'</td>
                    </tr>
                    <tr>
                  <td colspan="10">A/C: '.$dados->nomeColaborador.'</td>
                  </tr>
                  ';
                }
                
                
               $html.=' <tr>

                <td colspan="10"></td>
                </tr>
                <tr>

                    <td colspan="10"><!--Serviço(s) a realizar:--></td>
                </tr>
               <!-- <tr align="center">

                   <td colspan="10"><hr></td>
                </tr>-->
                
                
                ';
                


$validaServiço = $objOrcamento->querySelecionaItemServico($id);
if(empty($validaServiço)){
    
}else{ 
    
                    $html.= '<tr align="center">
                    <td>Serviços</td>
                    </tr>
                    <tr align="center">
                    <td>Item</td>
                    <td colspan="">Serviço</td>
                    <td width="50%" colspan="4" align="center">Descrição</td>
                    <td colspan="">Qtd</td>
                    <td colspan="3" align="center">Valor Item</td>
                </tr>';
                


 }
 
 
$i = 1;
foreach($objOrcamento->querySelecionaItemServico($id) as $item){
    $descricao = nl2br($item->descricao);
                $html.='<tr>
                    <td align="center">'.$i++.'</td>
                    <td colspan="" align="center">'.$item->sigla.'</td>	
                    <td colspan="4" align="left">'.$descricao.'</td>
                    <td colspan="">'.$item->qtd.'</td>
                    <td colspan="3" align="center">R$ '.str_replace('.', ',',$item->valorItem).'</td>
                </tr>

                ';
    }
}

$validaProduto = $objOrcamento->querySelecionaItemProduto($id);
if(empty($validaProduto)){
    
}else{

               $html.=' 
               <tr>
               <td>Produtos</td>
               </tr>
               <tr align="center">
                    <td>Item</td>
                    <td colspan="">Produto</td>
                    <td width="50%" colspan="4" align="center">Descrição</td>
                    <td colspan="">Qtd</td>
                    <td colspan="3" align="center">Valor Item</td>
                </tr>';
 
$i = 1;
foreach($objOrcamento->querySelecionaItemProduto($id) as $item){
    $descricao = nl2br($item->descricao);
                $html.='<tr align="center">
                    <td align="center">'.$i++.'</td>
                    <td colspan="" align="center">'.$item->nomeProduto.'</td>	
                    <td colspan="4" align="left">'.$descricao.'</td>
                    <td colspan="">'.$item->qtdProduto.' '.$item->sigla.'</td>
                    <td colspan="3" align="center">R$ '.str_replace('.', ',',$item->valorItemProduto).'</td>
                </tr>
                ';
}
}
$html.='                <tr>
                <td colspan="10"><hr></td>
                </tr>';

                $html.='<tr>
                <td colspan="2">Observações: </td><td colspan="8" align="left">'. $dados->obsOrcamento.'</td>
                </tr>
                <tr>
                <td colspan="10"><hr></td>
                </tr>
                <tr>
                <td colspan="2">Pagamento: </td><td colspan="8" align="left">'. nl2br($dados->formaPagamento).'</td>
                </tr>
                <tr>
                <td colspan="10"><hr></td>
                </tr>
                   <tr>

                    <td colspan="6">Validade da Proposta: '.$objFuncao->formataData($dados->validade).'</td>
                    <td colspan="4" align="right">Valor Total: R$ '.str_replace('.', ',',$dados->total).'</td>
                </tr> 
                <tr>

                   <td colspan="10"><hr></td>
                </tr>';

                foreach($objOrcamento->querySelecionaAssinaturaEmail($dados->idUsuario) as $res){

                }

                $html.='<tr>

                    <td colspan="10">'. $res->nomeUsuario.'</td>
                </tr>';
                if($_SESSION['empresa']=='1'){
                    
                $html.='<tr>
                
                    <td colspan="10">Mareza Veiga Arquitetura</td>
                </tr>
                <tr>
               
                    <td colspan="10">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                </tr>
                <tr>
                
                    <td colspan="10">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
               
                    <td colspan="10">E-mail: '.$res->email.'</td>
                </tr>

                <tr>
                
                    <td colspan="10"><hr></td>
                </tr>
                <tr align="center">
              
                    <td colspan="10" >www.mveigaarquitetura.com.br</td>
                </tr>
                ';
                    }else{
                 $html.='<tr>
                    <td colspan="9">TC Assessoria</td>
                </tr>
                <tr>
                    <td colspan="9">Tels: (11) 3996-3356 </td>  
                </tr>
                <tr>
                    <td colspan="9">Cel.: ('.$res->dddcel.') '.$objFuncao->mask($res->cel, '#####-####').'</td>
                </tr>
                <tr>
                    <td colspan="9">E-mail: comercial@tcassessoria.com.br</td>
                </tr>
                <tr>
                    <td colspan="9" >www.tcassessoria.com.br</td>
                </tr>';
                    }
$html .='</table>';

$dompdf->load_html($html);
$dompdf->set_paper('A4', 'portraid');
$dompdf->render();
$dompdf->stream('orcamento.pdf', array('Attachment'=> false));
}
?>