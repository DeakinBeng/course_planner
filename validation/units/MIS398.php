<?php
include_once 'Unit.php';

class MIS398 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT374");

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Any four level 2 units";
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
			if (substr($unit, 3, 1) == "2") {
				$count++;
			}
			if ($count == 4)
				return true;
		}
		return false;
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