<?php
/*
 * Importiert Daten von einem ALLRIS Server
 * 
 * Version 2 (ohne html2text) 
 *  
 * (A) Mario XEdP3X
 * (c) GPL
 */

class PL_ALLRIS
{
	protected $option = array();

	function __construct($setting) {
		$this->option = $setting;

	}

	public function get_by_id($id, $old = array()){
		
		$out = $old;
		
		$id = $id + 0;
		if ($id == 0)
			error("ALLRIS_get_by_id","Die ID bei einem ALLRIS Import muss numerisch sein!");
		
		
		$html = file_get_contents($this->option["url"].$id);
		//$html = file_get_contents("/home/mario/allris.html");
		
		$tmp = substr($html,strpos($html,'<div id="allrisContent">'));
		$tmp = substr($tmp ,0,strpos($tmp,'<h3>Legende</h3>'));
		$text = utf8_encode(HTML2TEXT($tmp));
		$out["version"]=md5($tmp);
		
		$tmp = explode("***",$text);
		$out["name"] = $tmp[1];
		
		$out["titel"] 	= stringbetween($text,"Betreff:","\n");
		$out["status"] 	= stringbetween($text,"Status:","\t\t ");
		$out["text"] 	= stringbetween($text,"-------------------------","-------------------------");
		
		$tmp 			= stringbetween($text,"Drucksache-Art:","\n");
		$out["art"]		= substr($tmp,0,strpos($tmp,"\t"));
		
		$out["url"] 	= $this->option["url"].$id;
		
		
		$tmp = substr($text, strpos($text,"Beratungsfolge:"));
		$tmp = substr($tmp,0,strpos($tmp ,'Anlagen:'));
		$tmp = explode(" ",$tmp);
		$date = 0;
		while( list ( $key, $val ) = each ( $tmp ) ){
			$datum = trim($val);
			if (preg_match('/^(\d{2}).(\d{2}).(\d{4})$/', $datum, $wert)){
				$x = strtotime($datum);
				if ($x){
					if ($x > $date)
						$date = $x;
				}
			}
		}
		$out["date"] = date("Y-m-d H:i:s",$date);
		
		$out["id"]=$id;
		$out["template"]=$this->option["id"];
		
		$out["pad"] 	= str_replace('%id%',$id,$this->option["pad"]); 
		$out["wiki"] 	= str_replace('%id%',$id,$this->option["wiki"]); 
		$out["forum"] 	= str_replace('%id%',$id,$this->option["forum"]); 
		
		return $out;
	}
}