<?php 
session_start();
if(!empty($_SESSION['id'])){

        require_once 'classes/Funcoes.php';
        require_once 'classes/OrdemServico.php';
        require_once 'classes/Orcamento.php';
        require_once 'classes/Permissao.php';
        require_once 'classes/Pagamento.php';
        require_once("dompdf/dompdf_config.inc.php");
        
        $objOS = new OrdemServico();
        $objOrcamento = new Orcamento();
        $objPagamento = new Pagamento(); 
        $objFuncao = new Funcoes();
        $objPermissao = new Permissao();
        
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
<td  align="left">ORDEM DE SERVIÇO</td>
</tr>
</table><br/><br/>';
$busca = $_GET['busca'];
$id = $busca;  
        foreach($objOS->querySelecionaImpressao($id) as $res){
         if($res->data == "0000-00-00" OR $res->data == ""){
         $resData = " - ";
        }else{
            $resData = $objFuncao->formataData($res->data);
        }
        
         $html .='<table cellpadding="6"  class="table-sm"></tr>
                <tr>
                    <td colspan="10">Nº O.S.: '.$res->idOrdemServico.'</td>
                    <!--Seleciona tabela usuario-->
                    <td colspan="10" align="right">Data: '.$resData.'</td>
                </tr>
                <tr>
                    <td colspan="10" >Solicitante: '.$res->nomeColaborador.'</td>
<!--                    Seleciona tabela usuario-->
                    <td colspan="10" align="right">Responsável: '.$res->nomeUsuario.'</td>
                </tr>
                <tr>
                    <!--                    Seleciona tabela cliente-->
                    <td colspan="10">Cliente: '.$res->nomeCliente.'</td>                  
                    <td colspan="10" align="right">Vencimento: '.$objFuncao->formataData($res->validade).'</td>
                </tr>';
        
                $valorTotal = $res->total;
        }
                $pag = $objPagamento->querySelecionaPagamento($id);    
                
                 foreach ($pag as $parc) {
                     if($parc->parcela == 'Sinal'){          
                         $tipo = 'Parcelado';
                         $html.='<tr>
                                    <td colspan="10">Pagamento: '.$tipo.'<td>
                                    <td colspan="10" align="right">Valor Total: R$'.$objFuncao->SubstituiPonto($valorTotal).'</td>
                                 </tr>
                                 <tr>
                                 <td colspan="20" >Forma de Pagamento: '.$res->formaPagamento.'</td>
                                 </tr>';
                     }else{
                        //$html.='Não'.$id;
                     }
                   
                 }
                 foreach ($pag as $pagamento) {
                            //print_r($pagamento);
                            if($pagamento->parcela == "Única"){
                                $tipoPagamento = 'À vista';
                                $html.='                
                                    <tr>
                                        <td colspan="10">Pagamento: '.$tipoPagamento.'</td>
                                        <td colspan="10" align="right">Forma de Pagamento: '.$res->formaPagamento.'</td>
                                    </tr>
                                    <tr>
                                    <td colspan="7">Valor: R$ '.$objFuncao->SubstituiPonto($valorTotal).'</td>
                                        <td colspan="7">Data Pagamento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                            
                                        <td colspan="6" align="right">Situação: '.$pagamento->situacao.'</td>
                                    </tr>';
                                
                            }else{
                                $tipoPagamento = 'Parcelado';
                                $html.='<tr>
                                        <td colspan="4">Parcela: '.$pagamento->parcela.'</td>
                                            <td colspan="6">Vencimento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                        <td colspan="6">Valor: R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        
                                        <td colspan="4" align="right">Situação: <a href="atualizaPagamento.php?pg=&acao=pagamento&idOS='.$pagamento->idOrdemServico.'&idPag='.$pagamento->idPagamento.'&tipoPag='.$pagamento->parcela.'">'.$pagamento->situacao.'</a></td>
                                    </tr>';
                                }
                            }
                            
                 $html.='<tr>
                            <td colspan="20"><hr></td>
                         </tr>
                        <tr>
                            <td colspan="20">Serviço(s):</td>
                        </tr>
                        <tr align="center">
                           <td colspan="20"></td>
                        </tr>
                        <tr>
                            <td>Item</td>                    
                            <td colspan="2" align="center">RRT</td>
                            <td  align="center">NºProcesso</td>
                            <td  align="center">Agência</td>
                            <td colspan="2">Serviço</td>
                            <td align="center">Qtd</td>
                            <td colspan="5" align="center">Valor</td>
                            <td colspan="3" align="center">Previsão</td>
                            <td colspan="4" align="center">Status do serviço</td>
                        </tr>';
                $i = 1;
                foreach($objOS->querySelecionaItem($id) as $item){
                 if($item->entrega == "0000-00-00" OR $item->entrega == ""){
                        $dataEntrega = " - ";
                    }else{
                        $dataEntrega = $objFuncao->formataData($item->entrega);
                    }
         $html.='<tr align="center">
<!--                    Seleciona tabela serviço disponibilizado-->                    
                    <td align="center">'.$i++.'</td>
                    <td colspan="2">'.$item->nDocumento.'</td>
                    <td >'.$item->nProcesso.'</td>
                    
                    <td >'.$item->agencia.'</td>
                    
                    <td colspan="2" align="center">'.$item->sigla.'</td>	
                    ';
                                                      
                    $html.='<td align="center">'.$item->qtd.'</td>
                    <td colspan="5" align="center">R$'.$item->valorItem.'</td>
                    <td colspan="3" align="center">'.$dataEntrega.'</td>
                    <td colspan="4" align="center">'.$item->situacao.'</td>
                                             
                </tr>';

                    
                    
                 $html.='<tr>
                    <td colspan="20"><hr></td>
                    </tr>';
                }
                
                foreach($objOrcamento->querySelecionaAssinaturaEmail($res->idUsuario) as $assinatura){
                    
                    $html.='<tr>
                    <td colspan="20">'. $assinatura->nomeUsuario.'</td>
                            </tr>';
                    
                    
                }
                if($_SESSION['empresa']=='1'){
                    
                    $html.='<tr>
                                <td colspan="20">Mareza Veiga Arquitetura</td>
                            </tr>
                            <tr>
                                <td colspan="20">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                            </tr>
                    <tr>
                
                    <td colspan="20">Cel.: ('.$assinatura->dddcel.') '.$objFuncao->mask($assinatura->cel, '#####-####').'</td>
                </tr>
                                <tr>
               
                    <td colspan="20">E-mail: '.$assinatura->email.'</td>
                </tr>

                <tr>
                
                    <td colspan="20"><hr></td>
                </tr>
                <tr align="center">
              
                    <td colspan="20" >www.mveigaarquitetura.com.br</td>
                </tr>
                ';
                }else{
                 $html.='<tr>
                    <td colspan="9">TC Assessoria</td>
                </tr>
                <tr>
                    <td colspan="9">Tels: (11) 4509-4866 | (11) 4509-4865 </td> 
                </tr>
                <tr>
                    <td colspan="9">Cel.: ('.$assinatura->dddcel.') '.$objFuncao->mask($assinatura->cel, '#####-####').'</td>
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