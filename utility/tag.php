<?php 
include_once("functions.php");
class Tag{
   private $property;
   private $endtag;
   protected $name;
   

   public function Tag($tagname,$prop="innerHTML",$value="",$endtag=true){
      $this->property = array();
      $this->property["outerHTML"] = "";
	  $this->property[$prop] = $value;	  
      $this->name = $tagname;
	  $this->endtag = $endtag;
   }
   
   public function get($str){
      return $this->property[$str];
   }
   public function set($prop,$value){
      $this->property[$prop] = $value;
   }
   public function concat($prop,$value){
   	  $this->property[$prop] .= $value;
   }
   public function getPropertyString(){
      $str = "";
      foreach($this->property as $arr=>$value){
         if($arr != "innerHTML" && $arr != "outerHTML")
         $str .= $arr . '=\'' . $value . '\' ';
      }
      return $str;
   }
   public function toString(){
      $r = "<".$this->name." ".$this->getPropertyString().">";
	  if($this->endtag)$r .= $this->get("innerHTML") . "</".$this->name.">";
	  $r .= $this->get("outerHTML");
	  return $r;
   }
   function __toString(){
	  return $this->toString();
   }
   public function create(){
      echo $this->toString();
   }
}
?>