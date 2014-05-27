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
	
	public function validateRequirements($table, $row) {
		$ret = "";
		$newTable = Util::getAllRowsBefore($table, $row);
		if (!$this->validatePrerequisites($newTable)) {
			$ret = $ret . ($this->getPrerequisites());
		}
		$ret = $ret . "|";
		Util::addCurrentRow($table, $newTable, $row);
		if (!$this->validateCorequisites($table)) {
			$ret = $ret . ($this->getCorequisites());
		}
		$ret = $ret . "|";
		if (!$this->validateIncompatibilities($table)) {
			$ret = $ret . ($this->getIncompatibilities());
		}
		return $ret;
	}
}
?>