<?php
include_once("../dao/all.php");
$p = new PacienteTO();

$p->nome = addslashes($_POST['nome_da_pessoa']);
$p->sexo = addslashes($_POST['sexo']);
$p->nascimento = addslashes($_POST['nascimento_ano'] . "-" . $_POST['nascimento_mes'] . "-" . $_POST['nascimento_dia']);

$p->endereco->endereco = addslashes($_POST['endereco']);
$p->endereco->numero = (int) $_POST['numero'];
$p->endereco->complemento = addslashes($_POST['complemento']);
$p->endereco->bairro = addslashes($_POST['bairro']);
$p->endereco->regiao = addslashes($_POST['regiao']);
$p->endereco->telefone = addslashes($_POST['telefone']);
$p->endereco->cidade = new CidadeTO();
$p->endereco->cidade->id = 9522;
$p->responsavel = addslashes($_POST['nome_do_responsavel']);
$p->email = addslashes($_POST['email_do_responsavel']);
$p->estaEstudando = addslashes($_POST['estudando']);

//echo '-->' . $_POST['especificacaoDeficiencia'] . ' --' ;
$p->especificacaoDeficiencia =  addslashes($_POST['especificacaoDeficiencia']);
$p->deficiencias = array();

foreach($_POST as $k=>$v){
	if(strpos($k,"d_") === 0){
		$d= new DeficienciaTO();
		$d->id = $v;
		$p->deficiencias[] = $d;
	}	
}

$p->autista = addslashes($_POST['autismo']);
$faztratamento = addslashes($_POST['acompanhamento']);
if($faztratamento == 's'){
	$onde = addslashes($_POST['acompanhamento_onde']);
	if($onde == "outro"){
		$nome = addslashes($_POST['acompanhamento_especifique']);
		$id_cidade = (int) $_POST['acompanhamento_cidade'];
		$p->instituicao = new InstituicaoTO();
		$p->instituicao->cidade = new CidadeTO();
		$p->instituicao->cidade->id = $id_cidade;
		$p->instituicao->nome = $nome;
		$p->instituicao->exibir = 'n';
	}
	else{
		$p->instituicao = new InstituicaoTO();
		$p->instituicao->id = (int) $onde;
	}
}
else{
	$onde = addslashes($_POST['nao_acompanhamento']);
	if($onde == "outro"){
		
		$p->motivoTratamento = new MotivoTO();
		$p->motivoTratamento->nome = addslashes($_POST['acompanhamento_motivo']);
		
	}
	else{
		$p->motivoTratamento = new MotivoTO();
		$p->motivoTratamento->id = (int ) ($_POST['nao_acompanhamento']);
	}
}

$sabeler = addslashes($_POST['sabe_ler']);
if($sabeler == 's'){
	$p->escolaridade = new EscolaridadeTO();
	$p->escolaridade->id = (int) $_POST['escolaridade'];
}

$estatrabalhando = addslashes($_POST['trabalha']);	


if($estatrabalhando == 'n'){

	
	$p->motivoTrabalho = new MotivoTO();
	$p->motivoTrabalho->id = (int)$_POST['nao_trabalha'];
}

$possuirenda = addslashes($_POST['renda']);
if($possuirenda=='s'){
	$p->renda = addslashes($_POST['qual_renda']);
}

$editar = isset($_GET['id']);
if($editar){
	$p->id = (int) $_GET['id'];
	PacienteDAL::update($p);
}
else{
	PacienteDAL::cadastrar($p);
	
}
//echo "<xmp>\n\n";print_r($p);echo "</xmp>";

header("location:../listagem/index.php");

?>
