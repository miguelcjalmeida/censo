<?php
include_once("../dao/all.php");
$id_estado = (int) $_REQUEST['id_estado'];

$cds = CidadeDAL::getTodasPorEstado($id_estado);

$s = '[';
$i=0;$f=count($cds);
foreach($cds as $k=>$v){
	$s .= '{';
	$s .= 'id:'.(int)$v->id.',';
	$s .= 'nome:"'.(addslashes($v->nome)).'"';
	$s .= '}';	
	if(++$i < $f){
		$s .= ',';
	}
}
$s .= ']';


echo urlencode($s);
?>
