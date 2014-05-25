<?php
include_once 'Unit.php';

class SIT263 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "SIT162 or SIT120 or at least 4 SIT coded unit (excluding mathematics units coded SIT19-, SIT29-, SIT39-)";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		if (Util::in_array_r("SIT162", $table) || Util::in_array_r("SIT120", $table))
			return true;
		
		$count = 0;
		foreach ($table as $row) {
			foreach ($row as $unit) {
				if (strpos($unit, "SIT") !== false) {
					if (substr($unit, 4, 1) !== "9") {
						$count++;
					}
				}
				
				if ($count >= 4)
					break;
			}
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