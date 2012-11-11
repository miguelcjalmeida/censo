<?php
session_start();
	if(!(isset($_SESSION["usuario"]) and isset($_SESSION["usuario"]) and ($_SESSION["logado"] == true))){
		@session_destroy();
		//echo "não logado";
		header("location: ../logout.php");
	}else{
		$_SESSION["usuario"] = $_SESSION["usuario"];
		$_SESSION["logado"] = $_SESSION["logado"];
		$_SESSION["nome"] = $_SESSION["nome"];
	}
?>
<html>
<head>
<title>Censo de pessoas deficientes de Valinhos</title>
<meta http-equiv="Content-Type" content="text/html;">
<?php
include_once("../dao/all.php");
?>

<script language="JavaScript" src="../js/efeitos.js"></script>
<link rel="stylesheet" href="../css/estilo.css" type="text/css" />
<link rel="stylesheet" href="../css/grid.css" type="text/css" />
<link rel="stylesheet" href="../css/form.css" type="text/css" />
</head>
<body bgcolor="#ffffff" onLoad="MM_preloadImages('../imagens/layout_r2_c3_f2.jpg','../imagens/layout_r2_c4_f2.jpg','../imagens/layout_r2_c6_f2.jpg');">


<div id="page">

	<div id="nome">Logado como <b><?php echo $_SESSION["nome"] ; ?></b> ( <a href="../logout.php" class="sair">sair</a> )</div>	

<table border="0" cellpadding="0" cellspacing="0" width="950">

  <tr>
   <td><img src="../imagens/spacer.gif" width="320" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="168" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="105" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="102" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="9" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="118" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="101" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="27" height="1" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td rowspan="4"><img name="layout_r1_c1" src="../imagens/layout_r1_c1.jpg" width="320" height="207" border="0" alt=""></td>
   <td colspan="7"><img name="layout_r1_c2" src="../imagens/layout_r1_c2.jpg" width="630" height="84" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="1" height="84" border="0" alt=""></td>
  </tr>
  <tr >
   <td><img name="layout_r2_c2" src="../imagens/layout_r2_c2.jpg" width="168" height="44" border="0" alt=""></td>
   <td><a href="../listagem/index.php" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('layout_r2_c3','','../imagens/layout_r2_c3_f2.jpg',1);"><img name="layout_r2_c3" src="../imagens/layout_r2_c3.jpg" width="105" height="44" border="0" alt=""></a></td>
   <td colspan="2"><a href="../cadastrar/index.php" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('layout_r2_c4','','../imagens/layout_r2_c4_f2.jpg',1);"><img name="layout_r2_c4" src="../imagens/layout_r2_c4.jpg" width="111" height="44" border="0" alt=""></a></td>
   <td><a href="../relatorios/index.php" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('layout_r2_c6','','../imagens/layout_r2_c6_f2.jpg',1);"><img name="layout_r2_c6" src="../imagens/layout_r2_c6.jpg" width="118" height="44" border="0" alt=""></a></td>
   <td colspan="2"><img name="layout_r2_c7" src="../imagens/layout_r2_c7.jpg" width="128" height="44" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="1" height="44" border="0" alt=""></td>
  </tr>
  <tr class="fundo">
   <td rowspan="2" colspan="3"><img name="layout_r3_c2" src="../imagens/layout_r3_c2.jpg" width="375" height="79" border="0" alt=""></td>
   <td colspan="3"><img name="layout_r3_c5" src="../imagens/layout_r3_c5.jpg" width="228" height="37" border="0" alt=""></td>
   <td rowspan="2"><img name="layout_r3_c8" src="../imagens/layout_r3_c8.jpg" width="27" height="79" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="1" height="37" border="0" alt=""></td>
  </tr>
  <tr class="fundo">
   <td colspan="3"><img name="layout_r4_c5" src="../imagens/layout_r4_c5.jpg" width="228" height="42" border="0" alt=""></td>
   <td><img src="../imagens/spacer.gif" width="1" height="42" border="0" alt=""></td>
  </tr>
  <tr class="fundo">
   <td colspan="9">
	<div class="fundo">	
	<div id="conteudo">