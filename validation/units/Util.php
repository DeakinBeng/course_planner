<?php

class Util {
	public static function getAllRowsBefore($table, $row) {
		$ret = array();
		$count = 0;
		for ($i = 0; $i < $row ; $i++) {
			for ($j = 0; $j < count($table[$i]); $j++) {
				$ret[] = $table[$i][$j];
			}
		}
		return $ret;
	}
	
	public static function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && Util::in_array_r($needle, $item, $strict))) {
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
		$con= new mysqli("deakincourseplanner.com","Deakin","Deakin12!","CPlanner");
		if (mysqli_connect_errno()) {
			die ("Failed to connect to MySQL: " . mysqli_connect_error());
		} else {
			foreach ($table as $unit) {
				$sql = $con->query("SELECT `Credit_Points` FROM `units` WHERE `Unit_Code` = \"".$unit."\";");
				if ($sql->num_rows > 0) {
					$row = $sql->fetch_assoc();
					$cpoints += $row['Credit_Points'];
				}
			}
		}
		$con->close();
		return $cpoints;
	}
	
	public static function getTrimesterAvail($campus, $unit) {
		$trimesters = array();
		$con= new mysqli("deakincourseplanner.com","Deakin","Deakin12!","CPlanner");
		if (mysqli_connect_errno()) {
			die ("Failed to connect to MySQL: " . mysqli_connect_error());
		} else {
			$sql = $con->query("SELECT A.Campus_ID, C.Campus_ID, C.Name, A.Unit_Code, A.Trimester1, A.Trimester2, A.Trimester3  FROM 
			`availabilities` AS A 
			INNER JOIN 
			`campus` AS C
			ON C.Name = \"".$campus."\" AND C.Campus_ID = A.Campus_ID AND A.Unit_Code=\"".$unit."\"");
			if ($sql->num_rows > 0) {
				$row = $sql->fetch_assoc();
				$trimesters[0] = $row['Trimester1'];
				$trimesters[1] = $row['Trimester2'];
				$trimesters[2] = $row['Trimester3'];
			}
		}
		$con->close();
		return $trimesters;
	}
}
?>