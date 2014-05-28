<?php
include_once 'Unit.php';

class SIT207 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array();

	function __construct($unitCode) {
		parent::__construct($unitCode);
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
		
		foreach ($table as $unit) {
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
}

?>