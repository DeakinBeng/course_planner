<?php
include_once 'simple_html_dom.php';


	$con= new mysqli("120.147.187.76","Deakin","Deakin12!","CPlanner");
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	} else {
		$sql = $con->query("SELECT `Unit_Code` FROM `units`;");
		if ($sql->num_rows > 0) {
		while ($row = $sql->fetch_assoc()) {
			makeFiles($row['Unit_Code']);
		}
		}
	}

function makeFiles($unit) {
	$url = "http://www.deakin.edu.au/future-students/courses/unit.php?unit=".trim($unit);
	$html = file_get_html($url);
	$tablerow =  $html->find('table.course_summary')[0]->find('tbody tr');
	
	$Prerequisite = "";
	$pspecial = "";
	$Corequisite = "";
	$cspecial = "";
	$Incompatible = "";
	$ispecial = ""; 
	
	foreach ($tablerow as $row) {
		if ($row->find("th") != null) {
		$tkey = trim($row->find("th")[0]->plaintext);
		$tvalue = trim($row->find("td")[0]->plaintext);
		if ($tkey == "Prerequisite:") {
			if ($tvalue != "Nil") {
				if ((strpos($tvalue, ",") || strlen($tvalue) == 6) && !strpos($tvalue, "or")) {
					$Prerequisite = "\"" . str_replace(" ", "", implode("\",\"", explode (",", $tvalue))) . "\"";
				} else {
					echo $unit . " spre: " . $tvalue . "<br />";
					$pspecial = $tvalue;
				}
			}
		} else if ($tkey == "Corequisite:") {
			if ($tvalue != "Nil") {
				if (strpos($tvalue, ",") || strlen($tvalue) == 6)
					$Corequisite = "\"" . str_replace(" ", "", implode("\",\"", explode (",", $tvalue))) . "\"";
				else {
					echo $unit . " con: " . $tvalue . "<br />";
					$cspecial = $tvalue;
			}}
		} else if ($tkey == "Incompatible with:") {
			if ($tvalue != "Nil") {
				if (strpos($tvalue, ",") || strlen($tvalue) == 6) {
					$Incompatible = "\"" . str_replace(" ", "", implode("\",\"", explode (",", $tvalue))) . "\"";
				}else{
					echo $unit . " inc: " . $tvalue . "<br />";
					$ispecial = $tvalue;
			}}
		}
		}
	}
	/*
	$my_file = $unit.".php";
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	$data = '<?php
include_once \'Unit.php\';

class '.$unit.' extends Unit {
	private $prerequisites = array('.$Prerequisite.');
	private $corequisites = array('.$Corequisite.');
	private $incompatibilities = array('.$Incompatible.');

	function __construct($unitTitle, $unitCode, $creditPoints, $EFTSL) {
		parent::__construct($unitTitle, $unitCode, $creditPoints, $EFTSL);
   }
   
   	public function getPrerequisites() {
		return';
			if (strlen($pspecial) == 0)
				$data = $data . ' implode(", ", $this->prerequisites);';
			else 
				$data = $data . ' "'.$pspecial.'";';
				
	$data = $data . '
	}
	
	public function getCorequisites() {
		return';
			if (strlen($cspecial) == 0)
				$data = $data . ' implode(", ", $this->corequisites);';
			else 
				$data = $data . ' "'.$cspecial.'";';
				
	$data = $data . '
	}
	
	public function getIncompatibilities() {
		return';
			if (strlen($ispecial) == 0)
				$data = $data . ' implode(", ", $this->incompatibilities);';
			else 
				$data = $data . ' "'.$ispecial.'";';
				
			$data = $data . '
	}
	
	protected function validatePrerequisites($table) {
		if (count($this->prerequisites) > 0) {
			foreach ($this->prerequisites as $pre) {
				if (!in_array($pre, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function validateCorequisites($table) {
		if (count($this->corequisites) > 0) {
			foreach ($this->corequisites as $cor) {
				if (!in_array($cor, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function validateIncompatibilities($table) {
		if (count($this->incompatibilities) > 0) {
			foreach ($this->incompatibilities as $inc) {
				if (in_array($inc, $table)) {
					return false;
				}
			}
		}
		return true;
	}
	
	public function validateRequirements($table) {
		if ($this->validatePrerequisites($table) && 
		$this->validateCorequisites($table) && 
		$this->validateIncompatibilities($table)) {
			return true;
		}
		return false;
	}
}

?>';
	fwrite($handle, $data);
	*/
}

echo "Done!";
?>