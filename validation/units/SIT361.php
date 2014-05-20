<?php
include_once 'Unit.php';

class SIT361 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("MSC301");

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return "Two SIT level 2 coded units (excluding mathematics units coded SIT19-, SIT29-, SIT39-) or one SIT level 2 coded unit and MSC228 (excluding mathematics units coded SIT19-, SIT29-, SIT39-)";
	}
	
	public function getCorequisites() {
		return "One of SIT363 or SIT262";
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$count = 0;
		if (in_array("MSC228", $table))
			$count++;
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false && substr($unit, 3, 1) == "2") {
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
		if (!in_array("SIT363", $table) && !in_array("SIT262", $table))
			return false;
		return true;
	}
	
	protected function validateIncompatibilities($table) {
		if (count($this->incompatibilities) > 0) {
			foreach ($this->incompatibilities as $inc) {
				if (in_array($inc, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	public function validateRequirements($table) {
		if ($this->validatePrerequisites($table) && 
		$this->validateCorequisites($table) && 
		$this->validateIncompatibilities($table)) {
			return true;
		}
		return false;
	}
}

?>