<?php 

include_once("database.php");

if(!empty($_GET['campus_id']))
{
    $sql = $con->query("Select A.Course_Code, B.Course_Title from campus_course_offerings A LEFT OUTER JOIN courses B on A.Course_Code = B.Course_Code Where A.Campus_ID = '" . $_GET['campus_id']. "' Order by A.Course_Code");
    
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