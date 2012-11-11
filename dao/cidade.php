<?php
class EstadoTO{
    public $id;
	public $nome;
	public $uf;
	
	public function EstadoTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->uf = NULL;
	}
}
class CidadeTO{
    public $id;
	public $nome;
	public $estado;
	
	public function CidadeTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->estado = NULL;
	}
}

class EstadoDAL{
	public static function getPorId($id){
		$e = new EstadoTO();
		$o = mysql_fetch_object(mysql_query("select nome,uf from estado where id='$id'"));
		$e->id = $id;
		$e->nome = $o->nome;
		$e->uf = $o->uf;
		return $e;
	}
	public static function getTodos(){
		$q = mysql_query("select id,nome,uf from estado");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$e = new EstadoTO();
			$e->id = $o->id;
			$e->nome = $o->nome;
			$e->uf = $o->uf;
			$a[] = $e;
		}
		return $a;
	}
}
class CidadeDAL{
	public static function getCidadePorId($id){
		return CidadeDAL::getPorId($id);
	}
	
	public static function getPorId($id){
		$c = new CidadeTO();
		$o = mysql_fetch_object(mysql_query("select nome,id_estado from cidade where id='$id'"));
		
		$c->id = $id;
		$c->nome = $o->nome;
		$c->estado = EstadoDAL::getPorId($o->id_estado);
		return $c;
	}
	public static function getTodasPorEstado($id){
		$q = mysql_query("select id,nome from cidade where id_estado='$id'");
		$a=array();
		while($o=mysql_fetch_object($q)){
			$c = new CidadeTO();
			$c->id = $o->id;
			$c->nome = $o->nome;
			$a[]=$c;
		}
		return $a;
	}

}
?>