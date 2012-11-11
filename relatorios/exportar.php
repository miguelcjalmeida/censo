<?php
  session_start();
  $nome = 'relatorio';
  $nome.= date('YmdHis');

  header('Content-type: application/vnd.ms-excel');
  header('Content-type: application/force-download');
  header('Content-Disposition: attachment; filename='. $nome .'.xls');
  header('Pragma: no-cache');

  function out($str){
     if($str=='') return '&nbsp;';
     return $str;
  }
  
  $sql = $_SESSION['sql_relatorio'];
  
  include_once("../dao/connect.php");
  include_once("../dao/all.php");
  include_once("temp.php");

  $query = mysql_query($sql);
  echo '<h1>Relátorio</h1>';
  
  echo '<style>
    .header{
      padding: 5px 10px 5px 10px;
      background: #CCCCCC;
      height: 30px;
    }
    
    td{
      border: 1px solid #000;
    }
  </style>';

  echo '<table style="border: 1px solid #000;">';
  echo $header;

  while($row = mysql_fetch_row($query)){
    $paciente = PacienteDAL::getPorId($row[0]);
    echo '<tr>';
    echo "<td>".out($paciente->id)."</td>";
    echo "<td>".out($paciente->nome)."</td>";
    echo "<td>".out($paciente->sexo)."</td>";
    echo '<td>'.date("d/m/Y",strtotime($paciente->nascimento)).'</td>';
    echo "<td>".out($paciente->endereco->endereco)."</td>";
    echo "<td>".out($paciente->endereco->numero)."</td>";
    echo "<td>".out($paciente->endereco->complemento)."</td>";
    echo "<td>".out($paciente->endereco->bairro)."</td>";
    echo "<td>".out($paciente->endereco->telefone)."</td>";
    echo "<td>".out($paciente->endereco->regiao)."</td>";
    echo "<td>".out($paciente->responsavel)."</td>";
    echo "<td>".out($paciente->email)."</td>";
    echo '<td>'.($paciente->temDeficiencia(1)?'S':'N').'</td>';
    echo '<td>'.($paciente->temDeficiencia(2)?'S':'N').'</td>';
    echo '<td>'.($paciente->temDeficiencia(3)?'S':'N').'</td>';
    echo '<td>'.($paciente->temDeficiencia(4)?'S':'N').'</td>';
    echo '<td>'.($paciente->especificacaoDeficiencia).'</td>';
    echo '<td>'.out($paciente->instituicao->autista?'S':'N').'</td>';
    echo "<td>".out($paciente->instituicao->nome)."</td>";
    echo "<td>".out($paciente->motivoTratamento->nome)."</td>";
    echo "<td>".out($paciente->escolaridade->nome)."</td>";
    echo '<td>'.($paciente->estaEstudando() ? "S":"N").'</td>';
    echo "<td>".($paciente->motivoTrabalho ? "N" : "S")."</td>";
    echo "<td>".out($paciente->motivoTrabalho->nome)."</td>";
    echo "<td>".out($paciente->renda)."</td>";
    echo '</tr>';
  }
  echo '</table>';
?>

