<?php
spl_autoload_register(function ($class) {
    include 'units/' . $class . '.php';
});

if (isset($_POST['table']) && isset($_POST['unit_code']) && isset($_POST['row'])) {
	$table = json_decode($_POST['table']);
	$unitcode = $_POST['unit_code'];
	if (file_exists('units/'.$unitcode.'.php')) {
		$unit = new $unitcode($unitcode);
		$validation = $unit->validateRequirements($table, $_POST['row']);
		if (strlen($validation) == 2) {
			echo "valid";
		} else {
			echo $validation;
		}
	} else { // no file for validation. default to valid
		echo "valid";
	}
}
?>