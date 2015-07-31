<?php
	$servername = "213.187.242.145";
	$username = "wecrossdata";
	$password = "Rpr5VCSmte3K99ZK";
	$dbname = "dataviews";
	$conn = new mysqli($servername, $username, $password,$dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
?>