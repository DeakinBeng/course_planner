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
	
	public function validateRequirements($table, $row, $campus) {
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
		$ret = $ret . "|";
		$trimesters = Util::getTrimesterAvail($campus, $this->unitCode);
		$trimester = ($row) % 2;
		if (count($trimesters) < 3) {
			$ret = $ret . "No availability data found. Please notify the system Administrator";
		} else if ($trimesters[$trimester] == 0) {
			$ret = $ret . "Unit is only available in trimester ";
			$count = 0;
			foreach ($trimesters as $tri) { // get how many trimesters are available
				if ($tri ==1)
					$count++;
			}
			for ($i = 1; $i <= count($trimesters); $i++) {
				if ($trimesters[$i-1] == 1) {
					$ret = $ret . $i;
					if ($count == 2 && $i < count($trimesters)) {
						$ret = $ret . " and ";
					}
				}
			}
		}
		return $ret;
	}
}
?>