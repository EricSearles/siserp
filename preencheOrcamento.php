<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaOrcamento($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $orcamento) {

?>
<li onClick="selecionaOrcamento('<?php echo $orcamento->numero; ?>','<?php echo $orcamento->idOrcamento; ?>);"><?php echo $orcamento->numero; ?></li>
<?php } ?>
</ul>
<?php } } ?>