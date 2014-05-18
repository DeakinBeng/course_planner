<?php
include_once 'Unit.php';

class SIT354 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT252");

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return "SIT204 and one of SIT153, SIT251";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		if (!in_array("SIT204", $table)) {
			return false;
		} else if (!in_array("SIT153", $table) && !in_array("SIT251", $table)) {
			return false;
		}
		return true;
	}
	
	protected function validateCorequisites($table) {
		if (count($this->corequisites) > 0) {
			foreach ($this->corequisites as $cor) {
				if (!in_array($cor, $table)) {
					return false;
				}
			}
		}
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