<?php
	include_once("../base/topo.php");
	$c = true;
	if(isset($_GET['id'])){
		$p = PacienteDAL::getPorId((int) $_GET['id']);
		$dt = explode("-",$p->nascimento);
		$dia = $dt[2];
		$mes = $dt[1];
		$ano = $dt[0];  
		if($p->instituicao !== NULL && $p->instituicao->exibir == 'n'){
			$especifique_instituicao = $p->instituicao->nome;
			$cidade_instituicao = $p->instituicao->cidade->id;
			$estado_instituicao= CidadeDAL::getCidadePorId($p->instituicao->cidade->id)->estado->id;
		}
		else{ 
			$especifique_instituicao = "";
			$cidade_instituicao = 9522;
			$estado_instituicao= 26;
		}
		
		//print_r($p);
		$c = false;
	}
	else{
		$p = new PacienteTO();
		$p->nome = "";
		$p->sexo = 'm';
		$p->nascimento = "";
		$dia = date("d"); 
		$mes = date("m");
		$ano = date("Y");
		$p->endereco = new EnderecoTO();
		$p->endereco->endereco = "";
		$p->endereco->bairro = "";
		$p->endereco->telefone = "";
		$p->endereco->complemento = "";
		$p->endereco->numero = "";
		$p->endereco->regiao = "";
		$p->deficiencias = array();
		$p->autista = "n";
		$p->estaEstudando = "n";
		$p->motivoTratamento = new MotivoTO();
		$p->motivoTratamento->id = 1;
		$p->especificacaoDeficiencia = "";
		$especifique_instituicao = "";
		$cidade_instituicao = 9522;
		$estado_instituicao= 26;
	}
	
	function comboregiao($n = 0){
		$q = mysql_query('SELECT * FROM `regiao`');
		while($o = mysql_fetch_object($q)){
			echo '<option value="'. $o->id .'" '.( ($o->id == $n)?'selected="selected"':'' ).' >'. $o->nome .'</option>';
		}
		echo '</select>';
	}

?>

<script type="text/javascript" src="../js/ajax.js"></script>
<script type="text/javascript" src="../js/mascara.js"></script>
<form action="engine.php<?= (isset($_GET['id']))?'?id=' . ($_GET['id']):''; ?>" onsubmit="return onSubmit()" method="post">

<div id="page_formulario">

<br />
<h1>Formulário</h1>
<table border="0">
	<tr>
		<td class="especial">Nome:</td>
		<td colspan="3"><input type="text" name="nome_da_pessoa" id="nome_da_pessoa" value="<?=htmlentities($p->nome)?>" class="igrande"></td>
	</tr>
	<tr>
		<td  class="especial">Sexo:</td>
		<td><input type="radio" name="sexo"  id="sexo" <?=$p->sexo=='m'?'checked="checked"':'' ?> value="m">Masculino <input type="radio" name="sexo" id="sexo" <?=$p->sexo!='m'?'checked="checked"':'' ?> value="f">Feminino</td>
        <td  class="especial">Data de Nascimento:</td>
		<td><input type="text" onkeyup="mascara(this,'00')" name="nascimento_dia" id="nascimento_dia" value="<?=$dia?>"  size="2" maxlength="2">/<input type="text" name="nascimento_mes" onkeyup="mascara(this,'00')" id="nascimento_mes" size="2" value="<?=$mes?>" maxlength="2">/<input type="text" name="nascimento_ano" onkeyup="mascara(this,'0000')" id="nascimento_ano" size="4" value="<?=$ano?>" maxlength="4"> <em>(dd/mm/aaaa)</em></td>
	</tr>
</table>

<hr/>

<table border="0">
	<tr>
		<td class="especial">Endereço:</td>
		<td colspan="3" style="width:300px"><input type="text" name="endereco" id="endereco" value="<?=$p->endereco->endereco?>" class="igrande" style="width:300px"></td>
		<td  class="especial">nº:</td>
		<td style="width:150px"><input type="text" value="<?=$p->endereco->numero?>" name="numero" id="numero" class="igrande"></td>
	</tr>
    <tr>
		<td  class="especial">Complemento:</td>
		<td style="width:150px"><input type="text" name="complemento" id="complemento" value="<?=$p->endereco->complemento?>" class="igrande"></td>
		<td  class="especial">Bairro:</td>
		<td style="width:150px"><input type="text" name="bairro" id="bairro" value="<?=$p->endereco->bairro?>" class="igrande"></td>
        <td  class="especial">Telefone:</td>
		<td style="width:150px"><input onkeyup="mascara(this,'(00) !0000-0000')" type="text" name="telefone" id="telefone" value="<?=$p->endereco->telefone?>" class="igrande" ></td>
	</tr>
	<tr>
		<td class="especial">Região:</td>
		<td colspan="5" style="width:300px">
			<?php comboregiao($p->endereco->regiao);?>
		</td>
	</tr>
</table>

<hr/>

<table border="0">
	<tr>
		<td class="especial">Nome da responsável:</td>
		<td style="width:400px"><input type="text" value="<?=$p->responsavel?>" name="nome_do_responsavel" id="nome_do_responsavel"  class="igrande"></td>
		<td  class="especial">E-mail:</td>
		<td style="width:200px"><input type="text" value="<?=$p->email?>" name="email_do_responsavel" id="email_do_responsavel" class="igrande" ></td>
	</tr>
</table>


<hr/>

<table border="0">
   	<tr>
		<td class="especial">1. Qual o tipo de deficiência ?</td>

        <?php
			$defs = DeficienciaDAL::getTodasExibiveis();
			
			for($i=0;$i<ceil(count($defs)/4);$i++){
				for($j=0;$j<4;$j++){
					$checked = "";
					for($k=0;$k<count($p->deficiencias);$k++){
						if($p->deficiencias[$k]->id == $defs[$i*4+$j]->id){
							$checked = "checked = 'checked'";
							break;
						}
					}
					if($i*4 + $j < count($defs)){
						echo "<td>";
						echo "<input type='checkbox' ".$checked;
						echo " name='d_".($i*4+$j)."'";
						echo " value='".$defs[$i*4+$j]->id."'>";
						echo $defs[$i*4+$j]->nome;
						echo " </td>";
					}
					else{
						echo '<td>&nbsp;</td>';
					}
				}
				if($i+1 < ceil(count($defs)/4)){
					echo '</tr>';
					echo '<tr>';
					echo '<td></td>';
				}
			}
		?>
	</tr>
    <tr>
        <td class="especial">Especifique:</td>
        <td colspan="5"><input type="text" name="especificacaoDeficiencia" id="especificacaoDeficiencia"  value="<?=$p->especificacaoDeficiencia ?>" class="igrande" ></td>
    </tr>
   	<tr>
		<td  class="especial">Tem autismo:</td>  
        <td><input type="radio" name="autismo" id="autismo" <?=$p->autista=='s' ?'checked="checked"':''?> value="s">sim </td>  
        <td colspan="4"><input type="radio" name="autismo" <?=$p->autista!='s' ?'checked="checked"':''?> id="autimos" value="n">não </td> 
    </tr>  
</table>

<hr />

<table border="0">
	<tr>
    	<td colspan="2"  class="especial">2. Realiza algum tratamento ou acompanhamento?</td>
        <td><input type="radio" id="c_box_acompanhamento_s" onclick="camposAcompanhamento()" name="acompanhamento" <?=$p->instituicao!==NULL ?'checked="checked"':''?> id="acompanhamento" value="s">sim</td>
        <td><input type="radio" id="c_box_acompanhamento_n" onclick="camposAcompanhamento()" name="acompanhamento" <?=$p->instituicao===NULL ?'checked="checked"':''?> id="acompanhamento" value="n">não</td>
    </tr>
	<tr id="acompanhamento_linha_sim">
    	<td  class="especial"> se sim, onde realiza ?</td>
        <?php
			$ins = InstituicaoDAL::getTodasExibiveis();
			$max = floor(count($ins)/3)+1;
			$wasSelected = false;
			for($i=0;$i<$max;$i++){
				for($j=0;$j<3;$j++){
					if($i*3 + $j < count($ins)){
						$checked = "";
						
						if($p->instituicao!==NULL){
							if($p->instituicao->id == $ins[$i*3+$j]->id){
								$checked = "checked='checked'";
								$wasSelected = true;
							}
						}
						
						echo "<td><input type='radio' $checked name='acompanhamento_onde' value='".$ins[$i*3+$j]->id."'>".$ins[$i*3+$j]->nome." </td>";
					}
					else{
						if($i*3 + $j-1 < count($ins) && $i+1 >= $max){
							?><td><input type="radio" <?=!$wasSelected?"checked='checked'":""?> name="acompanhamento_onde" id="acompanhamento_onde" value="outro">Outro local</td><?php
						}
						else{
							echo '<td>&nbsp;</td>';
						}
					}
				}
				if($i+1 < $max){
					echo '</tr>';
					echo '<tr id="acompanhamento_linha_sim">';
					echo '<td></td>'; 
				}
			}
		?>
        
    </tr>
    <tr id="acompanhamento_linha_sim">
    	<script type="text/javascript">
			function onSubmit(){
				var da = document.getElementById("nascimento_ano");
				var dm = document.getElementById("nascimento_mes");
				var dd = document.getElementById("nascimento_dia");
				var datual = new Date();

				if(da.value.replace(/\ */g,"") == "" || parseInt(da.value,10) <= 1900 || parseInt(da.value,10) > parseInt(datual.getFullYear()) ){
					alert("Ano Inválido");
					da.focus();
					return false;
				}
				else if(parseInt(dm.value,10) < 1 || parseInt(dm.value,10) > 12 ){
					alert("Mês Inválido");
					dm.focus();
					return false;
				}
				else if(dd.value.replace(/\ */g,"") == "" || parseInt(dd.value,10) < 1 || parseInt(dd.value,10) > 31 ){
					alert("Dia Inválido");
					dd.focus();
					return false;
				}

				return true; 
			}
			function getById(id){
				var a = [];
				var d = document.getElementsByTagName("*");
				for(var i=0;i<d.length;i++){
					if(typeof(d[i].id) != "undefined"){
						if(d[i].id == id){
							a[a.length] = d[i];
						}
					}
				}	
				return a;
			}
			function foreach(a,f){
				for(var i=0;i<a.length;i++){
					f(a[i],i);
				}
			}
			function camposAcompanhamento(){
				var cs = document.getElementById("c_box_acompanhamento_s");
				var cn = document.getElementById("c_box_acompanhamento_n");
				if(cs.checked){
					foreach(getById("acompanhamento_linha_nao"),function(o,i){
						o.style.visibility = "hidden";
					});
					foreach(getById("acompanhamento_linha_sim"),function(o,i){
						o.style.visibility = "visible";
					});
				}
				else{
					foreach(getById("acompanhamento_linha_nao"),function(o,i){
						o.style.visibility = "visible";
					});
					foreach(getById("acompanhamento_linha_sim"),function(o,i){
						o.style.visibility = "hidden";
					});
				}
			}
			function getSelectedOption(combo){
				for(var i=0;i<combo.options.length;i++){
					if(combo.options[i].selected){
						return combo.options[i];
					}
				}
				return null;
			}
			function removeOptions(combo){
				for(var i=combo.childNodes.length-1;i>=0;i--){
					combo.removeChild(combo.childNodes[i]);
				}
			}
			function atualizarCidades(){
				
				var e = document.getElementById("acompanhamento_estado");
				var c = document.getElementById("acompanhamento_cidade");
				c.disabled = true;
				var cp = <?=$cidade_instituicao?>;				
				var op = getSelectedOption(e);
				
				ajaxRequest({
					url:"../ajax/getcidades.php",
					params:{
						id_estado:op.value
					},
					method:"POST",
					success:function(r){
                        removeOptions(c);
						var re = unescape(r.responseText.replace(/\+/g," "));
						var arr = eval("("+ re +")");
						for(var i =0;i<arr.length;i++){//>
                            var op = document.createElement("option");
							op.value = arr[i].id;
							op.text = arr[i].nome;
                            if(arr[i].id == cp){
								op.selected = true;
							}
							c.appendChild(op);
							c.childNodes[c.childNodes.length-1].innerHTML=arr[i].nome;

						}
						c.disabled = false;
					}
				});
			}
		</script>
    	<td>Especifique: <input type="text" name="acompanhamento_especifique" value="<?=$especifique_instituicao?>" id="acompanhamento_especifique"  ></td>
        <td>Estado: 
        	<select name="acompanhamento_estado" id="acompanhamento_estado" onchange="atualizarCidades()" >
        		<?php
					$ufs = EstadoDAL::getTodos();
					foreach($ufs as $k=>$v){
						?><option <?=$estado_instituicao==$v->id?'selected="selected"':''?> value="<?=$v->id?>"><?=$v->nome?></option><?php 
					}
				?>
            </select>
        </td>
        <td colspan="2">Cidade: 
            <select name="acompanhamento_cidade" id="acompanhamento_cidade"  style="width:220px;">
            	<?php
					$cds = CidadeDAL::getTodasPorEstado($estado_instituicao);
					foreach($cds as $k=>$v){
						?><option <?=$cidade_instituicao==$v->id?'selected="selected"':''?> value="<?=$v->id?>"><?=$v->nome?></option><?php
					}
				?>
            </select>
        </td>
    </tr>
    <tr id="acompanhamento_linha_nao">
    	<td colspan="4"  class="especial">se NÃO faz nenhum tratamento ou acompanhamento, indique abaixo o motivo:</td>
    </tr>
	<?php
		$mts = MotivoTratamentoDAL::getTodosExibiveis();
		$checked = false;
		$nchecked = false;
		for($i=0;$i<ceil((count($mts)+2)/3);$i++){
			?><tr id="acompanhamento_linha_nao"><?php
			for($j=0;$j<3;$j++){
				if($i*3 + $j < count($mts)){
					if($p->motivoTratamento){

						
						$checked = false;
						if($p->motivoTratamento->id == $mts[$i*3+$j]->id){
						
							$checked = true;
							$nchecked = true;
						}
					}
					?><td <?=$j==2?'colspan="2"':''?>><input type="radio" <?=$checked?'checked="checked"':''?> name="nao_acompanhamento" id="nao_acompanhamento" value="<?=$mts[$i*3+$j]->id?>"><?=$mts[$i*3+$j]->nome?></td><?php
				}
				else if($i*3 + $j - 1 < count($mts)){

					?><td <?=$j==2?'colspan="2"':''?>><input type="radio" <?=!$nchecked?'checked="checked"':''?> name="nao_acompanhamento" id="nao_acompanhamento" value="outro">Outro</td><?php
				}
				else if($i*3 + $j - 2 < count($mts)){
					$id = "";
					$nome = "";
					if($p->motivoTratamento){
						if($p->motivoTratamento->exibir == 'n'){
							$nome = $p->motivoTratamento->nome;
							$id = $p->motivoTratamento->id;
						}
					}
					?><td <?=$j==2?'colspan="2"':''?>>Especifique: <input type="text" value="<?=htmlentities($nome)?>" name="acompanhamento_motivo" id="acompanhamento_motivo" class="igrande" > <input type="hidden" name="acompanhamento_especifique_id" value="<?=$id?>" /><?php
				}
				else{
					?><td <?=$j==2?'colspan="2"':''?>>&nbsp;</td><?php
				}
			}
			?></tr><?php
		}
	?>
</table>

<hr />

<table border="0">
	<tr>
    	<td  class="especial">3. Sabe ler e escrever? </td>
        <td><input type="radio" name="sabe_ler" id="c_box_ler_sim" onclick="camposSabeLer()" <?=$p->sabeLer() ? 'checked="checked"':""?> value="s">sim</td>
        <td colspan="2"><input type="radio" name="sabe_ler" id="c_box_ler_nao" onclick="camposSabeLer()" <?=!$p->sabeLer() ? 'checked="checked"':""?> id="sabe_ler" value="n">não</td>
    </tr>
    <tr id="escolaridade_linha_sim">
    	<td colspan="4"  class="especial">se SIM, qual o grau de escolaridade?</td>
    </tr>
    <script type="text/javascript">
		function camposSabeLer(){
			var c = document.getElementById("c_box_ler_sim");
			if(c.checked){
				foreach(getById("escolaridade_linha_sim"),function(o,i){
					o.style.visibility = "visible";
				});
			}
			else{
				foreach(getById("escolaridade_linha_sim"),function(o,i){
					o.style.visibility = "hidden";
				});
			}
		}
	</script>
	<?php
		$es = EscolaridadeDAL::getTodas();
		$checked = false;
		$schecked = false;
		for($i=0;$i<ceil(count($es)/4);$i++){
			?><tr id="escolaridade_linha_sim"><?php
			for($j=0;$j<4;$j++){
				if($i*4+$j < count($es)){
					if($p->sabeLer()){
						if($p->escolaridade->id == $es[$i*4+$j]->id){
							$checked = true;
							$schecked = true;
						}
						else if(!$schecked && $i*4+$j+1 >= count($es)){
							$checked = true;
							$schecked = true;
						}
					}
					?><td><input <?=$checked ? 'checked="checked"':""?> type="radio" name="escolaridade" id="escolaridade" value="<?=$es[$i*4+$j]->id?>"><?=$es[$i*4+$j]->nome?></td><?php
					$checked = false;
				}
				else{
					?><td>&nbsp;</td><?php
				}
			}
			?></tr><?php 
		}
	?>
	
	<tr>
    	<td  class="especial" >Está estudando atualmente? </td>
        <td><input type="radio" name="estudando" id="estudando" <?=$p->estaEstudando()?'checked="checked"':""?> value="s">sim</td>
        <td colspan="2"><input type="radio" name="estudando" <?=!$p->estaEstudando()?'checked="checked"':""?> id="estudando" value="n">não</td>
    </tr>
</table>

<hr />

<table border="0" >
	<tr>
    	<td  class="especial">4. Está trabalhando atualmente ?</td> 
        <td><input type="radio" id="c_box_trabalha_sim" onclick="camposTrabalha()" name="trabalha" <?=$p->estaTrabalhando()?'checked="checked"':""?> id="trabalha" value="s">sim</td>
        <td colspan="2"><input type="radio" <?=!$p->estaTrabalhando()?'checked="checked"':""?> onclick="camposTrabalha()" name="trabalha" id="trabalha" value="n">não</td>
    </tr>
	<tr id="trabalha_linha_nao">
        <td colspan="4"  class="especial">se NÃO, a pessoa:</td>
    </tr>
	<script type="text/javascript">
		function camposTrabalha(){
			var c = document.getElementById("c_box_trabalha_sim");
			if(c.checked){
				foreach(getById("trabalha_linha_nao"),function(o,i){
					o.style.visibility = "hidden";
				});
			}
			else{
				foreach(getById("trabalha_linha_nao"),function(o,i){
					o.style.visibility = "visible";
				});
			}
		}
	</script>
	<?php
		$mtt = MotivoTrabalhoDAL::getTodosExibiveis();
		$checked = false;
		$nchecked = false;
		for($i=0;$i<ceil((count($mtt)+1)/4);$i++){
			?><tr id="trabalha_linha_nao"><?php
			for($j=0;$j<4;$j++){
				if($i*4+$j<count($mtt)){
					if($p->motivoTrabalho){

						if($mtt[$i*4+$j]->id == $p->motivoTrabalho->id){
							$checked = true;
							$nchecked = true;
						} 
					}
					?><td><input type="radio" <?=$checked?'checked="checked"':''?> name="nao_trabalha" id="nao_trabalha" value="<?=$mtt[$i*4+$j]->id?>"><?=$mtt[$i*4+$j]->nome?></td><?php
				}
				else if($i*4+$j-1<count($mtt)){
					if(!$nchecked){
						$checked = true;
						$nchecked = true;
					}
					?><td><input type="radio" <?=$checked?'checked="checked"':''?> name="nao_trabalha" id="nao_trabalha" value="outro">Outros motivos</td><?php
				}
				else{
					?><td>&nbsp;</td><?php
				}
				$checked = false;
			}
			?></tr><?php
		}
	?>

</table>

<table border="0">
	<tr>
    	<td  class="especial">5. Deficiente possui renda?</td>
    	<td><input type="radio" onclick="camposPossuiRenda()" id="c_box_renda_sim" name="renda" <?=$p->possuiRenda()?'checked="checked"':""?> id="renda" value="s">sim </td>
        <td><input type="radio" onclick="camposPossuiRenda()" name="renda" <?=!$p->possuiRenda()?'checked="checked"':""?> id="renda" value="n">não </td>
        <?php
		if($p->possuiRenda()){
			$renda = $p->renda;
		}
		else{
			$renda = "";
		}
		?>
		<td style="width:200px" id="qual_renda">Qual: <input type="text" name="qual_renda" value="<?=$renda?>" class="igrande" ></td>
    </tr>
</table>
<script type="text/javascript">
	function camposPossuiRenda(){
		var c = document.getElementById("c_box_renda_sim");
		var r = document.getElementById("qual_renda");
		if(c.checked){
			r.style.visibility = "visible";
		}
		else{
			r.style.visibility = "hidden";
		}
	}
	camposPossuiRenda();
	camposTrabalha();
	camposSabeLer();
	camposAcompanhamento();
</script>
<script>

(function(){

var k = document.getElementById("nome_da_pessoa");
k.focus();
k.select();
})();

</script>
<br />

</div>

<input type="submit" style="margin-left:40px;" value="<?php echo ($c)?"Cadastrar":"Salvar";?>" /> <input type="reset" value="<?php echo ($c)?"Limpar":"Redefinir";?>" />
</form>
<br />

<?php
	include_once("../base/rodape.php");
?>
