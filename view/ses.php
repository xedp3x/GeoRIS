<h1>Session:</h1>
<form>
	<input type=submit name=reset value="Reset" />
	<input type=submit value="Reload" />
</form>
<?php

if ($_GET["reset"]){
	$_SESSION = array();
}
DUMP($_SESSION);