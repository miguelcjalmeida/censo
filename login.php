<?php
	@session_start();
	include_once("dao/all.php");
	
	$obj = ContaDAL::getPorUsuario(addslashes($_REQUEST["usuario"]));

	if($obj->senha == $_REQUEST["senha"] ){
		$_SESSION["usuario"] = $obj->usuario;
		$_SESSION["nome"] = $obj->nome;
		$_SESSION["logado"] = true;
		header("location: alunos.php");
	}else{
		header("location: alunos.php");		
	}
?>