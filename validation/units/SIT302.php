<?php
include_once 'Unit.php';

class SIT302 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("SCM363","MSC303","MIS399");

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Must have passed five SIT level 2 or 3 coded units.";
	}
	
	public function getCorequisites() {
		return "SIT301 or SIT374 or MIS398";
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$count = 0;
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false) {
				if (substr($unit, 3, 1) == "2" || substr($unit, 3, 1) == "3") {
					$count++;
				}
			}
			
			if ($count >= 5)
				break;
		}
		
		if ($count < 5)
			return false;
			
		return true;
	}
	
	protected function validateCorequisites($table) {
		if (!Util::in_array_r("SIT301", $table) && !Util::in_array_r("SIT374", $table) && !Util::in_array_r("MIS398", $table))
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
}

?>