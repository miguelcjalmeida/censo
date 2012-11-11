<?php
$dbhost = "localhost";
$dbname = "censo";
$dbuser = "root";
$dbpass = "";
if(!@mysql_connect($dbhost,$dbuser,$dbpass)){
	if(!@mysql_connect($dbhost,'root',''))
	echo 'Erro ao criar conexão com banco de dados, linha: ' . __LINE__ . ' ' . __FILE__;
}
if(!@mysql_select_db($dbname)){
	echo 'Erro ao selecionar banco de dados, linha: ' . __LINE__ . ' ' . __FILE__;
}

?>