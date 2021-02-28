        <div>
            <table   cellpadding="6">
                <tr>
                    <td colspan="20" align="center">ORDEM DE SERVIÇO</td>
                </tr>
                <tr>
                    <td colspan="20"></td>
                </tr>
                <tr>
                    <td colspan="10">Nº O.S.: <?php echo $res->idOrdemServico;?></td>
                    <!--Seleciona tabela usuario-->
                    
                    <td colspan="10" align="right">Data: <?php echo $objFuncao->formataData($res->data);?></td>
                </tr>
                <tr>
                    <td colspan="10" >Solicitante: <?php echo $res->nomeColaborador;?></td>
<!--                    Seleciona tabela usuario-->
                    <td colspan="10" align="right">Responsável: <?php echo $res->nomeUsuario;?></td>
                </tr>

                <tr>
                    <!--                    Seleciona tabela cliente-->
                    <td colspan="10">Cliente: <?php echo $res->nomeCliente;?></td>                  
                    <td colspan="10" align="right">Vencimento: <?php echo $objFuncao->formataData($res->validade);?></td>
                </tr>
                <?php
                $pag = $objPagamento->querySelecionaPagamento($id);
               // print_r($pag);
                 foreach ($pag as $parc) {
                     if($parc->parcela == 'Sinal'){
                            
                         $tipo = 'Parcelado';
                         echo '<tr>
                                    <td colspan="10">Pagamento: '.$tipo.'<td>
                               <td colspan="10" align="right">Valor Total: R$'.$objFuncao->SubstituiPonto($res->total).'</td>
                            </tr>
                            <tr>
                            <td colspan="20" >Forma de Pagamento: '.$res->formaPagamento.'</td>
                            </tr>';
                     }else{
                         //echo 'Não';
                     }
                   
                 }
                    
                foreach ($pag as $pagamento) {
                            //print_r($pagamento);


                           
                            if($pagamento->parcela == "Única"){
                                $tipoPagamento = 'À vista';
                                echo '                
                                    <tr>
                                        <td colspan="10">Pagamento: '.$tipoPagamento.'</td>
                                        <td colspan="10" align="right">Forma de Pagamento: '.$res->formaPagamento.'</td>
                                    </tr>
                                    <tr>
                                    <td colspan="7">Valor: R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        <td colspan="7">Data Pagamento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                            
                                        <td colspan="6" align="right">Situação: '.$pagamento->situacao.'</td>
                                    </tr>';
                                
                            }else{
                                $tipoPagamento = 'Parcelado';
                                echo'                
                                  
                                    <tr>
                                        <td colspan="4">Parcela: '.$pagamento->parcela.'</td>
                                            <td colspan="6">Vencimento: '.$objFuncao->formataData($pagamento->dataVencimento).'</td>
                                        <td colspan="6">Valor: R$ '.$objFuncao->SubstituiPonto($pagamento->valor).'</td>
                                        
                                        <td colspan="4" align="right">Situação: '.$pagamento->situacao.'</td>
                                    </tr>';
                                }
                            }
                ?>
                

                                    <tr>
                                       <td colspan="20"><hr></td>
                                    </tr>
                                    <tr>

                                        <td colspan="20">Serviço(s):</td>
                                    </tr>
                <tr>
                   <td colspan="20"><hr></td>
                </tr>
                <tr>
                    <td>Item</td>                    
                    <td colspan="2" align="center">NºDocumento</td>
                    <td colspan="2" align="center">NºProcesso</td>
                    <td colspan="2">Serviço</td>
                    <td align="center">Obs</td>
                    <td align="center">Qtd</td>
                    <td colspan="4" align="center">Valor</td>

                    <td colspan="3" align="center">Previsão</td>
                    <td colspan="3" align="center">Status do serviço</td>
                </tr>
                <?php
                $i = 1;

        foreach($objOS->querySelecionaItem($id) as $item){
            //print_r($item);
        
                echo'                   
                <tr>
<!--                    Seleciona tabela serviço disponibilizado-->                    

                    <td align="center">'.$i++.'</td>
                    <td colspan="2">'.$item->nDocumento.'</td>
                    <td colspan="2">'.$item->nProcesso.'</td>
                    <td colspan="2" align="center">'.$item->sigla.'</td>	
                    ';
                if(empty($item->obs)){
                    echo '<td align="center"> - </td>';
                }else{
                     echo  '<td align="center"><a href="?pg=&busca='.$res->idOrdemServico.'&item='.$item->idItemOS.'&obs=ok">Sim</a></td>';
                  ;
                                                             
                }  
echo'
<!--                    Opção de entrada manual tambem-->
                    <td align="center">'.$item->qtd.'</td>
                    <td colspan="4" align="center">R$'.$item->valorItem.'</td>
                    <td colspan="3" align="center">'.$item->entrega.'</td>

                    <td colspan="3" align="center"><a href="?pag=&busca='.$id.'&item='.$item->idItemOS.'">'.$item->situacao.'</a></td>
                </tr>';
                                if(isset($_GET['obs'])&& $_GET['obs'] == 'ok') {
                       echo '<tr bgcolor="FF6"><td colspan="20" align="center">'.$item->obs.'</td></tr>';
                }
                 echo'
                 <tr>
                   <td colspan="20"><hr></td>
                </tr>
                                ';
                }
                ?>

            </table>      
        <a href="?pg=&acao=alterar">Alterar</a> |      
        <a href="?pg=&acao=limpar">Voltar</a>
  
</div>  