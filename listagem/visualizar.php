<?php
	include_once("../base/topo.php");

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
		$p->motivoTratamento = new MotivoTO();
		$p->motivoTratamento->id = 1;
		$p->especificacaoDeficiencia = "";
		$especifique_instituicao = "";
		$cidade_instituicao = 9522;
		$estado_instituicao= 26;
	}
	
	function comboregiao($n = 0){
		echo '<select id="regiao" name="regiao" style="width:300px;">';
		$q = mysql_query('SELECT * FROM `regiao`');
		echo '<option value="0" ></option>';
		while($o = mysql_fetch_object($q)){
			echo '<option value="'. $o->id .'" '.( ($o->id == $n)?'selected="selected"':'' ).' >'. $o->nome .'</option>';
		}
		echo '</select>';
	}
?>
<style>
   .especial{
    font-weight: bold;
    font-size: 12px !important;
    font-family: Arial;
   }
</style>
<div id="page_formulario">

<br />
<h1><?=htmlentities($p->nome)?></h1>
<table border="0">
	<tr>
		<td  class="especial">Sexo:</td>
		<td><?=$p->sexo=='m'?'Masculino':'Feminino' ?></td>
        <td  class="especial">Data de Nascimento:</td>
		<td><?=$dia . '/' . $mes . '/' . $ano?></td>
	</tr>
</table>

<table border="0">
	<tr>
		<td class="especial">Endereço:</td>
		<td colspan="3" style="width:300px"><?=$p->endereco->endereco?></td>
		<td  class="especial">nº:</td>
		<td style="width:150px"><?=$p->endereco->numero?></td>
	</tr>
    <tr>
		<td  class="especial">Complemento:</td>
		<td style="width:150px"><?=$p->endereco->complemento?></td>
		<td  class="especial">Bairro:</td>
		<td style="width:150px"><?=$p->endereco->bairro?></td>
        <td  class="especial">Telefone:</td>
		<td style="width:150px"><?=$p->endereco->telefone?></td>
	</tr>
	<tr>
		<td class="especial">Região:</td>
		<td colspan="5" style="width:300px">
			<?php echo $p->endereco->regiao ;?>
		</td>
	</tr>
</table>


<table border="0">
	<tr>
		<td class="especial">Nome da responsável:</td>
		<td style="width:400px"><?=$p->responsavel?></td>
		<td  class="especial">E-mail:</td>
		<td style="width:200px"><?=$p->email?></td>
	</tr>
</table>

<table border="0">
   	<tr>
		<td class="especial">1. Qual o tipo de deficiência ?</td>

        <?php
			$defs = DeficienciaDAL::getTodasExibiveis();
			
			for($i=0;$i<ceil(count($defs)/4);$i++){
				for($j=0;$j<4;$j++){
					//$checked = "";
					$checked = false;
					for($k=0;$k<count($p->deficiencias);$k++){
						if($p->deficiencias[$k]->id == $defs[$i*4+$j]->id){
							//$checked = "checked = 'checked'";
							$checked = true;
							break;
						}
					}
					if($i*4 + $j < count($defs)){
						//echo "<td><input type='checkbox' ".$checked." name='d_".($i*4+$j)."' value='".$defs[$i*4+$j]->id."'>".$defs[$i*4+$j]->nome." </td>";
                        if($checked){
                             echo '<td style="font-size: 14px;">' . $defs[$i*4+$j]->nome . '</td>';
                        }else{
                        
                        }
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
		<td  class="especial">Especificação:</td>
        <td colspan="5"><?=$p->especificacaoDeficiencia?></td>
    </tr>
   	<tr>
		<td  class="especial">Tem autismo:</td>  
        <td colspan="5"><?=$p->autista=='s' ?'SIM, tem Autismo.':'NÃO.'?></td>
    </tr>  
</table>

<table border="0">
	<tr>
    	<td colspan="2"  class="especial">2. Realiza algum tratamento ou acompanhamento?</td>
        <td colspan="2"><?=$p->instituicao!==NULL ?'SIM':'NÃO'?></td>
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
						
						echo "<td><input disabled='disabled' type='radio' $checked name='acompanhamento_onde' value='".$ins[$i*3+$j]->id."'>".$ins[$i*3+$j]->nome." </td>";
					}
					else{
						if($i*3 + $j-1 < count($ins) && $i+1 >= $max){
							?><td><input disabled='disabled' type="radio" <?=!$wasSelected?"checked='checked'":""?> name="acompanhamento_onde" id="acompanhamento_onde" value="outro">Outro local</td><?php
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
    	<td><b>Especifique:</b> <?=$especifique_instituicao?></td>
        <td><b>Estado:</b>

        		<?php
					$ufs = EstadoDAL::getTodos();
					foreach($ufs as $k=>$v){
						echo ($estado_instituicao==$v->id)?$v->nome:'' ;
					}
				?>

        </td>
        <td colspan="2">Cidade: 

            	<?php
					$cds = CidadeDAL::getTodasPorEstado($estado_instituicao);
					foreach($cds as $k=>$v){
						echo ($cidade_instituicao==$v->id)?$v->nome:'' ;
					}
				?>

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
					?><td <?=$j==2?'colspan="2"':''?>><input  disabled='disabled' type="radio" <?=$checked?'checked="checked"':''?> name="nao_acompanhamento" id="nao_acompanhamento" value="<?=$mts[$i*3+$j]->id?>"><?=$mts[$i*3+$j]->nome?></td><?php
				}
				else if($i*3 + $j - 1 < count($mts)){

					?><td <?=$j==2?'colspan="2"':''?>><input  disabled='disabled' type="radio" <?=!$nchecked?'checked="checked"':''?> name="nao_acompanhamento" id="nao_acompanhamento" value="outro">Outro</td><?php
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
					?><td <?=$j==2?'colspan="2"':''?>>Especifique: <?=htmlentities($nome)?><?php
				}
				else{
					?><td <?=$j==2?'colspan="2"':''?>>&nbsp;</td><?php
				}
			}
			?></tr><?php
		}
	?>
</table>


<table border="0">
	<tr>
    	<td  class="especial">3. Sabe ler e escrever? </td>
        <td colspan="3"><?=$p->sabeLer() ? 'SIM':"NÃO" ?></td>

    </tr>
    <tr id="escolaridade_linha_sim">
    	<td colspan="4"  class="especial">se SIM, qual o grau de escolaridade?</td>
    </tr>
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
					?><td><input disabled='disabled' <?=$checked ? 'checked="checked"':""?> type="radio" name="escolaridade" id="escolaridade" value="<?=$es[$i*4+$j]->id?>"><?=$es[$i*4+$j]->nome?></td><?php
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
        <td colspan="3"><?=$p->estaEstudando()?'SIM':'NÃO'?></td>
    </tr>
</table>



<table border="0" >
	<tr>
    	<td  class="especial">4. Está trabalhando atualmente ?</td> 
        <td colspan="3"><?=$p->estaTrabalhando()?'SIM':'NÃO'?></td>
    </tr>
	<tr id="trabalha_linha_nao">
        <td colspan="4"  class="especial">se NÃO, a pessoa:</td>
    </tr>

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
					?><td><input disabled='disabled' type="radio" <?=$checked?'checked="checked"':''?> name="nao_trabalha" id="nao_trabalha" value="<?=$mtt[$i*4+$j]->id?>"><?=$mtt[$i*4+$j]->nome?></td><?php
				}
				else if($i*4+$j-1<count($mtt)){
					if(!$nchecked){
						$checked = true;
						$nchecked = true;
					}
					?><td><input disabled='disabled' type="radio" <?=$checked?'checked="checked"':''?> name="nao_trabalha" id="nao_trabalha" value="outro">Outros motivos</td><?php
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
    	<td><?=$p->possuiRenda()?'SIM':'NÃO'?></td>
        <?php
		if($p->possuiRenda()){
			$renda = $p->renda;
		}
		else{
			$renda = "";
		}
		?>
		<td style="width:200px" id="qual_renda"><b>Qual:</b> <?=$renda?></td>
    </tr>
</table>

<br />

</div>

<br />

<?php
	include_once("../base/rodape.php");
?>
