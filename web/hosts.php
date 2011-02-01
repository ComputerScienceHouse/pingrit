<?php
// OPEN THE PASSWORDS FILE ----------------------
if($file_handle = fopen("pingrit.conf", "r")) {
	// Read the lines
	while(($buffer = fgets($file_handle)) !== false) {
		// Skip commented lines
		if($buffer[0] == "#") { continue; }

		$buffer_exploded = explode("|", $buffer);
		if($buffer_exploded[0] == "password") {
			$password = rtrim($buffer_exploded[1]);
		}
		if($buffer_exploded[0] == "username") {
			$username = rtrim($buffer_exploded[1]);
		}
		if($buffer_exploded[0] == "dbLocation") {
			$dbLocation = rtrim($buffer_exploded[1]);
		}
		if($buffer_exploded[0] == "database") {
			$database = rtrim($buffer_exploded[1]);
		}
		if($buffer_exploded[0] == "octet1") {
			$octet1 = rtrim($buffer_exploded[1]);
		}
		if($buffer_exploded[0] == "octet2") {
			$octet2 = rtrim($buffer_exploded[1]);
		}
	}
} else {
	die("Could not open configuration file.");
}

// Is it set up correctly
if(!isset($password) || $password == "") {
	die("Database credentials not defined");
}
if(!isset($username) || $username == "") {
	die("Database credentials not defined");
}
if(!isset($dbLocation) || $dbLocation == "") {
	die("Database location not defined");
}
if(!isset($database) || $database == "") {
	die("Database not defined");
}
if(!isset($octet1) || $octet1 == "") {
	die("Home octet1 not defined");
}
if(!isset($octet2) || $octet2 == "") {
	die("Home octet2 not defined");
}

// Grab the start time of the script
$timeStart = microtime(true);

// Connect to mysql
$handle = mysql_connect($dbLocation, $username, $password) 
    or die("Fatal Error: Could not connect to database.<br />\n" 
    . mysql_error());
mysql_select_db($database) or die("Fatal Error: Could not select DB.<br />\n"
    . mysql_error());
?>
<html>
	<head>
		<title>Hostnames - PingRIT Project</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<h1>PingRIT Project
		<h3>IP Mapping Project for RIT</h3>
		<h4><a href='map.php'>The Map</a> | <a href='map.php'>Hostnames</a></h4>
		<?php
		// Only generate the domain names if we aren't passed a domain
		if(!isset($_GET['domain'])) {
			// Generate and run the query
			$query = "SELECT DISTINCT `domain` FROM `ips` WHERE `domain` != '' ORDER BY `domain`";
			$result = mysql_query($query) 
			    or die("Fatal Error: Query Failed");
			
			echo("\t\t");
			// Output the different domains
			while($domain = mysql_fetch_array($result)) {
				echo("<a href='?domain={$domain[0]}'>{$domain[0]}</a> | ");
			}
			echo("\n");
		} else {
			// Sanitize the input
			$domain = $_GET['domain'];
			$domain = mysql_real_escape_string($domain);
		
			// Start unordered list
			echo("<h4>{$domain}</h4>\n");
			echo("<ul>\n");
		
			// Generate and run a query to output hostnames at a given domain
			$hostquery = "SELECT * FROM `ips` WHERE `domain` = '$domain' ORDER BY `hostname`";
			$hostresult = mysql_query($hostquery) or die(mysql_error());
			while($row = mysql_fetch_array($hostresult)) {
				if($row['web'] != 0) {
					echo("<li>");
					echo("<a href='http://{$row['hostname']}.{$row['domain']}'>");
					echo($row['hostname'] . " .".$domain);
					echo("</a>");
					echo("</li>\n");
				} else {
					echo("<li>");
					echo($row['hostname'] . " .".$domain);
					echo("</li>\n");
				}
			}
			
			// End unordered list
			echo("</ul>\n");
		}
		
		// Calculate time taken
		$timeTaken = microtime(true) - $timeStart;
		echo("<h4>Time Elapsed: ".round($timeTaken,4)."s</h4>");
		?>
	</body>
</html>
