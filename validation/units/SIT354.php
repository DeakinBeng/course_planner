<?php
include_once 'Unit.php';

class SIT354 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SIT252");

	function __construct($unitCode) {
		parent::__construct($unitCode);
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
		if (!Util::in_array_r("SIT204", $table)) {
			return false;
		} else if (!Util::in_array_r("SIT153", $table) && !Util::in_array_r("SIT251", $table)) {
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