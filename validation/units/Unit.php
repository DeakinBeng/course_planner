<?php
Abstract class Unit {

	private $unitCode;
	
	function __construct($unitCode) {
		$this->unitCode = $unitCode;
   }
	
	public function getUnitCode() {
		return $this->unitCode;
	}
	
	abstract protected function validatePrerequisites($table);
	
	abstract protected function validateCorequisites($table);
	
	abstract protected function validateIncompatibilities($table);
	
	abstract public function getPrerequisites();
	
	abstract public function getCorequisites();
	
	abstract public function getIncompatibilities();
	
	abstract public function validateRequirements($table, $row);
}
?>