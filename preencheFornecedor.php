<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaFornecedor($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $fornecedor) {
?>
<li onClick="selecionaFornecedor('<?php echo $fornecedor->nomeFornecedor; ?>','<?php echo $fornecedor->idFornecedor; ?>','<?php echo $fornecedor->tipoFornecedor; ?>');"><?php echo $fornecedor->nomeFornecedor; ?></li>
<?php } ?>
</ul>
<?php } } ?>