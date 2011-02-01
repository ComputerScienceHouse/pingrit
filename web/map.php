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
		<title>The Map - PingRIT Project</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
		<?php
		// Write the urhere style if we're on RIT's Network
		$ip = explode(".", $_SERVER['REMOTE_ADDR']);
		if($ip[0] == $octet1 && $ip[1] == $octet2) {
			echo("#mapContainer #d{$ip[2]}d{$ip[3]} {\n");
			echo("\t\t\tbackground-color:red;\n");
			echo("\t\t}\n");
		}
		?>
		</style>
	</head>
	<body>
		<h1>PingRIT Project
		<h3>IP Mapping Project for RIT</h3>
		<h4><a href='map.php'>The Map</a> | <a href='hosts.php'>Hostnames</a></h4>
		<div id="legend">
			Responded to ping <span class="ping">&nbsp;&nbsp;</span>
			 | Registered but no ping <span class="reg">&nbsp;&nbsp;</span>
			 | Not registered <span class="dne">&nbsp;&nbsp;</span>
			 | You are here <span class="urhere">&nbsp;&nbsp;</span>
		</div>
		<hr />

		<div id="mapContainer">
<?php
		// Build the query and execute it
		$query = "SELECT * FROM `ips` ORDER BY thirdo,fourtho";
		$result = mysql_query($query) or die(mysql_error());

		// Print out the boxes for each row
		while($row = mysql_fetch_array($result)) {
			echo("\t\t\t<div");
			echo(" id='d{$row['thirdo']}d{$row['fourtho']}'");
			echo(" class='" . strtolower($row['exists']) . "'></div>\n");
		}
?>

		</div></div>
		<div id="stats">
		<p>
			<?php
			// Generate query for stats
			$query = "SELECT * FROM `stats` ORDER BY `date` LIMIT 1";
			$result = mysql_fetch_array(mysql_query($query)) or die("Error with query");
			?>
			<b>General Statistics:</b><br />
			<b>Last IP Scan Completed On:</b> <? echo($result['date']); ?><br /><br />
			<b>IPs Tried:</b> <? echo($result['tried']); ?><br />
			<b>Responding IPs:</b> <? echo($result['ping'] . " (". round($result['ping'] / $result['tried'] * 100, 1) . "%)"); ?>
			 | <b>Registered IPs:</b> <? echo($result['reg'] . " (". round($result['reg'] / $result['tried'] * 100, 1) . "%)"); ?><br />
			<b>Non-registered IPs:</b> <? echo($result['dne'] . " (". round($result['dne'] / $result['tried'] * 100, 1) . "%)"); ?>
			 | <b>Web Servers Found:</b> <? echo($result['web'] . " (". round($result['web'] / $result['tried'] * 100, 1) . "%)"); ?><br />
			<br />
			<b>Time to Generate Map:</b> <? echo(round(microtime(true) - $timeStart, 4)); ?>s
		</p>
		</div>
	</body>
</html>
