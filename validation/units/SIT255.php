<?php
include_once 'Unit.php';

class SIT255 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT352");

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "SIT153 or SIT251";
	}
	
	public function getCorequisites() {
		return implode(", ", $this->corequisites);
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		if (!Util::in_array_r("SIT153", $table) && !Util::in_array_r("SIT251", $table)) {
			return false;
		}
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
}

?>