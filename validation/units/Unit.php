<?php


Abstract class Unit {

	private $unitTitle;
	private $unitCode;
	private $creditPoints;
	private $EFTSL;
	
	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		$this->unitTitle = $unitTitle;
		$this->unitCode = $unitCode;
		$this->creditPoints = $creditPoints;
		$this->EFTSL = $EFTSL;
   }
   
   	public function getUnitTitle() {
		return $this->unitTitle;
	}
	
	public function getUnitCode() {
		return $this->unitCode;
	}
	
	public function getCreditPoints() {
		return $this->creditPoints;
	}
	
	public function getEFTSL() {
		return $this->EFTSL;
	}
	
	abstract protected function validatePrerequisites($table);
	
	abstract protected function validateCorequisites($table);
	
	abstract protected function validateIncompatibilities($table);
	
	abstract public function getPrerequisites();
	
	abstract public function getCorequisites();
	
	abstract public function getIncompatibilities();
	
	abstract public function validateRequirements($table, $row, $creditPoints);
}
?>