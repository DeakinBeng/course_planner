<?php
	include_once("database.php");
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Course Planner</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript">

		$(function() {

			  $('#courseSelection').change(function(){
				//alert("load-course-major.php?course_code=" + $('#courseSelection').val());
				load_course_major('load-course-major.php?course_code=' + $('#courseSelection').val());
			});

		});
		/*function to load a list*/
		function load_course_major(url)
		{
			$("#majorSelection").empty();
			$.getJSON(url,null,function(data)
			{
				$.each(data, function(i,obj)
				{
					$("#majorSelection").append($('<option></option>').val(obj['Major_ID']).html(obj['Major_Title']));
				});
			});
		}
	</script>
</head>

<body>
<div class="wrapper">
	<div class="page-title">
		<h1>STUDENT COURSE PLANNER</h1>
		<p>Plan out your future course with the Deakin Course Planner.</p>
	</div>
</div>