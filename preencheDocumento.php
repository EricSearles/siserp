<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaDocumento($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $documento) {
?>
<li onClick="selecionaDocumento('<?php echo $documento->nDocumento; ?>','<?php echo $documento->idItemOS; ?>');"><?php echo $documento->nDocumento; ?></li>
<?php } ?>
</ul>
<?php } } ?>