<?php

// Grab the start time of the script
$timeStart = microtime(true);

// Connect to mysql
$handle = mysql_connect("localhost", "pingrit", "pingrit1") 
    or die("Fatal Error: Could not connect to database.<br />\n" 
    . mysql_error());
mysql_select_db("pingrit") or die("Fatal Error: Could not select DB.<br />\n"
    . mysql_error());
?>
<html>
	<head>
		<title>The Map - PingRIT Project</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<h1>PingRIT Project
		<h3>IP Mapping Project for RIT</h3>
		<h4><a href='map.php'>The Map</a> | <a href='map.php'>Hostnames</a></h4>
		<div id="mapContainer">
<?php
		// Build the query and execute it
		$query = "SELECT * FROM `ips` ORDER BY thirdo,fourtho";
		$result = mysql_query($query) or die(mysql_error());

		// Print out the boxes for each row
		while($row = mysql_fetch_array($result)) {
			echo("\t\t\t<div");
			echo(" id='{$row['thirdo']}.{$row['fourtho']}'");
			echo(" class='" . strtolower($row['exists']) . "'></div>\n");
		}
?>
		</div>
		<h4>Time Elapsed: <? echo(microtime(true) - $timeStart); ?>s</h4>
	</body>
</html>
