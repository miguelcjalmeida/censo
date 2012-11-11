<?php
include_once("../dao/all.php");

PacienteDAL::apagar((int) $_GET['id']);

header("location:../listagem/index.php");

?>