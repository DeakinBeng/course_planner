<?php
include_once 'Unit.php';

class SIT353 extends Unit {
	private $prerequisites = array("Musthavecompleted4unitsinSIT151","SIT152","SIT153","SIT251","SIT252","SIT253","SIT254","SIT255","SIT352","SIT354");
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
	
		return implode(", ", $this->prerequisites);
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
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