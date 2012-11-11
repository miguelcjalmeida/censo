<?php
class InstituicaoTO{
	public $id;
	public $nome;
	public $cidade;
	public $exibir;
	
	public function InstituicaoTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->cidade = new CidadeTO();
	   $this->exibir = NULL;
	}
}

class InstituicaoDAL{
	public static function getTodas(){
		$q =  mysql_query("select id,nome,id_cidade,exibir from instituicao");
		$a = array();
		while($o = mysql_fetch_object($q)){
			$i = new InstituicaoTO();
			$i->id = $o->id;
			$i->nome = $o->nome;
			if($o->id_cidade){
				$i->cidade = CidadeDAL::getPorId($o->id_cidade);
			}
			else{
				$i->cidade = new CidadeTO();
			}
			$i->exibir = $o->exibir == 's';
			$a[]=$i;
		} 
		return $a;
	}	
	public static function getTodasExibiveis(){
		$a = self::getTodas();
		$na = array();
		foreach($a as $k=>$v){
			if($v->exibir){
				$na[] = $v;
			}
		}
		
		return $na;
	}
	public static function cadastrar(InstituicaoTO $e){
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
		
		$q = mysql_query("select id from instituicao $where limit 1");
		if(mysql_num_rows($q) == 0){
			$id_cidade = $e->cidade !== NULL ? "'".(int)$e->cidade->id."'" : "NULL";
			$exibir = "'" . ($e->exibir !== NULL ? $e->exibir : "n") ."'";
			mysql_query("insert into instituicao(id,nome,exibir,id_cidade,desativado) values(NULL,'{$e->nome}',$exibir,$id_cidade,'n')");
			return mysql_insert_id();
		}
		return mysql_fetch_object($q)->id;
	}
	public static function getPorId($id){
		$q = mysql_query("select nome,exibir,id_cidade from instituicao where id='$id'");
		$o = mysql_fetch_object($q);
		$i = new InstituicaoTO();
		$i->id = $id;
		$i->nome = $o->nome;
		$i->exibir = $o->exibir;
		$i->cidade = new CidadeTO();
		$i->cidade->id = $o->id_cidade;
		return $i;
	}
}
?>