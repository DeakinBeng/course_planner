<?php
include_once 'Unit.php';

class SIT340 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT740");

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return implode(", ", $this->prerequisites);
	}
	
	public function getCorequisites() {
		return "Any two level 2 SIT coded units";
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		if (count($this->prerequisites) > 0) {
			foreach ($this->prerequisites as $pre) {
				if (!in_array($pre, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function validateCorequisites($table) {
		$count = 0;
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false) {
				if (substr($unit, 3, 1) == "2") {
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