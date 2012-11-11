<?php

class MotivoTO{
    public $id;
	public $nome;
	public $exibir;
	
	public function MotivoTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->exibir = false;
	}
}

class MotivoTrabalhoDAL{
	public static function getTodos(){
		$q =  mysql_query("select id,nome,exibir from motivo_trabalho");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$d = new MotivoTO();
			$d->id = $o->id;
			$d->nome = $o->nome;
			$d->exibir = $o->exibir;
			$a[]=$d;
		}
		return $a;
	}	
	public static function getTodosExibiveis(){
		$a = self::getTodos();
		$na = array();
		foreach($a as $k=>$v){
			if($v->exibir){
				$na[] = $v;
			}
		}
		return $na;
	}
	public static function cadastrar(MotivoTO $e){
		if($e->id === NULL){
			$q = mysql_query("select id from motivo_trabalho where nome='{$e->nome}' and desativado='n' limit 1");
			if(mysql_num_rows($q) == 0){
				$exibir = "'" . ($e->exibir !== NULL ? ($e->exibir ? $e->exibir : "n") : "n") ."'";
				
				mysql_query("insert into motivo_trabalho(id,nome,exibir,desativado) values(NULL,'{$e->nome}',$exibir,'n')");
				return mysql_insert_id();
			}
		}
		else{
			return $e->id; 	
		}
	}
	public static function getPorId($id){
		$q = mysql_query("select id,nome,exibir from motivo_trabalho where id='$id'");
		$o = mysql_fetch_object($q);
		$i = new MotivoTO();
		$i->nome = $o->nome;
		$i->id = $o->id;
		$i->exibir = $o->exibir;
		return $i;
	}
}

class MotivoTratamentoDAL{
	public static function getTodos(){
		$q =  mysql_query("select id,nome,exibir from motivo_tratamento");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$d = new MotivoTO();
			$d->id = $o->id;
			$d->nome = $o->nome;
			$d->exibir = $o->exibir;
			$a[]=$d;
		}
		return $a;
	}	
	
	public static function getTodosExibiveis(){
		$a = self::getTodos();
		$na = array();
		foreach($a as $k=>$v){

			if($v->exibir == 's'){
				$na[] = $v;
			}
		}
		return $na;
	}
	
	public static function cadastrar(MotivoTO $e){
		if($e->id === NULL){
			$q = mysql_query("select id from motivo_tratamento where nome='{$e->nome}' and desativado='n' limit 1");
			if(mysql_num_rows($q) == 0){
				$exibir = "'" . ($e->exibir !== NULL ? ($e->exibir ? $e->exibir : "n") : "n") ."'";
				mysql_query("insert into motivo_tratamento(id,nome,exibir,desativado) values(NULL,'{$e->nome}',$exibir,'n')");
				return mysql_insert_id();
			}
		}
		else{
			return $e->id;
		}
	}
	public static function getPorId($id){
		$q = mysql_query("select id,nome,exibir from motivo_tratamento where id='$id'");
		$o = mysql_fetch_object($q);
		$i = new MotivoTO();
		$i->id = $o->id;
		$i->nome = $o->nome;
		$i->exibir = $o->exibir;
		return $i; 	
	}
}
?>