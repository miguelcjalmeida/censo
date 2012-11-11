<?php
include_once("functions.php");
class DeficienciaTO{
    public $id;
	public $nome;
	public $exibir;
	
	public function DeficienciaTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->exibir = false;
	}
}

class DeficienciaDAL{
	public static function getTodas(){
		$q =  mysql_query("select id,nome,exibir from deficiencia where desativado='n'");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$d = new DeficienciaTO();
			$d->id = $o->id;
			$d->nome = $o->nome;
			$d->exibir = $o->exibir;
			$a[]=$d;
		}
		return $a;
	}	
	public static function getPorId($id){
		$q =  mysql_query("select nome,exibir from deficiencia where id='$id' and desativado='n'");
		$o = mysql_fetch_object($q);
		$d = new DeficienciaTO();
		$d->id = $id;
		$d->nome = $o->nome;
		$d->exibir = $o->exibir;
		return $d;
	}
	public static function apagar(DeficienciaTO $p){
		$where = ($where=getSimpleWhere($p)) ? "WHERE " . $where : "";
		return mysql_query("update deficiencia set desativado='s' $where");
	}
	public static function getTodasExibiveis(){
		$a = self::getTodas();
		$na = array();
		foreach($a as $k=>$v){
			if($v->exibir == 's'){
				$na[] = $v;
			}
		}
		return $na;
	}
	public static function cadastrar(DeficienciaTO $e){
		$where = "";
		if($e->id !== NULL){
			$where = "id='{$e->id}'";
		}
		else if($e->nome !== NULL){
			$where = "nome='{$e->nome}'";
		}
		if($where){
			$where = "where " . $where;
		}
		
		$q = mysql_query("select id from deficiencia $where limit 1");
		if(mysql_num_rows($q) == 0){
			$exibir = "'" . ($e->exibir !== NULL ? ($e->exibir ? $e->exibir : "n") : "n") ."'";
			mysql_query("insert into deficiencia(id,nome,exibir,desativado) values(NULL,'{$e->nome}',$exibir,'n')");
			return mysql_insert_id();
		}
		return mysql_fetch_object($q)->id;
	}
}
?>