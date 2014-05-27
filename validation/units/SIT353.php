<?php
include_once 'Unit.php';

class SIT353 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Must have completed 4 units in SIT151, SIT152, SIT153, SIT251, SIT252, SIT253, SIT254, SIT255, SIT352, SIT354";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$pre = ["SIT151","SIT152","SIT153","SIT251","SIT252","SIT253","SIT254","SIT255","SIT352","SIT354"];
		$count = 0;
		foreach ($pre as $p) {
			if (Util::in_array_r($p, $table)) {
				$count++;
			}
			if ($count >= 4)
				break;
		}
		if ($count < 4)
			return false;
			
		return true;
	}
	
	protected function validateCorequisites($table) {
		if (count($this->corequisites) > 0) {
			foreach ($this->corequisites as $cor) {
				if (!Util::in_array_r($cor, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function validateIncompatibilities($table) {
		if (count($this->incompatibilities) > 0) {
			foreach ($this->incompatibilities as $inc) {
				if (Util::in_array_r($inc, $table)) {
					return false;
				}
			}
		}
		return true;
	}
}

?>