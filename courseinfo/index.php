<script> 
function newWindow() { 
window.open("CInfoViewer.php/?code="+document.getElementsByName('code')[0].value+"&cu="+(document.getElementsByName('cu')[0].checked ? "course" : "unit"), "", "menubar=0, resizable=0,dependent=0,status=0,width=660,height=700,left=10,top=10"); 

return false;
} 
</script>
<?php

echo "<form action='CInfoViewer.php' method='GET' onSubmit='return newWindow()' >
	Code: <input type='text' name='code' autofocus autocomplete='off' />
	<br />
	<input type='radio' name='cu' value='course' checked>Course &nbsp;
	<input type='radio' name='cu' value='unit'>Unit
 <input type='submit'/>
</form>";
?>