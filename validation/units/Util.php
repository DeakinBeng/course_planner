<?php
class Util {
	public static function getAllRowsBefore($table, $row) {
		$ret = array();
		$count = 0;
		for ($i = 0; $i < $row ; $i++) {
			for ($j = 0; $j < count($table[0]); $j++) {
				$ret[] = $table[$i][$j];
			}
		}
	}
	
	function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}
	
	public static function addCurrentRow($table1, $table2, $row) {
		for ($i = 0 ; $i < count($table1[$row]) ; $i++) {
			$table2[$row][$i] = $table1[$row][$i];
		}
	}

	public static function getCreditPoints($table) {
		$cpoints = 0;
		foreach ($table as $row) {
			foreach ($row as $unit) {
				$cpoints += $unit->getCreditPoints();
			}
		}
	}
}
?>