<?php
include_once 'Unit.php';

class SIT222 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Two SIT coded units (excluding mathematics units coded SIT19-, SIT29-, SIT39-) or one SIT coded unit and MSC228 (excluding mathematics units coded SIT19-, SIT29-, SIT39-)";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$count = 0;
		if (Util::in_array_r("MSC228", $table))
			$count++;
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false) {
				if (substr($unit, 4, 1) !== "9") {
					$count++;
				}
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