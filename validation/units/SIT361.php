<?php
include_once 'Unit.php';

class SIT361 extends Unit {
	private $prerequisites = array();
	private $corequisites = array();
	private $incompatibilities = array("MSC301");

	function __construct($unitCode) {
		parent::__construct($unitCode);
   }
   
   	public function getPrerequisites() {
		return "Two SIT level 2 coded units (excluding mathematics units coded SIT19-, SIT29-, SIT39-) or one SIT level 2 coded unit and MSC228 (excluding mathematics units coded SIT19-, SIT29-, SIT39-)";
	}
	
	public function getCorequisites() {
		return "One of SIT363 or SIT262";
	}
	
	public function getIncompatibilities() {
		return implode(", ", $this->incompatibilities);
	}
	
	protected function validatePrerequisites($table) {
		$count = 0;
		if (Util::in_array_r("MSC228", $table))
			$count++;
		foreach ($table as $unit) {
			if (strpos($unit, "SIT") !== false && substr($unit, 3, 1) == "2") {
				if (substr($unit, 4, 1) !== "9") {
					$count++;
				}
			}
			
			if ($count >= 2)
				break;
		}
		
		if ($count < 2)
			return false;
		return true;
	}
	
	protected function validateCorequisites($table) {
		if (!Util::in_array_r("SIT363", $table) && !Util::in_array_r("SIT262", $table))
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