<?php
	include_once("../utility/grid.php");
	include_once("../base/topo.php");
	session_start();

	if( isset($_POST['session']) or  $_POST['session'] == 'limpa' ){
        $_SESSION['sql_relatorio'] = false;
	}
	
	if(!$_SESSION['sql_relatorio']){
	
	$sexo 			= $_REQUEST["sexo"];
	$idade 			= $_REQUEST["idade"];
	$deficiencia 	= $_REQUEST["deficiencia"];
	$autismo 		= $_REQUEST["autimo"];
	$tratamento 	= $_REQUEST["tratamento"];
	$alfabatizado 	= $_REQUEST["ler"];
	$trabalha 		= $_REQUEST["trabalha"];
	$renda 			= $_REQUEST["renda"];
	$regiao 		= $_REQUEST["regiao"];
	
	function colunaAcao($s,$i,$c){
		$r = "<center>";
		$r .= "<a target='_blank' href='../listagem/visualizar.php?id=".$c[0]."' title='Visualizar Deficiente'> <img src='../imagens/b_select.png' border='0'/> </a>";
		$r .= "<a target='_blank' href='../cadastrar/index.php?id=".$c[0]."' title='Editar Deficiente'> <img src='../imagens/b_edit.png' border='0'/> </a>";
		$r .= "<a href='../apagar/index.php?id=".$c[0]."' title='Apagar Deficiente' > <img src='../imagens/b_drop.png' border='0'/> </a>";
		$r .= "</center>";
		return $r;
	}
	
	if((int)$idade == 1){
		$idade = "( datediff(now(), nascimento)/365.25 < 18 ) "; 
	}
	else if((int)$idade == 2){
		$idade = "( datediff(now(), nascimento)/365.25 >= 18 and datediff(now(), nascimento)/365.25 <= 45 ) "; 
	}
	else if((int)$idade == 3){
		$idade = "( datediff(now(), nascimento)/365.25 > 45 ) ";
	}
	else{
		$idade = "( 1 = 1 ) ";
	}
	
	if($deficiencia == "%"){
		$deficiencia = "( 1=1 ) ";
	}
	else{
		$deficiencia = "( pd.id_deficiencia = '".$deficiencia."') ";
	}
	
	if($sexo == "m"){
		$sexo = "( `sexo` = 'm' ) "; 
	}else if($sexo == "f"){
		$sexo = "( `sexo` = 'f' ) "; 
	}else{
		$sexo = "( 1 = 1 ) "; 
	}
	
	
	if($autismo == "s"){
		$autismo = "( `autista` = 's' ) "; 
	}else if($autismo == "n"){
		$autismo = "( `autista` = 'n' ) "; 
	}else{
		$autismo = "( 1 = 1 ) "; 
	}
	
	
	if($tratamento == "s"){
		$tratamento = "( `id_motivo_tratamento` IS NULL ) "; 
	}else if($tratamento == "n"){
		$tratamento = "( `id_motivo_tratamento` IS NOT NULL ) "; 
	}else{
		$tratamento = "( 1 = 1 ) "; 
	}


	
	if($trabalha == "s"){
		$trabalha = "( `id_motivo_trabalho` IS NULL ) "; 
	}else if($trabalha == "n"){
		$trabalha = "( `id_motivo_trabalho` IS NOT NULL ) "; 
	}else{
		$trabalha = "( 1 = 1 ) "; 
	}
		
		
	if($alfabatizado == "s"){
		$alfabatizado = "( `id_escolaridade` IS NOT NULL ) "; 
		
	}else if($alfabatizado == "n"){
		$alfabatizado = "( `id_escolaridade` IS NULL ) "; 
	}else{
		$alfabatizado = "( 1 = 1 ) "; 
	}
	
	if($renda == "s"){
		$renda = "( `renda` IS NOT NULL ) "; 
	}else if($renda == "n"){
		$renda = "( `renda` IS NULL  or `renda` = '') "; 
	}else{ 
		$renda = "( 1 = 1 ) "; 
	}
	
	
	if($regiao == "todos" or ((int)$regiao) <= 0){
		$regiao = "( 1 = 1 ) "; 
	}else if(((int)$regiao) > 0){
		$regiao = "( e.`regiao` = '".$regiao."') "; 
	}else{ 
		$regiao = "( 1 = 1 ) "; 
	}
	
	
	$sql = "SELECT p.id,p.nome,p.sexo,p.nascimento 
			FROM paciente p
			LEFT OUTER JOIN (deficiencia d, paciente_deficiencia pd , endereco e) 
			ON(pd.id_paciente = p.id AND pd.id_deficiencia = d.id AND p.id_endereco = e.id)
	        WHERE $sexo 
			AND $idade 
			AND $deficiencia 
			AND $autismo 
			AND $tratamento 
			AND $alfabatizado 
			AND $trabalha 
			AND $renda 
			AND $regiao 
			AND p.desativado = 'n'
			GROUP BY p.id
			"; 
      $_SESSION['sql_relatorio'] = $sql;
      
    }else{

      $_SESSION['sql_relatorio'] = $_SESSION['sql_relatorio'];
      $sql = $_SESSION['sql_relatorio'];
    }
    
	$g = new Grid("grid",$sql);  
	$g->addColumn("Ação","");

	$g->setHeaderTitle(0,"ID");
	$g->setHeaderTitle(1,"Nome");
	$g->setHeaderTitle(2,"Sexo");
	$g->setHeaderTitle(3,"Nascimento");
	
	$g->setFormat(2,array("switch","m"=>"Masculino","default"=>"Feminino"));
	$g->setFormat(3,array("date","d/m/Y"));
	$g->setSearchFilter(2,"combobox",array(array("m","Masculino"),array("f","Feminino")));
	$g->setSearchFilter(3,"date");
	
	$g->setFormat(4,array("function",colunaAcao));
?>
<div id="page_formulario">
     <h1>Tabela de Deficientes Filtrada</h1>
     <div style="margin: 5px 0 0 25px;">
          <a href="exportar.php" style="text-decoration:none;color:#000;">
                  <img src="../imagens/b_export.png" border="0" > Exportar para Excel este Relátorio.
          </a>
     </div>

</div>
<?php
	$g->create();	
	include_once("index.php");
	include_once("../base/rodape.php");
?>
