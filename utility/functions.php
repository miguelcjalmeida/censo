<?php
function base(){
	return "/censo/";
}
function strcut($str,$i,$f){
   $retorno = "";
   for(;$i<$f;$i++){
      $retorno .= $str[$i];
   }
   return $retorno;
}
function getFileName($file){
   for($i=strlen($file)-1;$i>=0;$i--){
      if($file[$i] == "/" || $file[$i] == "\\"){
         return strcut($file,$i+1,strlen($file));
      }
   }
   return $file;
}
?>
