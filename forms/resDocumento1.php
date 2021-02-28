<?php
echo'            
                     <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Ordens de Serviço</h6>
            <table class="table">
                <thead>
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">OS</th>
                    <th scope="col">Data</th>
<!--                    <th scope="col">Serviço(s)</th> -->              
                    <th scope="col">Cliente</th>
 <!--                   <th scope="col">Valor</th>-->
                    <th scope="col">Status</th>
  <!--                  <th scope="col">Pagamento</th>-->
                    <th scope="col">Ver</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Excluir</th>
                </tr>
                </thead>
';
$pg = $_GET['pg'];
if (!isset($pg)) {		
$pg = 0;
}
$numreg = 25;
$inicial = $pg * $numreg;
$proximo = $pg + 1;
$anterior = $pg - 1;

 //conta a quantidade total de registros na tabela
$quantreg = $objOS->querySelectTotalServico($filtro);
 $i = $quantreg;
 //echo $i;
 foreach($objOS->querySelecionaServico($inicial, $numreg, $filtro) as $res){
     echo'           
         <tr align="center">
         <td>'.$i--.'</td>
         <td>'.$res->idOrdemServico.'</td>
         <td>'.$objFuncao->formataData($res->idOrdemServico).'</td>
         <!--<td><button class="btn btn-primary" data-toggle="modal" data-target="#ExemploModalCentralizado" data-id="3" >
  Ver
</button></td>-->
         <!-- Botão para acionar modal -->

         <td>'.$res->nomeCliente.'</td>
  <!--   <td>R$ 450,00</td>-->
         <td>'.$res->situacao.'</td>
  <!--      <td>Ok</td> -->
         <td><a href="exibeServico.php?pg=&busca='.$res->idOrdemServico.'" title="Visualizar esse dado"><img src="images/ico-visualizar.png"  height="16" alt="Visualizar"></td>
         <td><a href="#" title="Alterar esse dado"><img src="images/ico-editar.png" width="16" height="16" alt="Editar"></td>
          <td><a href="#" title="Excluir esse dado"><img src="images/ico-excluir.png" width="16" height="16" alt="Excluir"></td>
                
         </tr>
         <tr>
         <td colspan=11 align="center">';
     // print_r($res);
 }

$quant_pg = ceil($quantreg/$numreg);	
$quant_pg++;		// Verifica se esta na primeira página, se nao estiver ele libera o link para anterior	
if ( $pg > 0) { 		
echo '<a href=?pg='.$anterior .' ><b>« anterior</b></a>';	
} else { 		
echo "";	
}		// Faz aparecer os numeros das página entre o ANTERIOR e PROXIMO	
for($i_pg=1;$i_pg<$quant_pg;$i_pg++) { 		
// Verifica se a página que o navegante esta e retira o link do número para identificar visualmente		
if ($pg == ($i_pg-1)) { 			
echo " [$i_pg] ";		
} else {			
$i_pg2 = $i_pg-1;			
echo ' <a href="?pg='.$i_pg2.'" ><b>'.$i_pg.'</b></a> ';
		}
		}		
		// Verifica se esta na ultima página, se nao estiver ele libera o link para próxima	
		if (($pg+2) < $quant_pg) {
			echo '<a href=?pg='.$proximo .' ><b>próximo »</b></a>';	
			} else {
				echo "";
                                echo '</td></tr>';
				}
echo'
                                                
            </table>
        </div>
                     
   ';   
?>