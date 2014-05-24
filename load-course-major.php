<?php 

include_once("database.php");

if(!empty($_GET['course_code']))
{
    $sql = $con->query("Select A.Major_ID, B.Major_Title from course_majors A LEFT OUTER JOIN major_sequences B on A.Major_ID = B.Major_ID Where A.Course_Code = '" . $_GET['course_code']. "' Order by A.Major_ID");
    
    $rows = array();
         
    if ($sql->num_rows > 0) { 
		while($rec = $sql->fetch_assoc()) { 
			$rows[] = $rec;
		}
	}
    //mysql_close($con);
     
    echo json_encode($rows);
}
?>