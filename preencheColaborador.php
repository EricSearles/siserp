<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaColaborador($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $colaborador) {
?>
<li onClick="selecionaColaborador('<?php echo $colaborador->nomeColaborador; ?>','<?php echo $colaborador->idColaborador; ?>','<?php echo $colaborador->tipoColaborador; ?>');"><?php echo $colaborador->nomeColaborador; ?></li>
<?php } ?>
</ul>
<?php } } ?>