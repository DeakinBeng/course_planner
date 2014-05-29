<?php
	session_start();
	
	include_once("header.php");
	
	// Store the previous page post data in Session (Fetch Campus Information)
	if(isset($_POST["studyMode"]))
	{
		$_SESSION['studyMode'] = @$_POST["studyMode"];
		$sql = $con->query("SELECT * from campus where Campus_ID = '" . $_SESSION['studyMode'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['campus_name'] = $row['Name'];
		} 
	}
	
	// Fetch Course Information
	if(isset($_POST["courseSelection"]))
	{
		$_SESSION['course_selection'] = @$_POST["courseSelection"];
		$sql = $con->query("SELECT * from courses where Course_Code = '" . $_SESSION['course_selection'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['course_name'] = $row['Course_Title'];
		} 
	}
	
	// Fetch Major Sequence Information
	if(isset($_POST["majorSelection"]))
	{
		$_SESSION['major_selection'] = @$_POST["majorSelection"];
		$sql = $con->query("SELECT * from major_sequences where Major_ID = '" . $_SESSION['major_selection'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['major_sequence'] = $row['Major_Title'];
		}
	}
	
	// Check month greater than March for Next commencing year
	if(date('n') >= 3) 
		$_SESSION['commencing_year'] = date('Y', strtotime('+1 year'));
	else
		$_SESSION['commencing_year'] = date('Y');
		
	if(@$_SESSION['major_sequence'] == '')
		header("Location: index.php");
		
?>

<div class="wrapper">
	<div class="left-sidebar">
		<div class="search-unit">
			<p><strong>Search for a unit</strong></p>
			Enter a unit code
			<form method="post" id="frmSearch" name="frmSearch" action="">
				<!-- Load the searched value -->
				<input type="text" id="searchCourse" name="searchCourse" value="<?php echo @$_POST['searchCourse']; ?>" />
				<input type="submit" id="btnSearch" name="btnSearch" value="Search" />
			</form>
		</div>
		
		<br/>
		<div class="left">
			<table>
				<?php
					$search_results = 0;
					// Search and List the units as per search text
					/*$query = "SELECT A.Unit_Code, A.Unit_Title, B.Core from units A LEFT OUTER JOIN major_units B on A.Unit_Code = B.Unit_Code 
					WHERE  (B.Core=0 AND B.Major_ID = '". @$_SESSION['major_selection'] ."' AND A.Unit_Code like '%" . @$_POST['searchCourse'] . "%') 
					OR (B.Core=0 AND B.Major_ID <> '". @$_SESSION['major_selection'] ."' AND A.Unit_Code like '%" . @$_POST['searchCourse'] . "%') 
					Group by A.Unit_Code, A.Unit_Title";*/
					$query = "SELECT A.Unit_Code, A.Unit_Title, B.Core from units A LEFT OUTER JOIN major_units B on A.Unit_Code = B.Unit_Code 
					WHERE A.Unit_Code like '%" . @$_POST['searchCourse'] . "%' AND
					A.Unit_Code NOT IN (". @$_SESSION['added_units'] .") Group by SUBSTRING(A.Unit_Code, 4, 3), A.Unit_Title";
					$sql = $con->query($query); 
					if ($sql->num_rows > 0) { 
						$search_results = $sql->num_rows;
						while($row = $sql->fetch_assoc()) {
							echo '<tr><td><div id="'. $row["Unit_Code"] . '" class="item"><span class="hidden"><img class="cross" onClick="return removeFromTable(this);" src="images/cross.png"></span>'. $row['Unit_Code'] . ' - ' . $row['Unit_Title'] . '</div></td>
								<td><a href="#" onClick="javascript:newWindow('.$row["Unit_Code"].');" class="view-detail">View Detail</a></td></tr>';
						}
					}
					else
						echo '<tr><td><div>Searched unit is not found or already in Core units.</div></td></tr>';
				?>
			</table>
		</div>
		<p><?php echo $search_results . " Search Results"; ?></p>
		<p>Course Status: <span id="course-status">Incomplete</span></p>
	</div>
	
	<div class="page-main">
		<div class="course-info">
			<!-- Display information from previous post data stored in Session -->
			COURSE CODE : <?php echo @$_SESSION['course_selection']; ?><br/>
			COURSE NAME : <?php echo @$_SESSION['course_name']; ?><br/>
			MAJOR SEQUENCE : <?php echo @$_SESSION['major_sequence']; ?><br/>
			COMMENCING : <?php echo @$_SESSION['commencing_year']; ?><br/>
			CAMPUS : <span id="campus"><?php echo @$_SESSION['campus_name']; ?></span><br/>
			<!--VALID : <span id='valid'>TRUE</span>-->
		</div>
	
		<p><strong>Click and drag a Unit in the Course Planner Template</strong></p>
		<div class="right">
			<?php 
				// Check if selected major has Satey Induction Program unit and display Note
				$sql = $con->query("SELECT * FROM major_units where Unit_Code = 'SIT010' and Major_ID = '" . $_SESSION['major_selection'] . "'"); 
				if ($sql->num_rows > 0) { 
					$row = $sql->fetch_assoc();
					?>
					<p class="note">Note: Need to Complete SIT010 Safety Induction Program (0 credit-point compulsory unit)</p>
					<script>
					$(".left").css('height', '478px'); // align left table with right table
					</script>
					
				<?php	
				}
			?>
			<table id="template">
			<?php
			
			$added_units = "''";
			// 3 Years Loop for Trimester 1 and Trimester 2
			for($i=1; $i<=3; $i++)
			{
			?>
				<tr>
					<td rowspan="2" class="Y<?php echo $i; ?>">Y<?php echo $i; ?></td>
					<td class="T1">T1</td>
					<?php 
					// Get Unit information for Trimester 1
					$query = "SELECT A.Unit_Code, B.Unit_Title, A.Core, C.Trimester1, C.Trimester2, A.Year FROM `major_units` A 
						LEFT OUTER JOIN units B on A.Unit_Code = B.Unit_Code
						LEFT OUTER JOIN availabilities C on A.Unit_Code = C.Unit_Code
						Where C.Trimester1 = 1 AND A.Core = 1 AND A.Unit_Code NOT IN (". $added_units .")
						AND A.Major_ID = '" . $_SESSION['major_selection'] . "' AND C.Campus_ID = '". $_SESSION['studyMode'] . "' Order by A.Unit_Code, A.Core desc";
					$sql = $con->query($query);
					if ($sql->num_rows > 0) { 
						$max_count = 1;
						while($row = $sql->fetch_assoc()) {
							// Fetch Level/Year from the Unit Code
							$unit_year = substr($row["Unit_Code"], 3, 1);
							if($unit_year == $i)
							{
								// Maximum 4 column/units per Trimester
								if($max_count <= 4)
								{
									// Check if Core Unit and not allowing Drag and Drop
									if($row["Core"] == '1') {
										echo '<td class="core"><div id="'. $row["Unit_Code"] . '">'. $row["Unit_Code"]. ' - ' . $row["Unit_Title"] . '</div></td>';
										if($added_units == '')
											$added_units .= "'".$row["Unit_Code"]."'";
										else
											$added_units .= ', ' . "'".$row["Unit_Code"]."'";
									}
								}
								$max_count++;
							}
						}
						while($max_count <= 4)
						{
							// Add Empty Column for electives
							echo '<td class="drop"></td>';
							$max_count++;
						}
					}
					?>
				</tr>
				<tr>
					<td class="T1">T2</td>
					<?php 
					// Get Unit Information for Trimester 2
					$query = "SELECT A.Unit_Code, B.Unit_Title, A.Core, C.Trimester1, C.Trimester2, A.Year FROM `major_units` A 
						LEFT OUTER JOIN units B on A.Unit_Code = B.Unit_Code
						LEFT OUTER JOIN availabilities C on A.Unit_Code = C.Unit_Code
						Where C.Trimester2 = 1 AND A.Core = 1 AND A.Unit_Code NOT IN (". $added_units .")
						AND A.Major_ID = '" . $_SESSION['major_selection'] . "' AND C.Campus_ID = '". $_SESSION['studyMode'] . "' Order by A.Unit_Code, A.Core desc";
					$sql = $con->query($query);
					if ($sql->num_rows > 0) { 
						$max_count = 1;
						while($row = $sql->fetch_assoc()) {
							// Fetch Level/Year from the Unit Code
							$unit_year = substr($row["Unit_Code"], 3, 1);
							if($unit_year == $i)
							{
								// Maximum 4 column/units per Trimester
								if($max_count <= 4)
								{
									// Check if Core Unit and not allowing Drag and Drop
									if($row["Core"] == '1') {
										echo '<td class="core"><div id="'. $row["Unit_Code"] . '">'. $row["Unit_Code"]. ' - ' . $row["Unit_Title"] . '</div></td>';
										if($added_units == '')
											$added_units .= "'".$row["Unit_Code"]."'";
										else
											$added_units .= ', ' . "'".$row["Unit_Code"]."'";
									}
								}
								$max_count++;
							}
						}
						while($max_count <= 4)
						{
							// Add Empty Column for electives
							echo '<td class="drop"></td>';
							$max_count++;
						}
					}
					?>
				</tr>
			<?php
			}
			?>
			</table>
			<!-- Store core units that are already added in the template -->
			<?php $_SESSION['added_units'] = $added_units; ?>
		</div>
		<!-- <img class="bin" src="images/trash5.png" /> 
		<br/>
		<p>Click and drag a unit in trash to remove from Planner.</p> -->
		<div class="button-right">
			<input type="button" id="btnSave" name="btnSave" value="SAVE" />
			<input type="button" id="btnPrint" name="btnPrint" value="PRINT" />
		</div>
	</div>
	
</div>

<script>
	// Display the unit detail information in a new window
	function newWindow(unit) {
		window.open("cinfoviewer.php/?code="+$(unit).attr("id"), "", "menubar=0, resizable=0,dependent=0,status=0,width=660,height=700,left=10,top=10"); 

		return false;
	} 
	
	function removeFromTable(item) {
			var source = $(item).parent().parent();
			var c = source.clone(true);
			c.removeClass('assigned');
			c.draggable({
				revert:true
			});
			c.children("span").addClass('hidden'); // hide the x
			$('.left table tbody').prepend("<tr><td></td><td></td></tr>"); // create empty table row for insertion
			var firsttd = $('.left table tbody tr td').first();
			firsttd.append(c); // add unit back into list
			firsttd.next().append('<a href="#" onClick="return newWindow('+c.prop('id')+');" class="view-detail">View Detail</a>');
			source.remove();
	}
	
	$(function(){
		// Set the left Unit List draggable
		$('.left .item').draggable({
			revert:true,
			proxy:'clone'
		});
		// Set the Right Template table columns droppable
		$('.right td.drop').droppable({
			onDragEnter:function(){
				// Add hover effect style on table when trying to drop unit
				$(this).addClass('over');
			},
			onDragLeave:function(){
				// Remove hover effect style on table when trying to drop unit
				$(this).removeClass('over');
			},
			onDrop:function(e,source){
				var arr= [];
				var row = 0;
				var col = 0;
				var oddrow = true;
				var count = 0;
				var curObj = $(this);
				var dropLocX;
				// Check each row and column of table to Fetch the units added in template
				$(".right table tr").each(function() {
					arr[row] = [];
					$(this).children().each(function() {
						if (((oddrow && col > 1 && col <= 5) || (!oddrow && col > 0 && col <= 4)) && $(this).text().length > 0) {
							// Collect the added units in an array
							arr[row][oddrow ? col - 2 : col - 1] = $(this).text().substring(0, 6);
							count++;
						}
						if (curObj.is($(this))) {
							dropLocX = row;
						}
						col++;
					});
					col = 0;
					row++;
					oddrow = !oddrow;
				});
				// Use ajax to pass the unit array to another PHP file for validation check
				var currentCell = $(this);
				$.ajax({
					type: "POST",
					url: "validation/validate.php",
					data: {table : JSON.stringify(arr), unit_code : $(source).attr("id"), row : dropLocX, campus : $('span#campus').text()},
					success: function( msg ){
						if ($(source).parent().html() != null) {
						if (msg != "valid") { // Does not meet prereq/coreq and incompatibility tests
							var msgArr = msg.split("|");
							var errStr = "<span style='color: red;'>";
							if (msgArr[0].length > 0) {
								errStr += ("<br />Prerequisites: " + msgArr[0]);
							}
							if (msgArr[1].length > 0) {
								errStr += ("<br />Corequisites: " + msgArr[1]);
							}
							if (msgArr[2].length > 0) {
								errStr += ("<br />Incompatibilities: " + msgArr[2]);
							}
							if (msgArr[3].length > 0) {
								errStr += ("<br />" + msgArr[3]);
							}
							errStr += "</span>";
							new Messi('Unit cannot be drop in template due to:' + errStr, {title: 'Error', titleClass: 'anim error', buttons: [{id: 0, label: 'Close', val: 'X'}]});
							currentCell.removeClass('over');
							//removeFromTable($("#template div#" + $(source).attr("id")), false);
						} else {
							// existing units move function in template table from one column to another
							if ($(source).hasClass('assigned')) {
								if(currentCell.has('.item.assigned').length == 0) {
									currentCell.append(source);
								}
							} else {
								// Check for duplicate units and Add New unit dropped from unit list to template table
								if($("#template").find("div#" + $(source).attr("id")).length == 0) {
										var c = $(source).clone().addClass('assigned');
										c.children("span").removeClass('hidden');
										currentCell.empty().append(c);
										c.draggable({
											revert:true
										});
										$(source).parent().parent().remove(); // remove unit from list
								}
								else {
									new Messi('Unit already exists in the template.', {title: 'Error', titleClass: 'anim error', buttons: [{id: 0, label: 'Close', val: 'X'}]});
								}
							}
						}
						}
					},
					error: function()
					{
						new Messi('Something went wrong!', {title: 'Error', titleClass: 'anim error', buttons: [{id: 0, label: 'Close', val: 'X'}]});
					}
				});
				$(this).removeClass('over');
			}
		});
		function item_dropped() {
			alert('test');
		}
		
		/*
		// Set trash icon droppable for removing units
		$('.bin').droppable({
			// Drop Accept only of units with class name "assigned"
			accept:'.assigned',
			onDragEnter:function(e,source){
				// Add Hover effect style on unit when moved to trash icon
				$(source).addClass('trash');
			},
			onDragLeave:function(e,source){
				// Remove Hover effect style on unit when moved to trash icon
				$(source).removeClass('trash');
			},
			onDrop:function(e,source){
				// If dropped on trash, remove the unit from source and add back into list i.e. template table
				c = $(source).clone(true);
				c.removeClass('trash');
				c.removeClass('assigned');
				c.children("span").addClass('hidden');
				c.draggable({
					revert:true
				});
				$('.left table tbody').prepend("<tr><td></td><td></td></tr>"); // create empty table row for insertion
				var firsttd = $('.left table tbody tr td').first();
				firsttd.append(c); // add unit back into list
				firsttd.next().append('<a href="#" onClick="return newWindow('+c.prop('id')+');" class="view-detail">View Detail</a>');
				$(source).remove();
			}
		});
		*/
	});
</script>

<?php
	include_once("footer.php");
?>