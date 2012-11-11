<?php
class PacienteTO{
	public $id;
	public $nome;
	public $sexo;
	public $nascimento;
	public $endereco;
	public $responsavel;
	public $email;
	public $autista;
	public $escolaridade;
	public $instituicao;
	public $motivoTrabalho;
	public $motivoTratamento;
	public $renda;
	public $deficiencias;
	public $especificacaoDeficiencia;
	public $estaEstudando;
	
	public function PacienteTO(){
	   $this->id = NULL;
	   $this->nome = NULL;
	   $this->sexo = NULL;
	   $this->nascimento = NULL;
	   $this->endereco = new EnderecoTO();
	   $this->responsavel = NULL;
	   $this->email = NULL;
	   $this->autista = NULL;
	   $this->escolaridade = NULL;
	   $this->instituicao = NULL;
	   $this->motivoTrabalho = NULL;
	   $this->motivoTratamento = NULL;
	   $this->especificacaoDeficiencia = NULL;
	   $this->renda = NULL;
	   $this->estaEstudando = true;
	   $this->deficiencias = array();
	}
	public function sabeLer(){
		if($this->escolaridade){
			return true;
		}
		return false;
	}
	public function estaEstudando(){ 
        if($this->estaEstudando == 's' or $this->estaEstudando == 'S' or $this->estaEstudando === true){
			return true;
		}
		return false;
	} 
	public function possuiRenda(){
		return $this->renda !== NULL;
	}
	public function fazTratamento(){
		if($this->instituicao){
			return true;
		}
		return false;
	}
	public function estaTrabalhando(){
		if($this->motivoTrabalho){
			return false;
		}
		return true;
	}
	public function temDeficiencia($id){
        for($i=0;$i<count($this->deficiencias);$i++){
            if($this->deficiencias[$i]->id == $id){
                return true;
            }
        }
        return false;
	}
}

class PacienteDAL{
	private static function setDefaultValues(PacienteTO &$p){
		if($p->autista){
			$p->autista = 's';
		}
		else{
			$p->autista = 'n';
		}
	}
	public static function getIdEndereco($id){
		$q = mysql_query("select id_endereco from paciente where id='$id'");
		return mysql_fetch_object($q)->id_endereco;
	}
	public static function getPorId($id){
        $p = new PacienteTO();
        $o=mysql_fetch_object(mysql_query("select id,nome,sexo,nascimento,id_endereco,responsavel,
		email,autista,id_escolaridade,id_instituicao,id_motivo_trabalho,renda,id_motivo_tratamento,especificacaoDeficiencia,estaEstudando from paciente where id='$id' and desativado = 'n'"));
		$p->id=$id;
		$p->nome=$o->nome;
		$p->sexo=$o->sexo;
		$p->nascimento=$o->nascimento;
		$p->responsavel=$o->responsavel;
		$p->email=$o->email;
		$p->autista=$o->autista;
		$p->renda=$o->renda;
		$p->estaEstudando = $o->estaEstudando;
		
		$p->endereco = EnderecoDAL::getPorId($o->id_endereco);
		if($o->id_escolaridade){
			$p->escolaridade = EscolaridadeDAL::getPorId($o->id_escolaridade);
		}
		if($o->id_instituicao){
			$p->instituicao = InstituicaoDAL::getPorId($o->id_instituicao);
		}
		if($o->id_motivo_trabalho){
			$p->motivoTrabalho = MotivoTrabalhoDAL::getPorId($o->id_motivo_trabalho);
		}
		if($o->id_motivo_tratamento){
			$p->motivoTratamento = MotivoTratamentoDAL::getPorId($o->id_motivo_tratamento);
		}
		$p->deficiencias = self::getDeficiencias($id);
		$p->especificacaoDeficiencia =  $o->especificacaoDeficiencia;
		return $p;
	}
	
	public static function update(PacienteTO $p){
		
		if(($p->endereco->id=self::getIdEndereco($p->id))!==NULL){
			$id_endereco = "'{$p->endereco->id}'";
			EnderecoDAL::update($p->endereco);
		}
		else{
			$id_endereco = "'".EnderecoDAL::cadastrar($p->endereco)."'";
		}
		
		if($p->sabeLer()){
			$id_escolaridade = "'".$p->escolaridade->id."'";
		}
		else{
			$id_escolaridade = "NULL";
		}
		if($p->fazTratamento()){
			$id_instituicao = "'".InstituicaoDAL::cadastrar($p->instituicao)."'";
			$id_motivo_tratamento = "NULL";
		}
		else{
			$id_instituicao = "NULL";
			if($p->motivoTratamento !==NULL ){
				
				$id_motivo_tratamento = "'".MotivoTratamentoDAL::cadastrar($p->motivoTratamento)."'";
				
			}
			else{
				
				$id_motivo_tratamento = "NULL";
			}
		}
		if($p->possuiRenda()){
			$renda = "'".$p->renda."'";
		}
		else{
			$renda = "NULL";
		}
		
		//if(!$p->estaTrabalhando() and ((int) $id_motivo_trabalho) != 0){
		if(!$p->estaTrabalhando()){ 
			$id_motivo_trabalho = "'".MotivoTrabalhoDAL::cadastrar($p->motivoTrabalho)."'";
		}
		else{
			$id_motivo_trabalho = "NULL";
		}
		if($p->autista == 's' || $p->autista===true || $p->autista===NULL){
			$autista = 's';
		}
		else if($p->autista===false){
			$autista = 'n';
		} 
		
		
		if($p->estaEstudando == 's' || $p->estaEstudando===true || $p->estaEstudando===NULL){
			$estaEstudando = 's';
		}
		else if($p->autista===false){
			$estaEstudando = 'n';
		}

		self::updateDeficiencias($p->id,$p->deficiencias);
		
		$query = ("update paciente SET nome='{$p->nome}',
		sexo='{$p->sexo}',
		nascimento='{$p->nascimento}',
		responsavel='{$p->responsavel}',
		email='{$p->email}',
		autista='{$autista}',
		renda=$renda,
		id_escolaridade=$id_escolaridade,
		id_instituicao=$id_instituicao,
		id_motivo_trabalho=$id_motivo_trabalho,
		id_motivo_tratamento=$id_motivo_tratamento,
		id_endereco=$id_endereco,
		especificacaoDeficiencia='{$p->especificacaoDeficiencia}',
		estaEstudando='{$p->estaEstudando}'
		where id='{$p->id}'");	
		
		echo $query;
		
		return mysql_query($query);
	}
	
	public static function apagar($id){
		return mysql_query("update paciente set desativado='s' where id='{$id}'");
	}
	
	public static function cadastrar(PacienteTO $p){
		$id_endereco = EnderecoDAL::cadastrar($p->endereco);
		if($p->sabeLer()){
			$id_escolaridade = "'".$p->escolaridade->id."'";
		}
		else{
			$id_escolaridade = "NULL";
		}
		if($p->fazTratamento()){
			
			$id_instituicao = "'".InstituicaoDAL::cadastrar($p->instituicao)."'";
			$id_motivo_tratamento = "NULL";
		}
		else{
			$id_instituicao = "NULL";
			if($p->motivoTratamento !==NULL){
				$id_motivo_tratamento = "'".MotivoTratamentoDAL::cadastrar($p->motivoTratamento)."'";
			}
			else{
				$id_motivo_tratamento = "NULL";
			}
		}
		if($p->possuiRenda()){
			$renda = "'".$p->renda."'";
		}
		else{
			$renda = "NULL";
		}
		

		
		//if(!$p->estaTrabalhando() and ((int) $id_motivo_trabalho) != 0 ){ 
		
		if(!$p->estaTrabalhando()){ 
			$id_motivo_trabalho = "'".((int)$p->motivoTrabalho)."'";
		}
		else{
			$id_motivo_trabalho = "NULL";
		}
		
		if($p->estaEstudando===true || $p->estaEstudando===NULL){
			$estaEstudando = 's';
		}
		else if($p->estaEstudando===false){
			$estaEstudando = 'n';
		}
		else{
			$estaEstudando = $p->estaEstudando;
		}
		
		if($p->autista===true || $p->autista===NULL){
			$autista = 's';
		}
		else if($p->autista===false){
			$autista = 'n';
		}
		else{
			$autista = $p->autista;
		}
		
		
		if($p->id){
			$id = "'{$p->id}'";
		}
		else{
			$id = "NULL";
		}	
		
	   $query = ("insert into paciente(id,nome,sexo,nascimento,responsavel,email,autista,renda,id_escolaridade,id_instituicao,id_motivo_trabalho,id_motivo_tratamento,id_endereco,especificacaoDeficiencia,estaEstudando,desativado)
		values($id,'{$p->nome}','{$p->sexo}','{$p->nascimento}','{$p->responsavel}','{$p->email}','{$autista}',$renda,$id_escolaridade,$id_instituicao,$id_motivo_trabalho,$id_motivo_tratamento,'$id_endereco','$p->especificacaoDeficiencia','$p->estaEstudando','n')");
		
		//echo $query;
		
		mysql_query($query);
		
		$id_palestrante = mysql_insert_id();
		
		self::cadastrarDeficiencias($id_palestrante,$p->deficiencias);
		
		return $id_palestrante;
	}
	public function getDeficiencias($id){
		$a = array();
		$q = mysql_query("select id_deficiencia from paciente_deficiencia 
		inner join(deficiencia d) on (d.id = id_deficiencia and d.desativado='n') 
		where id_paciente='$id'");
		while($o=mysql_fetch_object($q)){
			$a[] = DeficienciaDAL::getPorId($o->id_deficiencia);
		}
		return $a;
	}
	private function updateDeficiencias($id_paciente,$a=array()){
		mysql_query("delete from paciente_deficiencia where id_paciente='$id_paciente'");
		self::cadastrarDeficiencias($id_paciente,$a);
	}
	private function cadastrarDeficiencias($id_paciente,$a=array()){
		foreach($a as $k=>$v){
			$id_deficiencia = DeficienciaDAL::cadastrar($v);
			self::relacionarComDeficiencia($id_paciente,$id_deficiencia);	
		} 
	} 
	private function relacionarComDeficiencia($id_paciente,$id_deficiencia){
		mysql_query("insert into paciente_deficiencia(id,id_paciente,id_deficiencia) values(NULL,'$id_paciente','$id_deficiencia')");
	}
}
?>
