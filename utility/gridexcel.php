<?php
//session_start();
class GridExcel extends Grid{
	public function GridExcel($n="",$s="",$r=9999999){
		if($n==""){
			$s = Grid::getSessionQuery();
		}
		else if($s == ""){
			$s = Grid::GetSessionQuery();
		}
		parent::Grid($n,$s,$r);
	}
	public function getTitle(){
		$c = "<tr>";
		for($i=0;$i<sizeof($this->ordColumn);$i++){
			if(!$this->hideColumn[$i]){
				$c .= '<th>'.$this->header[$i]->get("innerHTML").'</th>';
			}
		}
		$c .= '</tr>';
		return $c;
	}
	public function getCell($r,$i){
		return $this->formatString($r,$i,0);	
	}
	public function getLine(){
		$r = mysql_fetch_row($this->queryTotal);
		$line = "<tr>";
		for($i=0;$i<sizeof($r);$i++){
			if(!$this->hideColumn[$i]){
				$line .= "<td>".$this->getCell($r[$i],$i)."</td>";
			}
		}
		$line .= "</tr>";
		return $line;	
	}
	public function getCode(){
	    
		$c = "<table border=\"1\">";
		$c .= $this->getTitle();
		for($i=0;$i<mysql_num_rows($this->queryTotal);$i++){
			$c .= $this->getLine();
		}
		$c .= "</table>";
		return $c;
	}
	function setHeaderTitle($index,$title){
	  $this->header[$index]->set("innerHTML",'<a>'.$title.'</a>');
    }
	public function create(){
		/*header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Transfer-Encoding: binary ");
		header('Content-type: application/vnd.ms-excel');
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename=' . "relatorio_".substr(md5(uniqid(time())), 0, 5).".xls");
		header('Pragma: no-cache');*/	
		echo $c = $this->getCode(); 
		return $c;
	}
	public function setHeader($f){
		$this->header =$f;
	}
	public function setFormatter($f){
		$this->format = $f;
	}
	public function __tostring(){
		return $this->create();   
	}
}


?>