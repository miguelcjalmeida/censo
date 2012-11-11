<?php
class ContaTO{
    public $id;
	public $nome;
	public $usuario;
	public $senha;
	
	public function ContaTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->senha = NULL;
	   $this->usuario = NULL;
	}
}

class ContaDAL{
	public static function apagar(ContaTO $p){
		$where = ($where=getSimpleWhere($p)) ? "WHERE " . $where : "";
		return mysql_query("update conta set desativado='s' $where");
	}
	public static function getPorUsuarioSenha($usuario,$senha){
		$q = mysql_query("select id,nome,senha from conta
		where usuario = '$usuario' and senha = '$senha'");
		$o = mysql_fetch_object($q);
		$c = new ContaTO();
		$c->usuario = $o->usuario;
		$c->senha = $o->senha;
		$c->id = $o->id;
		$c->nome = $o->nome;
		return $c;	
	}
	
	public static function getPorUsuario($usuario){
		$q = mysql_query("select id,nome,senha,usuario from conta where usuario = '".$usuario."'");
		$o = mysql_fetch_object($q);
		$c = new ContaTO();
		$c->usuario = $o->usuario;
		$c->senha = $o->senha;
		$c->id = $o->id;
		$c->nome = $o->nome;
		return $c;	
	}
	
	public static function verificarPorUsuarioSenha($usuario,$senha){
		return mysql_num_rows(mysql_query("select count(*) from conta 
		where usuario='$usuario' and senha = '$senha'")) > 0 ;
	}
}

?>