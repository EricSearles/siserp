<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaProcesso($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $processo) {
?>
<li onClick="selecionaProcesso('<?php echo $processo->nProcesso; ?>','<?php echo $processo->idItemOS; ?>');"><?php echo $processo->nProcesso; ?></li>
<?php } ?>
</ul>
<?php } } ?>