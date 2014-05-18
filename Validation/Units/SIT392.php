<?php
include_once 'Unit.php';

class SIT392 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return "SIT281 or SIT292";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		if (!in_array("SIT281", $table) && !in_array("SIT292", $table))
			return false;
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