<HTML>
	<HEAD>
	<TITLE>Graphs</TITLE>
	</HEAD>
	<BODY>
		<H2>Appliance 1</H2>
		<img src="graph.php?device=1&s_time=<?php print strtotime("-1 week")//1 week ago?>&e_time=<?php print strtotime("now")?>">
		<p>
		<img src="graph.php?device=1&s_time=<?php print strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")))//start of the month?>&e_time=<?php print strtotime("now")?>">

		<H2>Appliance 2</H2>
		<img src="graph.php?device=2&s_time=<?php print strtotime("-1 week")//1 week ago?>&e_time=<?php print strtotime("now")?>">
		<p>
		<img src="graph.php?device=2&s_time=<?php print strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")))//start of the month?>&e_time=<?php print strtotime("now")?>">
	
	
	
		<P>
		<A HREF = logoutpage.php>Log Out</A>
		<A href="./main.php">Main Page</A>
	</BODY>
</HTML>