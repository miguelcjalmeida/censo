<?php
function fetch($query){
    $a = array();
	while($o=mysql_fetch_object($query)){
		$a[] = $o;
	}
	return $a;
}
function getSimpleWhere($to){
	$s = "";
	$i = 0;
	foreach($to as $k=>$v){
		if(is_object($v)){
			if(strpos(($class=get_class($v)),"TO") == strlen($class)-2){
				if(isset($v->id)){
					if($v->id !== NULL && $v->id !== FALSE){
						if($i){ $s .= " AND ";}
						$s .= "`id_$k`='{$v->id}'";
					}
				}
			}
			$i++;
		}
		else if($v !== NULL && !is_array($v)){
			if(is_bool($v)){
				$valor = $v ? "s" : "n";
			} 
			else{
				$valor = $v;
			}
			if($i){ $s .= " AND ";}
			$s .= "`$k`='$valor'";
			$i++; 	
		}
	}
	return $s;
}

?>