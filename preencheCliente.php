<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaCliente($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $cliente) {

?>
<li onClick="selecionaCliente('<?php echo $cliente->nomeCliente; ?>','<?php echo $cliente->idCliente; ?>','<?php echo $cliente->tipoCliente; ?>');"><?php echo $cliente->nomeCliente;; ?></li>
<?php } ?>
</ul>
<?php } } ?>