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
		echo("<h4>Time Elapsed: {$timeTaken} ms</h4>");
		?>
	</body>
</html>
