<?php
include_once 'Unit.php';

class SIT340 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT740");

	function __construct($unitCode) {
		parent::__construct($unitCode);
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
				if (!Util::in_array_r($pre, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function validateCorequisites($table) {
		$count = 0;
		foreach ($table as $row) {
			foreach ($row as $unit) {
				if (strpos($unit, "SIT") !== false) {
					if (substr($unit, 3, 1) == "2") {
						$count++;
					}
				}
				
				if ($count >= 2)
					break;
			}
		}
		
		if ($count < 2)
			return false;
			
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
	
	public function validateRequirements($table, $row) {
		$newTable = Util::getAllRowsBefore($table, $row);
		if ($this->validatePrerequisites($newTable)) {
			Util::addCurrentRow($table, $newTable, $row);
			if ($this->validateCorequisites($table) && 
				$this->validateIncompatibilities($table)) {
				return true;
			}
		}
		return false;
	}
}

?>