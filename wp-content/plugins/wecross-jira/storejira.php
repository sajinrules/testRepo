<?php
	require($_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );
	$nr = $_REQUEST['nr'];
	$url = $_REQUEST['url'];
	$path = $_REQUEST['cur'];
	
	echo $path;
	echo "<div class='alert alert-warning'>";
	echo saveJiraLink($url, $nr, $path);
	echo "</div>";
	