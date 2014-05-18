<?php
include_once 'simple_html_dom.php';

$completedUnits = array();

function getUnitsSQL($units) {
			global $completedUnits;
			$availstr = "";
			$unitstr = "";
			foreach ($units as $unit) {
				if (!in_array($unit, $completedUnits)) {
				$url = "http://www.deakin.edu.au/future-students/courses/unit.php?unit=".trim($unit);
				$html = file_get_html($url);
				$tablerow =  $html->find('table.course_summary')[0]->find('tbody tr');
				$availabilitytable = null;
				$credits = null;
				$eftsl = null;
				
				foreach ($tablerow as $row) {
					if ($row->find("th") != null) {
				if (trim($row->find("th")[0]->plaintext) == "Enrolment modes:") {
					$availabilitytable = trim($row->find("td")[0]->plaintext);
				} else
				if (trim($row->find("th")[0]->plaintext) == "Credit point(s):") {
					$credits = trim($row->find("td")[0]->plaintext);
				}else
				if (trim($row->find("th")[0]->plaintext) == "EFTSL value:") {
					$eftsl = trim($row->find("td")[0]->plaintext);
				}
				}
				
				}
				$unitstr = $unitstr . "INSERT INTO `Units` (
				`Unit_Code`,
				`Unit_Title`,
				`Credit_Points`,
				`EFTSL_Value`
				) VALUES (
				\"". trim($unit) ."\",
				\"". trim(substr($html->find("#wmt_content h2")[0]->plaintext, strlen($unit)+2)) ."\",
				\"". $credits ."\",
				\"". $eftsl ."\"
				);<br />";
				
				$burwood = [0,0,0];
				$b = false;
				$waterfront = [0,0,0];
				$w1 = false;
				$waurn = [0,0,0];
				$w2 = false;
				$warrnambool = [0,0,0];
				$w3 = false;
				$cloud = [0,0,0];
				$c = false;
				
				$trimesters = explode("Trimester", $availabilitytable);
				for ($i = 1; $i < count($trimesters); $i++) {
					$trimesterNo = substr($trimesters[$i], 0, 2);
					
					if (strpos($trimesters[$i], "Burwood")) {
						$burwood[$trimesterNo-1] = 1;
						$b = true;
					}
					if (strpos($trimesters[$i], "Warrnambool")) {
						$w3 = true;
						$warrnambool[$trimesterNo-1] = 1;
					}
					if (strpos($trimesters[$i], "Waterfront")) {
						$waterfront[$trimesterNo-1] = 1;
						$w1 = true;
					}
					if (strpos($trimesters[$i], "Waurn")) {
						$w2 = true;
						$waurn[$trimesterNo-1] = 1;
					}
					if (strpos($trimesters[$i], "Cloud")) {
						$c = true;
						$cloud[$trimesterNo-1] = 1;
					}
				}
				
				if ($b) {
					$availstr = $availstr . "INSERT INTO `Availabilities`(
					`Campus_ID`,
					`Unit_Code`,
					`Trimester1`,
					`Trimester2`,
					`Trimester3`
					) VALUES (
					\"5\", 
					\"". trim($unit) ."\",
					\"". $burwood[0] ."\",
					\"". $burwood[1] ."\",
					\"". $burwood[2] ."\"
					);<br />";
				}
				if ($w1) {
					$availstr = $availstr . "INSERT INTO `Availabilities`(
					`Campus_ID`,
					`Unit_Code`,
					`Trimester1`,
					`Trimester2`,
					`Trimester3`
					) VALUES (
					\"6\", 
					\"". trim($unit) ."\",
					\"". $waterfront[0] ."\",
					\"". $waterfront[1] ."\",
					\"". $waterfront[2] ."\"
					);<br />";
				}
				if ($w2) {
					$availstr = $availstr . "INSERT INTO `Availabilities`(
					`Campus_ID`,
					`Unit_Code`,
					`Trimester1`,
					`Trimester2`,
					`Trimester3`
					) VALUES (
					\"7\", 
					\"". trim($unit) ."\",
					\"". $waurn[0] ."\",
					\"". $waurn[1] ."\",
					\"". $waurn[2] ."\"
					);<br />";
				}
				if ($w3) {
					$availstr = $availstr . "INSERT INTO `Availabilities`(
					`Campus_ID`,
					`Unit_Code`,
					`Trimester1`,
					`Trimester2`,
					`Trimester3`
					) VALUES (
					\"9\",  
					\"". trim($unit) ."\",
					\"". $warrnambool[0] ."\",
					\"". $warrnambool[1] ."\",
					\"". $warrnambool[2] ."\"
					);<br />";
				}
				if ($c) {
					$availstr = $availstr . "INSERT INTO `Availabilities`(
					`Campus_ID`,
					`Unit_Code`,
					`Trimester1`,
					`Trimester2`,
					`Trimester3`
					) VALUES (
					\"8\",  
					\"". trim($unit) ."\",
					\"". $cloud[0] ."\",
					\"". $cloud[1] ."\",
					\"". $cloud[2] ."\"
					);<br />";
				}
				array_push($completedUnits, $unit);
			}
			}
		echo $unitstr;
		echo $availstr;

}

function getCoursesSQL($courses) {
	$availstr = "";
	$coursestr = "";
	$courseUnitsSQL = "";
	foreach ($courses as $course) {
		$url = "http://www.deakin.edu.au/future-students/courses/course.php?course=".trim($course)."&stutype=local";
		$html = file_get_html($url);
		$tablerow =  $html->find('table.course_summary')[0]->find('tbody tr');
		$coursetitle = "";
		$campusoffering = "";
		$length = "";
		$coursecode = "";
		
		foreach ($tablerow as $row) {
			if ($row->find("th") != null) {
				if (trim($row->find("th")[0]->plaintext) == "Award granted") {
					$coursetitle = trim($row->find("td")[0]->plaintext);
				} else
				if (trim($row->find("th")[0]->plaintext) == "Campus") {
					$campusoffering = trim($row->find("td")[0]->plaintext);
				} else
				if (trim($row->find("th")[0]->plaintext) == "Length") {
					$length = substr(trim($row->find("td")[0]->plaintext), 0, 1);
				} else
				if (trim($row->find("th")[0]->plaintext) == "Deakin course code") {
					$coursecode = trim($row->find("td")[0]->plaintext);
				}
			}
		}
		
		if (strpos($campusoffering, "Burwood")) {
		$availstr = $availstr . "INSERT INTO `Campus_Course_Offerings` (
		`Campus_ID`,
		`Course_Code`
		) VALUES (
		\"5\",
		\"" . $coursecode . "\"
			);<br />";
		}
		if (strpos($campusoffering, "Waterfront")) {
		$availstr = $availstr . "INSERT INTO `Campus_Course_Offerings` (
		`Campus_ID`,
		`Course_Code`
		) VALUES (
		\"6\",
		\"" . $coursecode . "\"
			);<br />";
		}
		if (strpos($campusoffering, "Waurn")) {
		$availstr = $availstr . "INSERT INTO `Campus_Course_Offerings` (
		`Campus_ID`,
		`Course_Code`
		) VALUES (
		\"7\",
		\"" . $coursecode . "\"
			);<br />";
		}
		if (strpos($campusoffering, "Warrnambool")) {
		$availstr = $availstr . "INSERT INTO `Campus_Course_Offerings` (
		`Campus_ID`,
		`Course_Code`
		) VALUES (
		\"9\",
		\"" . $coursecode . "\"
			);<br />";
		}
		if (strpos($campusoffering, "Cloud")) {
		$availstr = $availstr . "INSERT INTO `Campus_Course_Offerings` (
		`Campus_ID`,
		`Course_Code`
		) VALUES (
		\"8\",
		\"" . $coursecode . "\"
			);<br />";
		}
		$coursestr = $coursestr . "INSERT INTO `Courses` (
			`Course_Code`,
			`Course_Title`,
			`Length`
			) VALUES (
			\"".$coursecode."\",
			\"".$coursetitle."\",
			\"".$length."\"
			);<br />";
			
	$units = array();
	$unitsinHTML = $html->find("table.aligned_unit_line tbody tr td a");
	
	foreach ($unitsinHTML as $unit) {
		array_push($units, $unit->plaintext);
		$courseUnitsSQL = $courseUnitsSQL . "INSERT INTO `Course_Units` (
		`Course_Code`,
		`Unit_Code`
		) VALUES (
		\"".$coursecode."\",
		\"".$unit->plaintext."\"
		);<br />";
	}
	getUnitsSQL($units);
	}
	echo $coursestr;
	echo $availstr;
	echo $courseUnitsSQL;
}
if(isset($_GET["code"]) && isset($_GET["cu"])){
	if ($_GET["cu"] == "course") {
		$courses = explode (",", strtoupper($_GET["code"]));
		getCoursesSQL($courses);
	} else if ($_GET["cu"] == "unit") {
		$units = explode (",", strtoupper($_GET["code"]));
		getUnitsSQL($units);
	}
}
?>