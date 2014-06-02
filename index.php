<?php
	session_start();
	
	include_once("header.php");
?>

<script type="text/javascript">

		$(function() {
			// Load default Course and Major on Page Load
			load_course('load-campus-course.php?campus_id=' + $('#studyMode').val());
			
			// Load related course and major on Change of drop down menu selection
			$('#studyMode').change(function(){
				load_course('load-campus-course.php?campus_id=' + $('#studyMode').val());
			});
			$('#courseSelection').change(function(){
				//alert("load-course-major.php?course_code=" + $('#courseSelection').val());
				load_course_major('load-course-major.php?course_code=' + $('#courseSelection').val());
			});
		});
		
		// Load courses offered by selected campus
		function load_course(url)
		{
			$("#courseSelection").empty();
			$.getJSON(url,null,function(data)
			{
				$.each(data, function(i,obj)
				{
					$("#courseSelection").append($('<option></option>').val(obj['Course_Code']).html(obj['Course_Title']));
				});
				load_course_major('load-course-major.php?course_code=' + $('#courseSelection').val()); // load majors once course has been loaded
			});
		}
		// Load a course major
		function load_course_major(url)
		{
			$("#majorSelection").empty();
			$.getJSON(url,null,function(data)
			{
				$.each(data, function(i,obj)
				{
					$("#majorSelection").append($('<option></option>').val(obj['Major_ID']).html(obj['Major_Title']));
					//$('#majorSelection').prop('disabled', false);
				});
			});
		}
		
		// Check Major Selection before proceeding to next page.
		function forwardCourse()
		{
			if($("#majorSelection").val() == "" || $("#majorSelection").val() == null) {
				alert("Select Course and Major to proceed.");
				return false;
			}
			else
				return true;
		}
</script>

<div class="wrapper">
	<form method="post" id="frmSearch" name="frmSearch" onsubmit="return forwardCourse()" action="course-template.php">
    	<h1>Campus Or online Selection</h1>
		<select id="studyMode" name="studyMode">
        	<?php 
				// Load campus locations
				$sql = $con->query("SELECT * from campus"); 
				if ($sql->num_rows > 0) { 
					while($row = $sql->fetch_assoc()) { 
						echo '<option value='. $row['Campus_ID'] . '>'. $row['Name'] . '</option>';
					}
				} 
			?>            
        </select>
        
		<!-- Empty Course and Major since they will be loaded on campus and course selection respectively. -->
        <h1>Course Selection</h1>
        <select id="courseSelection" name="courseSelection">
            <option value=""></option>
        </select>
        <h1>Major Selection</h1>
        <select id="majorSelection" name="majorSelection">
        	<option value=""></option>
        </select>
		<br/>
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Generate Plan" />
    </form>
</div>

<?php
	include_once("footer.php");
?>