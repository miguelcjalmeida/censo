<?php
include_once("../base/topo.php");
    session_start();
	function comboregiao($n = 0){
		echo '<select id="regiao" name="regiao" style="width:300px;">';
		$q = mysql_query('SELECT * FROM `regiao`');
		echo '<option value="todos" >Todas Regiões</option>';
		while($o = mysql_fetch_object($q)){
			echo '<option value="'. $o->id .'" '.( ($o->id == $n)?'selected="selected"':'' ).' >'. $o->nome .'</option>';
		}
		echo '</select>';
	}
	
    if(!isset($_SESSION['sql_relatorio'])){
	  $_SESSION['sql_relatorio'] = '';
      $_SESSION['sql_relatorio'] = false;
	}
	
?>
<form action="filtragem.php" method="post">
<input type="hidden" id="session" name="session" value="limpa" />

<div id="page_formulario">

<br />
<h1>Filtros</h1>

<table border="0">
	<tr>
    	<td class="especial">Sexo:</td>
        <td> <input type="radio" id="sexo" name="sexo" value="%" checked /> Todos </td>
        <td> <input type="radio" id="sexo" name="sexo" value="m" /> Masculino </td>
        <td colspan="3"> <input type="radio" id="sexo" name="sexo" value="f" /> Feminino </td>
    </tr>
    <tr>
    	<td class="especial">Idade:</td>
        <td> <input type="radio" id="idade" name="idade" value="%" checked /> Todas </td>
        <td> <input type="radio" id="idade" name="idade" value="1" /> Menos de 18 anos. </td>
        <td> <input type="radio" id="idade" name="idade" value="2" /> Entre 18 e 45 anos. </td>
        <td colspan="2"> <input type="radio" id="idade" name="idade" value="3" /> Mais de 45 anos. </td>
    </tr>
    <tr>
    	<td class="especial">Deficiencia:</td>
        <td> <input type="radio" id="deficiencia" name="deficiencia" value="%"  checked/> Todas </td>
        <td> <input type="radio" id="deficiencia" name="deficiencia" value="1" /> Auditiva </td>
        <td> <input type="radio" id="deficiencia" name="deficiencia" value="2" /> Física </td>
        <td> <input type="radio" id="deficiencia" name="deficiencia" value="3" /> Mental </td>
        <td> <input type="radio" id="deficiencia" name="deficiencia" value="4" /> Visual </td>
    </tr>
    <tr>
    	<td class="especial">Tem Autismo:</td>
        <td> <input type="radio" id="autismo" name="autismo" value="%"  checked/> Todos </td>
        <td> <input type="radio" id="autismo" name="autismo" value="s" /> sim </td>
        <td colspan="3"> <input type="radio" id="autismo" name="autismo" value="n" /> não </td>
    </tr>
    <tr>
    	<td class="especial">Realiza Tratamento:</td>
        <td> <input type="radio" id="tratamento" name="tratamento" value="%" checked /> Todos </td>
        <td> <input type="radio" id="tratamento" name="tratamento" value="s" /> sim </td>
        <td colspan="3"> <input type="radio" id="tratamento" name="tratamento" value="n" /> não </td>
    </tr>
    <tr>
    	<td class="especial">Sabe Ler ou Escrever:</td>
        <td> <input type="radio" id="ler" name="ler" value="%" checked /> Todos </td>
        <td> <input type="radio" id="ler" name="ler" value="s" /> sim </td>
        <td colspan="3"> <input type="radio" id="ler" name="ler" value="n" /> não </td>
    </tr>
    <tr>
    	<td class="especial">Esta Trabalhando:</td>
        <td> <input type="radio" id="trabalha" name="trabalha" value="%"  checked /> Todos </td>
        <td> <input type="radio" id="trabalha" name="trabalha" value="s" /> sim </td>
        <td colspan="3"> <input type="radio" id="trabalha" name="trabalha" value="n" /> não </td>
    </tr>
    <tr>
    	<td class="especial">Possui Renda:</td>
        <td> <input type="radio" id="renda" name="renda" value="%" checked /> Todos </td>
        <td> <input type="radio" id="renda" name="renda" value="s" /> sim </td>
        <td colspan="3"> <input type="radio" id="renda" name="renda" value="n" /> não </td>
    </tr>
    <tr>
    	<td class="especial">Região:</td>
        <td colspan="5"><?php comboregiao(null);?></td>
    </tr>	
</table>


</div>
<br>
<input type="submit" value="Filtrar" style="margin-left:50px;" />
<input type="reset" value="Redefinir" style="margin-left:10px;" />

</form>
<?php
include_once("../base/rodape.php");
?>
