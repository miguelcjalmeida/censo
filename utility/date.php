<?php
class Date{
	
	private $year;
	private $month;
	private $day;
	private $hour;
	private $min;
	private $sec;
	private $format;
	
	
	public function Date($date="00/00/0000",$opt="date"){
		$this->year = 0;
		$this->month = 0;
		$this->day = 0;
		$this->hour = 0;
		$this->min = 0;
		$this->sec = 0;
		
		if($opt == "date"){
			$this->parseDate($date);
			$format = "d/m/Y";
		}	 
		else if($opt == "sqldate"){
			$format = "d/m/Y";
			$this->parseSqlDate($date);
		}
		else if($opt == "time"){
			$this->parseTime($date);
			$format = "h:i:s";
		}
		else if($opt == "sqltime"){
			$this->parseTime($date);
			$format = "h:i:s";
		}
		else if($opt == "datetime"){
			$this->parseDateTime($date);
			$format = "d/m/Y h:i:s";
		}
		else if($opt == "sqldatetime"){
			$this->parseSqlDateTime($date);
			$format = "d/m/Y h:i:s";
		}
		else{
			$format = "";
		}
		$this->format = $format;	
	}
	
	private function leftZero($str,$i){
		$len = strlen($str);
		$repete = $i - $len;
		for($i=0;$i<$repete;$i++){
			$str = "0" . $str;
		}
		return $str;
	}
	private function getPreparedTime(){
		return $this->leftZero($this->year,4) . "-" . $this->leftZero($this->month,2) . "-" . $this->leftZero($this->day,2) . " " .$this->leftZero($this->hour,2).":". $this->leftZero($this->min,2) .":".$this->leftZero($this->sec,2);
	}
	
	public function toSqlDate(){
		return $this->leftZero($this->year,4) . "-" . $this->leftZero($this->month,2) . "-" . $this->leftZero($this->day,2);
	}
	public function toSqlTime(){
		return $this->leftZero($this->Hour,2) . ":" . $this->leftZero($this->min,2) . ":" . $this->leftZero($this->sec,2);
	}
	public function toSqlDateTime(){
		return $this->getPreparedTime();
	}
	public function toDefaultDate(){
		return $this->leftZero($this->day,2) . "/" . $this->leftZero($this->month,2) . "/" . $this->leftZero($this->year,4);
	}
	public function toDefaultTime(){
		return $this->leftZero($this->hour,2) . ":" . $this->leftZero($this->min,2) . ":" . $this->leftZero($this->sec,2);
	}
	public function toDefaultDateTime(){
		return $this->toDefaultDate() . " " . $this->toDefaultTime();
	}
	public function format($format=""){
		if($format==""){
			return date($this->format,strtotime($this->getPreparedTime()));
		}
		return date($format,strtotime($this->getPreparedTime()));
	}
	public function getMonthNameOf($m,$l){
		if($l == 2){
			//return date('F',mktime(0,0,0,$m,0,1));
			switch ($m) {
				case 1: return 'January'; break; 
				case 2: return 'February'; break; 
				case 3: return 'March'; break; 
				case 4: return 'April'; break; 
				case 5: return 'May'; break; 
				case 6: return 'June'; break; 
				case 7: return 'July'; break; 
				case 8: return 'August'; break; 
				case 9: return "September"; break; 
				case 10: return 'October'; break; 
				case 11: return 'November'; break; 
				default: return 'December'; break;
			}
			
		}
		else if($l == 1){
			switch ($m) {
				case 1:    return 'Janeiro';     break;
				case 2:    return 'Fevereiro';   break;
				case 3:    return 'Março';       break;
				case 4:    return 'Abril';       break;
				case 5:    return 'Maio';        break;
				case 6:    return 'Junho';       break;
				case 7:    return 'Julho';       break;
				case 8:    return 'Agosto';      break;
				case 9:    return 'Setembro';    break;
				case 10:   return 'Outubro';     break;
				case 11:   return 'Novembro';    break;
				default:   return 'Dezembro';    break; 
			}
		}
		else{
			switch ($m){
				case 1:    return 'Enero';     break;
				case 2:    return 'Febrero';   break;
				case 3:    return 'Marzo';       break;
				case 4:    return 'Abril';       break;
				case 5:    return 'Mayo';        break;
				case 6:    return 'Junio';       break;
				case 7:    return 'Julio';       break;
				case 8:    return 'Agosto';      break;
				case 9:    return 'Septiembre';    break;
				case 10:   return 'Octubre';     break;
				case 11:   return 'Noviembre';    break;
				default:   return 'Diciembre';    break; 
			}
		}
	}
	public function getWeek($l=1){
		$w = date("w",$this->getTime());
		if($l==2){
			if($w == 0)return "Sunday";
			else if($w==1)return "Monday";
			else if($w==2)return "Tuesday";
			else if($w==3)return "Wednesday";
			else if($w==4)return "Thursday";
			else if($w==5)return "Friday";
			else if($w==6)return "Saturday";	
		}
		else if($l==1){
			//echo $obj->getData()->getWeek(2);
			if($w == 0)return "Domingo";
			else if($w==1)return "Segunda-Feira";
			else if($w==2)return "Terça-Feira";
			else if($w==3)return "Quarta-Feira";
			else if($w==4)return "Quinta-Feira";
			else if($w==5)return "Sexta-Feira";
			else if($w==6)return "Sabado";
		}
		return $w;
	}
	public function getDateString($l=1,$opt=false){
		if($l == 1){
			return $this->leftZero($this->day,2) . " ". $this->getMonthName($l) ." de " .$this->leftZero($this->year,4) . ($opt ? " (" .$this->getWeek($l).")" : "");      
		}
		else if($l == 2){ 
			return $this->getMonthName($l) . " " . $this->leftZero($this->day,2) . " ,  " .$this->leftZero($this->year,4) . ($opt ? " (" .$this->getWeek($l).")"  : " at ");
		}
		else{
			return $this->leftZero($this->day,2) . " ". $this->getMonthName($l) ."  ,  ". $this->leftZero($this->year,4); 
		}
	} 
	public function getTimeString($l=1,$opt=false){
		if($l == 1){
			return $this->leftZero($this->hour,2) . ":" . $this->leftZero($this->min,2) . ($opt?" hrs (Horário de Brasília)":" ");
		}
		else if($l == 2){		
			if($this->leftZero($this->hour,2) > 12){
				return ($this->leftZero($this->hour,2)- 12) . ":" . $this->leftZero($this->min,2) . ($opt ? " p.m. (NY/EDT Time)" : " PM (ET)");
			}else if($this->leftZero($this->hour,2) == 12){
				return ($this->leftZero($this->hour,2)) . ":" . $this->leftZero($this->min,2) . ($opt ? " p.m. (NY/EDT Time)" : " PM (ET)");
			}else if($this->leftZero($this->hour,2) == 24){
				return ($this->leftZero($this->hour,2)- 12) . ":" . $this->leftZero($this->min,2) . ($opt ? " a.m. (NY/EDT Time)" : " AM (ET)");
			}else{
				return $this->leftZero($this->hour,2) . ":" . $this->leftZero($this->min,2) . ($opt ? " a.m.(NY/EDT Time)" : " AM (ET)");
			}
		}
		else{
			return $this->leftZero($this->hour,2) . ":" . $this->leftZero($this->min,2);
		}
	}
	public function getDateTimeString($l=1,$opt=false){
		
		return $this->getDateString($l) . ($opt?"<br>":" ") . $this->getTimeString($l,$opt);
	}
	
	public function getSecondTime(){
		return $this->hour * 3600 + $this->min * 60 + $this->sec;
	}	
	public function getMonthName($language=1){
		return $this->getMonthNameOf($this->month,$language);
	}
	
	public function parseSqlDate($data){
		$arr = explode("-",$data);
		$this->year = $arr[0];
		$this->month = $arr[1];
		$this->day = $arr[2];
		return $this;
	}
	public function parseSqlTime($time){
		$arr = explode(":",$time);
		$this->hour = $arr[0];
		$this->min = $arr[1];
		$this->sec = $arr[2];
		return $this;
	}
	public function parseSqlDateTime($strdate){
		$arr = explode(" ",$strdate);
		$this->parseSqlDate($arr[0]);
		$this->parseSqlTime($arr[1]);
	}
	public function parseDate($data){
		$arr = explode("/",$data);
		$this->year = $arr[2];
		$this->month = $arr[1];
		$this->day = $arr[0];
		return $this;
	}
	public function parseTime($time){
		$arr = explode(":",$time);
		$this->hour = $arr[0];
		$this->min = $arr[1];
		$this->sec = $arr[2];
		return $this;
	}
	public function parseDateTime($strdate){
		$arr = explode(" ",$strdate);
		$this->parseDate($arr[0]);
		$this->parseTime($arr[1]);
	}
	
	public function setFormat($format){
		$this->format = $format;
	}
	public function setDay($day){
		$this->day = $day; 
	}
	public function setMonth($day){
		$this->month = $day; 
	}
	public function setYear($day){
		$this->year = $day; 
	}
	public function setSec($day){
		$this->sec = $day; 
	}
	public function setMin($day){
		$this->min = $day; 
	}
	public function setHour($day){
		$this->hour = $day; 
	}

	public function getDay(){
		return $this->day;
	}
	public function getMonth(){
		return $this->month;
	}
	public function getYear(){
		return $this->year;
	}
	public function getSec(){
		return $this->sec;
	}
	public function getMin(){
		return $this->min;
	}
	public function getHour(){
		return $this->hour;
	}
	public function setTime($time){
		$date = new Date( date("d/m/Y H:i:s",$time) );
		echo $time;
		$this->sec = $date->getSec();
		$this->min = $date->getMin();
		$this->hour = $date->getHour();
		$this->day = $date->getDay();
		$this->month = $date->getMonth();
		$this->year = $date->getYear();
	}
	public function setSecondTime($time){
			
		$this->sec = $time % 60;
		$this->min = ($time/60)%60;
		$this->hour = ($time/3600)%24;
		
	}
	public function addMonth($i){
		$this->month += $i;
		if($this->month > 12){
			$this->year += 1;
			$this->month = 1; 
		}
		else if($this->month < 1){
			$this->year -= 1;
			$this->month = 1;
		}
	}
	public function getTime(){
		return mktime($this->hour,$this->min,$this->sec,$this->month,$this->day,$this->year);  
	}
	public function __toString(){
		return $this->format();
	}
}


?>
