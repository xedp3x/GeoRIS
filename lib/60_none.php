<?php
/*
 * Importiert keine Daten
 * 
 *  
 * (A) Mario XEdP3X
 * (c) GPL
 */

class PL_NONE
{
	protected $option = array();

	function __construct($setting) {
		$this->option = $setting;

	}

	public function get_by_id($id){
		
		$out["version"]="none";
		$out["id"]=$id;
		$out["template"]=$this->option["id"];
		
		return $out;
	}
}