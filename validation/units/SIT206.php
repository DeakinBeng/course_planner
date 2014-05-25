<?php
include_once 'Unit.php';

class SIT206 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return "SIT102 or SIT153 and one other SIT coded unit";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$firstpre = false;
		$secondpre = false;
		
		foreach ($table as $row) {
			foreach ($row as $unit) {
				if (strpos($unit, "SIT") !== false) {
					if (strpos($unit, "SIT102") !== false) {
						$firstpre = true;
					} else if (strpos($unit, "SIT153") !== false) {
						$firstpre = true;
					} else {
						$secondpre = true;
					}
				}
				if ($firstpre && $secondpre)
					return true;
			}
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
	
	public function validateRequirements($table, $row, $creditPoints) {
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