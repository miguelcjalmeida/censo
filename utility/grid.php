<?php
session_start();

include_once("functions.php");
include_once("tag.php");
include_once("date.php");


class Grid{
   protected $name;
   protected $sql;
   protected $query;
   protected $queryTotal;
   protected $header;
   protected $pagAtual;
   protected $pagMax;
   protected $pagTotal;
   protected $rowNum;
   protected $filtrotipo;
   protected $filtropalavra;
   protected $msg;
   protected $format;
   protected $column;
   protected $searchFilter;
   protected $stripe;
   protected $pagName;
   protected $hTitle;
   protected $lineformat;
   protected $ordColumn;
   protected $hideColumn;
   protected $functions;
   private $colhtml;
   protected $histfil;
   protected $acao;
   protected $titles;
   protected $cols;   
   
   private function getTotalQuery($sql){
      $sql = str_replace("%ord","''",$sql);
      if($this->getGetParam("filtro") == ""){
         $sql = str_replace("%filtro","''",$sql);
         $sql = str_replace("%palavra","",$sql);
      }
      else{
         $sql = str_replace("%filtro",$this->getGetParam("filtro"),$sql);
         $sql = str_replace("%palavra",$this->getGetParam("palavra"),$sql);
      }
      return $sql;
   } 
   public function prepareHistQuery($sql){
      //if($this->hist){
	  $pos = strrpos(strtolower($sql),"where")+5;
	  if($pos){
	  	 $c = $this->getGetParam("filtro");
		 $p = $this->getGetParam("palavra");
		 $s = "";
		 $i=0;
		 foreach($this->histfil as $k=>$v){
		    if(++$i < sizeof($this->histfil)){
				if($v[2]){
					$date = new Date($v[1],"date");
					$v[1] = $date->toSqlDate();
					$date = new Date($v[2],"date");
					$v[2] = $date->toSqlDate();
				    $s .= " ".$v[0]." >= '".$v[1]."' and ".$v[0]." <= '".$v[2]."' and";
				}
				else{
					$s .= " ".$v[0]." LIKE '%" . $v[1] . "%' and";
		 		}
			}
		 }
		 $sql = $this->insertString($sql,$pos,$s);
	  }
	  return $sql;
   }
   public static function insertString($s,$i,$s2){
      $sub1 = substr($s,0,$i);
	  $sub2 = substr($s,$i,strlen($s));
	  return $sub1 . $s2 . $sub2;
   }	
   public static function getSessionQuery($n="last_total_query"){ 
   	  if($n == "last_total_query"){
	  	 return $_SESSION[$n];
	  }
	  return $_SESSION[$n . "_total_query"];
   }
   public function setFailMsg($m){
   	  $this->msg->set("innerHTML",$m);
   }
   private function myparsedate($date){
           return implode("-",array_reverse(explode("/",$date)));

   }
   private function prepareSqlQueryExtraParam($sql){
   	  if($this->getGetParam("palavra2")){
		  $pos = strrpos(strtolower($sql),"where")+5;
		  if($pos){
			 $c = $this->getGetParam("filtro");

             $p1 = $this->myparsedate($this->getGetParam("palavra"));
			 $p2 = $this->myparsedate($this->getGetParam("palavra2"));

             $p3 = " $c >= '$p1' and $c <= '$p2' and";
			 $sql = $this->insertString($sql,$pos,$p3);
			 $_GET[$this->name."palavra"] = "";
		  }
	  }
	  return $sql;
   }
   
   public function getHistFilArray(){
      $a = array();
	  $s = addslashes($this->getGetParam("hist"));
	  if(strlen($s) > 1){
	  	 $arr = split(",",$s);
		 for($i=0;$i<sizeof($arr);$i++){
		    $a[$i] = split("\|",$arr[$i]);
		 }
	  }
	  return $a;
   }
   public function getHistFilString(){
      $c = "";
	  $i=0;
	  foreach($this->histfil as $k=>$v){
	     $c .= $v[0]."|".$v[1]."|".$v[2];
	  	 if(++$i < sizeof($this->histfil)){
		    $c .= ",";
		 }
	  }
	  return $c;
   }
   
   public function Grid($name,$sql,$pagMax=10,$pagName="index.php"){
      $this->sql = $sql;
      $this->name = $name;
	  $this->titles = array();
	  $this->acao = $this->getGetParam("acao");
	  
	  if($this->acao == "Filtrar"){
	  	  $_GET[$this->name."pg"] = 1;
	      $this->histfil = $this->getHistFilArray();
		  $a = array($this->getGetParam("filtro"),$this->getGetParam("palavra"),$this->getGetParam("palavra2"));
		  $t = false;
		  for($i=0;$i<sizeof($this->histfil);$i++){
			  if($a[0] == $this->histfil[$i][0]){
			      if($a[1] == $this->histfil[$i][1]){
				      $t=true;
					  break;  
				  }
			  }
		  }
		  if(!$t){
	  	      array_push($this->histfil,$a);
	  	  }
	  }
	  else if($this->acao == "Novo Filtro"){
	      $_GET[$this->name."pg"] = 1;
	      $this->histfil = array();
		  $a = array($this->getGetParam("filtro"),$this->getGetParam("palavra"),$this->getGetParam("palavra2"));
		  array_push($this->histfil,$a);
	  }
	  else if($this->acao == "ordenar"){
	      $this->histfil = $this->getHistFilArray();
	  }
	  else{
	      $this->histfil = array();    
	  }
	  $this->cols = array();
      $this->functions = array();
	  $this->pagName = $_SERVER['PHP_SELF'];//$pagName;
      $this->pagAtual = ($this->getGetParam("pg")==""?1:$this->getGetParam("pg"));
      $this->pagMax = $pagMax;
      $this->pagMax = isset($_GET[$this->name . 'pagMax'])?$_GET[$this->name . 'pagMax']:$pagMax;
	  if(!$this->pagMax){
	      $this->pagMax = $pagMax;
	  }
	  if($this->pagMax < 1){
	      $this->pagMax = 1;
	  }
	  $pagMax = $this->pagMax;
	  $this->filtrotipo = $this->getGetParam("filtro");
      $this->filtropalavra = $this->getGetParam("palavra");
	  $sql = $this->prepareSqlQueryExtraParam($sql);
	  $sql = $this->prepareHistQuery($sql);
	  $this->sql = $sql;
      $t = $this->getTotalQuery($sql);
	  $_SESSION["last_total_query"] = $t;
	  $_SESSION["$name"."_total_query"] = $t;	  
	  $this->queryTotal = mysql_query($t);
      $this->ordColumn = array();
	  $this->prepareSql();
	  $this->hideColumn = array();
	  $this->searchFilter = array();
	  $this->colhtml = array();
	  $this->lineFormat = '';
	  
      $this->rowNum = mysql_num_rows($this->queryTotal);
      $this->pagTotal = ceil(mysql_num_rows($this->queryTotal)/$this->pagMax);
      $this->query = mysql_query($this->sql . " Limit ".($this->pagAtual-1)*$pagMax.",$pagMax");
      $this->header = array();
      $this->hTitle = array();
	  $this->format = array();
      $this->column = array();
      $this->stripe = true;
	  
      for($i=0,$n=mysql_num_fields($this->query);$i<$n;$i++){
	     $this->hideColumn[$i] = false;
		 $this->searchFilter[$i] = array("input","");
         $this->ordenacao = mysql_field_name($this->query,$i);
         if($this->ordenacao == $this->getGetParam("ord")){
            $this->ordenacao .= "d";
            $this->ordColumn[sizeof($this->ordColumn)] = $this->ordenacao;
		 }
         else if($this->ordenacao == $this->getGetParam("ord") . "d"){
            $this->ordenacao = strcut($this->ordenacao,0,strlen($this->ordenacao));
			$this->ordColumn[sizeof($this->ordColumn)] = $this->ordenacao;
         }
		 else{
		    $this->ordColumn[sizeof($this->ordColumn)] = $this->ordenacao;
		 }
         $this->header[$i] = new Tag("th","innerHTML",
            '<a href="'.getFileName($this->pagName).'?'.$this->getUrlParams($this->pagAtual).'">'.mysql_field_name($this->query,$i).'</a>'
         );
		 $this->cols[$i] = mysql_field_name($this->query,$i);
		 $this->titles[$i] = mysql_field_name($this->query,$i);
		 $this->header[$i]->set("class","rowTitle");
		 $this->colhtml[$i] = new Tag("td");
         $this->format[$i] = array("none");
		 $this->hideSearchOption[$i] = "visible";
	  }
	  
      $this->ordenacao = $this->getGetParam("ord");
      $this->msg = new Tag("span","innerHTML","Não há nenhum ítem a ser exibido");
      $this->msg->set("class","noitemmsg");
	 
   }
   public function getColumn($i){
   	  if(isset($this->colhtml[$i])){
	  	 return $this->colhtml[$i];
	  }
	  return new Tag("td");
   }
   public function getNumRows(){
      return $this->rowNum;
   }
   public function getNumCols(){
      return sizeof($this->header);
   }
   function setLineFormat($format){
      $this->lineFormat = $format;
   }
   function setHeaderTitle($index,$title){
      if(isset($this->header[$index])){
		  $temp = $this->ordenacao;
		  $this->ordenacao = $this->ordColumn[$index];
		  $this->header[$index]->set("innerHTML",'<a href="'.getFileName($this->pagName).'?'.$this->getUrlParams($this->pagAtual).'">'.$title.'</a>');
		  $this->ordenacao = $temp;
		  $this->titles[$index] = $title;
   	  }
   }
   function setStripe($bool){
      $this->stripe = $bool;
   }
   function getGetParam($get){
      if(isset($_GET[$this->name.$get]))
         return addslashes($_GET[$this->name.$get]);
      return "";
   }
   function getColumnName($i){
      $table = mysql_field_table($this->queryTotal,$i);
	  $name = mysql_field_name($this->queryTotal,$i);
	  if($table){
	      return $table . "." . $name;
	  }
	  return $name;
   }
   function getOrdenationQuery($ord){
      for($i=0;$i<mysql_num_fields($this->queryTotal);$i++){
         $name = mysql_field_name($this->queryTotal,$i);
         if($name == $ord){
            return $this->getColumnName($i);
         }
         else if($name . "d" == $ord){
            return $this->getColumnName($i) . " Desc";
         }
      }
      return $this->getColumnName(0);
   }
   function hideSearchOption($index){
   	  $this->hideSearchOption[$index] = "hidden"; 
   }
   function getFilterQuery($filter){
      for($i=0;$i<mysql_num_fields($this->queryTotal);$i++){
         $name = mysql_field_name($this->queryTotal,$i);
         $table = mysql_field_table($this->queryTotal,$i);
         if($this->getColumnName($i) == $filter){
            return $filter;
         }
      }
      return $this->getColumnName(0);
   }
   function prepareSql(){
      $this->sql = str_replace("%ord",$this->getOrdenationQuery($this->getGetParam("ord")),$this->sql);
      $this->sql = str_replace("%filtro",$this->getFilterQuery($this->getGetParam("filtro")),$this->sql);
      $this->sql = str_replace("%palavra",$this->getGetParam("palavra"),$this->sql);
   }
   function getHeader($index){
      return $this->header[$index];
   }
   function setHeader($index,Tag $tag){
      $this->header[$index] = $tag;
   }
   function setHideColumn($index,$b=true){
      $this->hideColumn[$index] = $b;
	  $this->hideSearchOption[$index] = "hidden";
   }   
   function getOtherGets(){
      $i = 0;
      $max = sizeof($_GET);
      $ret = "";
      foreach($_GET as $key=>$value){
         if($this->name."pg"!=$key && $this->name."filtro"!=$key && $this->name."palavra"!=$key && $this->name."ord"!=$key && $this->name."acao" != $key){
			$ret .= "&";
            $ret .= $key . "=" . $value;
         }
         $i++;
      }
	  
      return $ret;
   }
   function getUrlParams($pg){
      $n = $this->name;
      return $n."pg=$pg&".$n."filtro=".$this->filtrotipo."&".$n."palavra=".$this->filtropalavra."&".$n."ord=".$this->ordenacao.$this->getOtherGets()."&".$n."acao=ordenar&".$n."hist=".$this->getHistFilString();
   }
   function getClassOf($str){
      if($str == "primeira"){
         if($this->pagAtual != 1)
            return '<a class="enabled" href="'.getFileName($this->pagName).'?'.$this->getUrlParams(1).'">Primeira</a>';
         return '<a class="disabled">Primeira</a>';
      }
      else if($str == "anterior"){
         if($this->pagAtual-1 > 0)
            return '<a class="enabled" href="'.getFileName($this->pagName).'?'.$this->getUrlParams($this->pagAtual-1).'">Anterior</a>';
         return '<a class="disabled">Anterior</a>';
      }
      else if($str == "ultima"){
         if($this->pagAtual < $this->pagTotal)
            return '<a class="enabled" href="'.getFileName($this->pagName).'?'.$this->getUrlParams($this->pagTotal).'">Última</a>';
         return '<a class="disabled">Ultima</a>';
      }
      else{
         if($this->pagAtual+1 <= $this->pagTotal)
            return '<a class = "enabled" href="'.getFileName($this->pagName).'?'.$this->getUrlParams($this->pagAtual+1).'">Próxima';
         return '<a class="disabled">Próxima</a>';
      }
      return "disabled";
   }
   function formatString($str,$i,$opt=0,$arr=""){
	  if(isset($this->format)){
	     switch($this->format[$i][0]){
            case "date":{
               return date($this->format[$i][1],strtotime($str));
               break;
            }
            case "money":{
               $ret = "";
               if($str < 0) $ret .= "-";
               $ret .= $this->format[$i][1] . number_format(abs($str),2,',','');
               return $ret;
            }
            case "bool":{
			   if($str == "0" || $str == false){
			      return $this->format[$i][2];
			   }
			   else{
			   	  return $this->format[$i][1];
			   }
			}
			case "coalesce":{
				if($str){
					return $str;
				}
				return $this->format[$i][1];
			}
			case "iequal":{
			   if(strtolower($str) == strtolower($this->format[$i][1])){
			      return $this->format[$i][2];
			   }
			   else{
			   	  return $this->format[$i][3];
			   }
			}
			case "iequalornull":{
			   if(strtolower($str) == strtolower($this->format[$i][1]) || !strtolower($str)){
				  return $this->format[$i][2];
			   }
			   else{
			      
			   	  return $this->format[$i][3];
			   }
			}
			case "img":{
				return str_replace("%url",$str,$this->format[$i][1]);
			}
			case "createimg":{
				return "<img src = '".$str."' width='".$this->format[$i][1]."' height='".$this->format[$i][2]."' />";
			}
			case "imgresize":{ 
				if(!(strpos(trim($str),"http://") === 0)){
				   $str = base()."/ajax/" . $str;
				}
				$fp = fopen($str,"b");
				if(!$fp){
					return $this->format[$i][3];
				}
				return "<img alt='".$this->format[$i][3]."' width='".$this->format[$i][1]."' height='".$this->format[$i][2]."' src='".base()."/player/ajax/imageresize.php?".
				"image=".$str.
				"&width=".$this->format[$i][1].
				"&height=".$this->format[$i][2].
				"' border='0' class='".$this->name."imgresize' />";
			}
			case "replace":{
			    return str_replace("($replace)",$str,$this->format[$i][1]);
			}
            case "function":{  
			    if(!isset($this->format[$i][2])){    
			    	if(function_exists($this->format[$i][1]))
					return eval('return '.$this->format[$i][1] . '($str,$opt,$arr,"","","");');
				}
				else{
					if(function_exists($this->format[$i][1]))
					return eval('return '.$this->format[$i][1] . '($str,$opt,$arr,$this->format[$i][2]);');
				}
            }
			case "arrayformat":{
			    $format = $this->format[$i][1];
				$al = $this->format[$i][2];
				$s = "";
				foreach($al as $k=>$v){
				   if(isset($arr[$v])){
				      if($arr[$v]!=""){
					     $t = str_replace("%%value",$arr[$v],$format);
				         $s .= $t;
					  }
				   }
				   else{
				      foreach($this->cols as $k2=>$v2){
					     if($v2 == $v){
						 	$t = str_replace("%%value",$arr[$k2],$format);
				         	$s .= $t;
							break;
						 } 
					  }
				   }
				}
				return $s;
			}
			case "switch":{
				
				foreach($this->format[$i] as $k=>$v){
					if($k===$str){
						return $v;
					}
				}
				return isset($this->format[$i]["default"]) ? $this->format[$i]["default"] : $str;
			}
			case "column":{
				$al = $this->cols;
				$s = $this->format[$i][1];
				foreach($al as $k=>$v){
				   $s = str_replace("%%col[".$k."]",$arr[$k],$s);
				   $s = str_replace("%%col[".$v."]",$arr[$k],$s);
				}
				return $s;
			}
            default:{
               return $str;
               break;
            }
         }
      }
      return $str;
   }
   function codePage(){
      $retorno = "<table>";
      $retorno .= "<tr>";
      $retorno .= '<td>'.$this->getClassOf("primeira").'</td>';
      $retorno .= '<td>'.$this->getClassOf("anterior").'</td>';
      $retorno .= "<td>";
      
	  for($i=$this->pagAtual-(floor(10/2));$i<=(10/2)+$this->pagAtual && $i<=$this->pagTotal;$i++){
         if($i>0){
            if($i != $this->pagAtual){
               $retorno .= '<a class = "pagina" href="'.getFileName($this->pagName).'?'.$this->getUrlParams($i).'">'.$i."</a>";
            }
            else{
               $retorno .= '<span class = "paginaatual">'.$i.'</span>';
            }

         }
      }
	  
      $retorno .= "</td>";
      $retorno .= '<td>'.$this->getClassOf("proxima").'</td>';
      $retorno .= '<td>'.$this->getClassOf("ultima").'</td>';
      $retorno .= "</tr>";
      $retorno .= "</table>";
      return $retorno;
   }
   function createPage(){
      echo $this->codePage();
   }
   function getStripeClass($i){
      if($this->stripe){
         return "row".($i%2==0?"":"2");
      }
      return "row";
   }

   function codeGrid(){
      $retorno = "";
      if($this->rowNum > 0){
      $retorno .= '<table id = "'.$this->name.'gridTable" class = "gridTable" width = "100%">';
      $retorno .= "<tr class='rowTitle'>";
      for($i=0,$a=sizeof($this->header);$i<$a;$i++){
         if(!$this->hideColumn[$i]){
		 	$retorno .= $this->header[$i]->toString();
		 }
      }
      $retorno .= "</tr>";
      for($j=0,$n=mysql_num_rows($this->query);$j<$n;$j++){
		 $arr = mysql_fetch_row($this->query);
		 
		
         if($this->lineFormat == ''){
		 	$retorno .= "<tr class = '".$this->getStripeClass($j)."' >";
		 }
		 else{
		    $metodo = $this->lineFormat;
		 	$retorno .= $metodo(new Tag("tr","class",$this->getStripeClass($j),false),$arr,$j);
		 }
		 for($i=0;$i<sizeof($arr);$i++){
            if(!$this->hideColumn[$i]){
				
				$str= $this->formatString($arr[$i],$i,$j,$arr);
				
				if($str !== 0 && !$str){
				   $str .= "&nbsp;";
				}
				
				$this->colhtml[$i]->set("innerHTML",$str); 
				$retorno .= $this->colhtml[$i]->tostring();
				
			}
         }
		 
         for($i=0;$i<sizeof($this->column);$i++){
            if(!$this->hideColumn[$i]){
				$str = $this->column[$i];//->toString();
				for($k=0;$k<mysql_num_fields($this->queryTotal);$k++){
				   $str->set("innerHTML",str_replace("%".mysql_field_name($this->queryTotal,$k),$arr[$k],$str->get("innerHTML")));
				}
				$str->set("innerHTML",str_replace("%pagatual",$this->pagAtual,$str->get("innerHTML")));
				$str->set("innerHTML",$this->formatString($str->get("innerHTML"),$i+sizeof($arr),$j,$arr));
				$retorno .= $str->toString();
			}
         }
         $retorno .= "</tr>";
      }
      $retorno .= "</table>";
      }
      else{
         $retorno .= $this->msg->toString();
      }
      return $retorno;
   }
   function createGrid(){
      echo $this->codeGrid();
   }
   function strcut($s,$i){
   	  $r="";
	  for(;$i<strlen($s);$i++){
	  	 $r .= $s[$i];
	  }
	  return $r;
   }
   function codeExcel(){
	  return "";
   }
   /*code search filters */
   private function getCombobox($c){
      $r  = "<select style='width:148px' name='$this->name"."palavra'>";
	  for($i=0;$i<sizeof($this->searchFilter[$c][1]);$i++){
	  	 $r .= "<option value='".$this->searchFilter[$c][1][$i][0]."'>".$this->searchFilter[$c][1][$i][1]."</option>";
	  }
	  $r .= "</select>";
	  return $r;
   }
   private function getTextfield($i){
      return "<input id='' name = '".$this->name."palavra' class = 'searchField' type = 'text' value ='".htmlentities($this->getGetParam("palavra"))."' />";
   }
   private function getDatefield($i){
      //$i1 = new InputDate("$this->name"."palavra",date("d/m/Y"));
	  //$i2 = new InputDate("$this->name"."palavra2",date("d/m/Y"));
	  
	  $s =  "<input style='width:70px;' onkeypress='formatGridDateField(this)' id='date_".$this->name."palavra' maxlength = '10' class = 'searchField' type = 'text' value ='' />";
	  $s .= "&nbsp; até &nbsp;";
	  $s .= "<input style='width:70px;' onkeypress='formatGridDateField(this)' id='date_".$this->name."palavra2' maxlength = '10' class = 'searchField' type = 'text' value ='' />";
	  
	  $s .=  "<input type='hidden' id='date_hidden_".$this->name."palavra' name='".$this->name."palavra' value ='' />";
	  $s .=  "<input type='hidden' id='date_hidden_".$this->name."palavra2' name='".$this->name."palavra2' value ='' />";
	  
	  
	  return $s;
   }
   private function getTimefield($i){
   }
   private function getDateTime($i){
        
   }
   private function getSearchFilter($i){
   	  if($this->searchFilter[$i][0] == "combobox"){
	     return $this->getCombobox($i);
	  }
	  else if($this->searchFilter[$i][0] == "date"){
	  	 return $this->getDatefield($i);
	  }
	  else if($this->searchFilter[$i][0] == "datetime"){
	     return $this->getDatetime($i);
	  }
	  else if($this->searchFilter[$i][0] == "time"){
	     return $this->getTime($i);
	  }
	  else{
	     return $this->getTextfield($i);
	  }
   }
   
   /*fim*/
   function codeSearch(){
      $retorno = $this->codeExcel();
	  $retorno .= "<br/>";
      $retorno .= '<input type="hidden" name = "'.$this->name.'pg" value = "1" />';
      $retorno .= '<input type="hidden" name = "'.$this->name.'hist" value = "'.$this->getHistFilString().'" />';
      $retorno .= '<table class = "gridSearch" width="100%">';
      $retorno .= '<td><span class = "searchText">N°:</span></td>';
	  
	  $retorno .= '<td><input name = "'.$this->name.'pagMax" size="3" class = "searchField" type = "text" value ="'.htmlentities($this->pagMax).'" /></td>';
	  
	  $retorno .= '<td><span class = "searchText">Filtrar Por: </span></td>';
      $retorno .= '<td><select onchange="onSelectOption(this)" id="filtro'.$this->name.'" name = "'.$this->name.'filtro" class = "searchCombo">';
      
	  function cmp($a,$b){
	     return strcmp(strtolower($a),strtolower($b));
	  }
	  
	  $header = $this->header;
	  $title = $this->titles;
	  usort($title,cmp);
	  $isort = array();
	  for($i=0;$i<sizeof($title);$i++){ 
	     for($j=0;$j<sizeof($title);$j++){
		    if($title[$i] == $this->titles[$j]){
				$isort[$i] = $j;
		 	} 
		 } 
	  } 
	  // print_r($this->header);
	  $mn = 0;
	  for($i=0;$i<sizeof($this->header) - sizeof($this->column);$i++){
         $k = $isort[$i];
		 if($this->hideSearchOption[$k] != "hidden"){
			$retorno .= '<option value = "'.htmlentities($this->getColumnName($k)).'" '.($this->getColumnName($k)==$this->getGetParam("filtro")?'selected':"").'>'.$this->header[$k]->get("innerHTML").'</option>';
		 }
      }
	  $this->header = $header; 

	  $retorno .= '</select></td>';
	  
	  $retorno .= '<script type="text/javascript">';
	  $retorno .= 'var gridcomponents = [];';
	  
	  $mn = 0;
	  for($i=0;$i<sizeof($this->header) - sizeof($this->column);$i++){
	 	 $k = $isort[$i];
		 if($this->hideSearchOption[$k] != "hidden"){
			$retorno .= 'gridcomponents['.$mn++.']="'.addslashes($this->getSearchFilter($k)).'";';
		 }
	  }
	  $retorno .= '</script>';
      $retorno .= '<td><span class = "searchText">Palavra-Chave: </span></td>';
      $retorno .= '<td><div id="div'.$this->name.'"><input id="" name = "'.$this->name.'palavra" class = "searchField" type = "text" value ="'.htmlentities($this->getGetParam("palavra")).'" /></div></td>';
      $retorno .= '<td>';
	  $retorno .= '<input type="submit" class = "searchButton" name="'.$this->name.'acao" value="Filtrar" />';
	  $retorno .= '<input type="submit" class = "searchButton" name="'.$this->name.'acao" value="Novo Filtro" />';
	  $retorno .= '</td>';
      $retorno .= "</tr>";
      $retorno .= "</table>";
      $retorno .= '<input type="hidden" name = "'.$this->name.'ord" value="'.$this->getGetParam("ord").'" />';
      $retorno .= "<script type='text/javascript'>";
      $retorno .= 
	  "function changeSearchField(n){".
	  	"var o = gridcomponents[n];".
		"var d = document.getElementById('div$this->name');".
		"d.innerHTML = o;".
	  "}".
	  "function onSelectOption(s){".
	  	"var op;".
		"for(var i=0;i<s.options.length;i++){".
			"if(s.options[i].selected){".
			   "break;".
			"}".
		"}".
		"changeSearchField(i);".
	  "}".
	  "onSelectOption(document.getElementById(\"filtro$this->name\"));"
	  ;
	  $retorno .= "</script>";
	  return $retorno;
   }
   function createSearch(){
      echo $this->codeSearch();
   }
   function codeBottomBar(){
      $r = '<table class = "gridBottom" width="100%">';
      $r .= "<tr>";
      $r .= '<td width="10%" align="left">'.'Total:'.$this->rowNum.'</td>';
      $r .= '<td width="90%" align="center">'.$this->codePage().'</td>';
      $r .= "</tr>";
      $r .= "</table>";
      return $r;
   }
   function createSession(){
   
   }
   function setHidden($r){
   	  $this->hideColumn = $r;
   }
   function getCode(){
   	  $_SESSION['last_format'] = $this->format;
	  $_SESSION['last_header'] = $this->header;
	  $_SESSION['last_functions'] = $this->functions;
	  $_SESSION['last_hidden'] = $this->hideColumn;
   	  $r = '<form action="" method="get">';
      $r .= "<div id = '".$this->name."div'>";
	  $r .= $this->codeSearch();
      $r .= $this->codeGrid();
      $r .= $this->codeBottomBar();
	  $r .= "</div>";
      $r .= '</form>';
	  return $r; 
   }
   function create(){
      echo $this->getCode();
	  echo '<script type="text/javascript">';
	  include_once("gridscripts.js");
	  echo '</script>';
   }
   function setFormat($index,$format){
      if($format[0] == "function"){
	  	 $this->functions[sizeof($this->functions)] = $format[1];
	  }
	  $this->format[$index] = $format;
   }

   function addColumn($titulo,$conteudo){
      $this->header[sizeof($this->header)] = new Tag("th","innerHTML",$titulo);
      $this->column[sizeof($this->column)] = new Tag("td","innerHTML",$conteudo);
   }
   
   function __tostring(){
      return $this->getCode();
   }
   function setSearchFilter($index,$type,$content=""){
      $this->searchFilter[$index] = array($type,$content);
   }
}
include_once("gridexcel.php");
?>
