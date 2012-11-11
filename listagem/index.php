<?php
include_once("../utility/grid.php");
include_once("../base/topo.php");
?>

<div class="center">
<script>
        function apagar(num,nome){
                 var apaga = confirm("Deseja excluir o Registro ? \n ID : '" + num + "' \n Nome: '" + nome + "'");
                 
                 if(apaga){
                           url = "../apagar/index.php?id=" + num ;
                           location.href = url;
                 }
        }
</script>
<?php

function colunaAcao($s,$i,$c){
	$r = "<center>";
    $r .= "<a href='visualizar.php?id=".$c[0]."' title='Visualizar Deficiente'> <img src='../imagens/b_select.png' border='0'/> </a>";
    $r .= "<a href='../cadastrar/index.php?id=".$c[0]."' title='Editar Deficiente'> <img src='../imagens/b_edit.png' border='0'/> </a>";
	$r .= "<a href='javascript: apagar(".$c[0].",\"".$c[1]."\");' title='Apagar Deficiente' > <img src='../imagens/b_drop.png' border='0'/> </a>";
	$r .= "</center>";
	return $r;
}

$g = new Grid("grid","select p.id,p.nome,p.sexo,p.nascimento from paciente p 
where %filtro LIKE '%%palavra%' and desativado = 'n'
order by %ord");

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

$g->create();
?>

</div>
<br>
<br>

<?php
include_once("../base/rodape.php");
?>
