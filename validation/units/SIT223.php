<?php
include_once 'Unit.php';

class SIT223 extends Unit {
	private $prerequisites = array();
	private $corequisites = array("SIT105");
	private $incompatibilities = array("SIT301","MIS231");

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Must have completed a minimum of two SIT coded units";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$count = 0;
		
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false) {
				$count++;
			}
			
			if ($count >= 2)
				break;
		}
		
		if ($count < 2)
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