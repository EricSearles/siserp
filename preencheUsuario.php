<?php
require_once 'classes/Funcoes.php';
require_once 'classes/Busca.php';

$objFuncao = new Funcoes();
$objBusca  = new Busca();

if(!empty($_POST["keyword"])) { 
  $dado = $_POST["keyword"];
  $result = $objBusca->buscaUsuario($dado);   
  //print_r($result);
if(!empty($result)) {
?>
<ul id="lista">
<?php
foreach($result as $usuario) {
?>
<li onClick="selecionaUsuario('<?php echo $usuario->nomeUsuario; ?>','<?php echo $usuario->idUsuario; ?>');"><?php echo $usuario->nomeUsuario; ?></li>
<?php } ?>
</ul>
<?php } } ?>