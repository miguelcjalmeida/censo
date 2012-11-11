<?php

class EscolaridadeTO{
    public $id;
	public $nome;
	
	public function EscolaridadeTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	}
}

class EscolaridadeDAL{
	public static function getPorId($id){
		$q = mysql_query("select nome from escolaridade where id='$id'");
		$o = mysql_fetch_object($q);
		$d = new EscolaridadeTO();
		$d->nome = $o->nome;
		$d->id = $id;
		return $d;
	}
	public static function getTodas(){
		$q =  mysql_query("select id,nome from escolaridade");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$d = new EscolaridadeTO();
			$d->id = $o->id;
			$d->nome = $o->nome;
			$a[]=$d;
		}
		return $a;
	}	

}
?>