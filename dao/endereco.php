<?php
class EnderecoTO{
	public $endereco;
	public $numero;
	public $bairro;
	public $telefone;
	public $complemento;
	public $cidade; 
	public $regiao;
	
	public function EnderecoTO(){
	   $this->endereco = NULL;
	   $this->numero = NULL;
	   $this->bairro = NULL;
	   $this->telefone = NULL;
	   $this->complemento = NULL;
	   $this->cidade = NULL;
	   $this->regiao = NULL;
	}
}

class EnderecoDAL{
	public static function getPorId($id){
		$e = new EnderecoTO();
		$o = mysql_fetch_object(mysql_query("select numero,bairro,id_cidade,telefone,complemento,endereco,regiao 
		from endereco 
		where id='$id'"));
		$e->id = $id;
		$e->numero = $o->numero;
		$e->bairro = $o->bairro;
		$e->cidade = CidadeDAL::getPorId($o->id_cidade);
		$e->telefone = $o->telefone;
		$e->complemento = $o->complemento;
		$e->endereco = $o->endereco;
		$e->regiao = $o->regiao;
		return $e;
	}
	public static function cadastrar(EnderecoTO $e){
		if($e->cidade !== NULL){
			$id_cidade = "'{$e->cidade->id}'";
		}
		else{
			$id_cidade = "NULL";
		}
		mysql_query("insert into endereco(id,numero,bairro,id_cidade,telefone,complemento,endereco,regiao)
		values(NULL,'{$e->numero}','{$e->bairro}',$id_cidade,'{$e->telefone}','{$e->complemento}','{$e->endereco}','{$e->regiao}')");
		return mysql_insert_id();
	}
	public static function update(EnderecoTO $e){
		if($e->cidade !== NULL){
			$id_cidade = "'{$e->cidade->id}'";
		}
		else{
			$id_cidade = NULL;
		}
		mysql_query("update endereco set numero='{$e->numero}',
		bairro='{$e->bairro}',
		id_cidade=$id_cidade,
		telefone='{$e->telefone}',
		complemento='{$e->complemento}',
		regiao='{$e->regiao}',
		endereco='{$e->endereco}'
		where id='{$e->id}'");
	}
}
?>