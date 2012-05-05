<?php
/*
 * Importiert Daten von einem ALLRIS Server
 * 
 * Benötigt:
 *  html2text
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

	public function get_by_id($id){
		
		$id = $id + 0;
		if ($id == 0)
			error("ALLRIS_get_by_id","Die ID bei einem ALLRIS Import muss numerisch sein!");
		
		$tmpfname = tempnam("/tmp", "GeoRIS_");
		$html = file_get_contents($this->option["url"].$id);
		file_put_contents($tmpfname,$html);
		$text = `html2text -width 1000 $tmpfname`;
		$text = str_replace("&#xa0;"," ",$text);
		
		unlink($tmpfname);
		
		
		$tmp = explode("******",$text);
		$out["name"] = substr($tmp[1],1,-1);
		
		$out["titel"] 	= stringbetween($text,"Betreff:         ","\n");
		$out["status"] 	= stringbetween($text,"Status:         ","  ");
		
		$tmp = stringbetween($text,"Drucksache-Art: ","  ");
		if (substr($tmp,strlen($tmp)/2).substr($tmp,strlen($tmp)/2) == $tmp){
			$out["art"] 	= substr($tmp,strlen($tmp)/2);
		}else{
			$out["art"] 	= $tmp;
		}
		$out["url"] 	= $this->option["url"].$id;
		
		$tmp = substr($html,strpos($html,'<td class="kb1" colspan="2">Anlagen:</td>'));
		$tmp = substr($tmp ,strpos($tmp,'<tr valign="top">'));
		$tmp = substr($tmp ,0,strpos($tmp,'</tr>'));
		$tmp = explode('<a href="',$tmp);
		while( list ( $key, $val ) = each ( $tmp ) ){
			$out["link"][] = array(
				"url" => $this->option["down"].substr($val,0,strpos($val,'"')),
				"name"=> stringbetween($val,">","<")
			);
		}
		unset($out["link"][0]);
		
		
		$tmp = substr($text, strpos($text," ******"));
		$tmp = substr($tmp , strpos($tmp ,'Anlagen:'));
		$tmp = substr($tmp,0,strpos($tmp ,'=============='));
		
		$tmp = explode(" ",$tmp);
		
		$date = 0;
		while( list ( $key, $val ) = each ( $tmp ) ){
			$datum = substr($val,0,10);
			if (preg_match('/^(\d{2}).(\d{2}).(\d{4})$/', $datum, $wert)){
				$x = strtotime($datum);
				if ($x){
					if ($x > $date)
						$date = $x;
				}
			}
		}
		$out["date"] = date("Y-m-d H:i:s",$date);
		

		$tmp = substr($text, strpos($text,"========="));
		$tmp = substr($tmp , strpos($tmp ,'?>')+2);
		$tmp = substr($tmp,0,strpos($tmp ,'<?'));
		
		if (strpos($tmp,"Begründung:")<> 0)
			$tmp = substr($tmp,0,strpos($tmp ,'Begründung:'));
		
		$tmp = explode("\n",$tmp);
		for ($i = 0; $i < count($tmp);$i++){
			if ($tmp[$i] == $tmp[$i-1]) unset ($tmp[$i]);
			if ($tmp[$i] == $tmp[$i-2]) unset ($tmp[$i]);
			if ($tmp[$i] == $tmp[$i-3]) unset ($tmp[$i]);
			
			if (substr($tmp[$i],5,10) == "==========") unset ($tmp[$i]);
		}
		$tmp = implode("\n",$tmp);
		$out["text"] = $tmp;
		
		
		$out["version"]=md5($text);
		$out["id"]=$id;
		$out["template"]=$this->option["id"];
		
		$out["pad"] 	= str_replace('%id%',$id,$this->option["pad"]); 
		$out["wiki"] 	= str_replace('%id%',$id,$this->option["wiki"]); 
		$out["forum"] 	= str_replace('%id%',$id,$this->option["forum"]); 
		
		
		return $out;
	}
}