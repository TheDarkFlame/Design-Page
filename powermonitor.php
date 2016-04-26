<?PHP 
	require_once './functions.php';
	check_login();
 ?>
<HTML>
	<HEAD>
	<TITLE>Graphs</TITLE>
	</HEAD>
	<BODY>
		<TABLE>
		<TR>
			<TD>
				<H2>Main Supply</H2>
			</TD>
			<TD>
				<H2>Appliance 2</H2>
			</TD>
		</TR>
		<TR>
			<TD>
				<img src="graph.php?device=1&s_time=<?php print strtotime("-24 hours")//1 day ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
			<TD>
				<img src="graph.php?device=2&s_time=<?php print strtotime("-24 hours")//1 day ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
		</TR>
		<TR>
			<TD>
				<img src="graph.php?device=1&s_time=<?php print strtotime("-1 week")//1 week ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
			<TD>
				<img src="graph.php?device=2&s_time=<?php print strtotime("-1 week")//1 week ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
		</TR>
		<TR>
			<TD>
				<img src="graph.php?device=1&s_time=<?php print strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")))//start of the month?>&e_time=<?php print strtotime("now")?>">
			</TD>
			<TD>
				<img src="graph.php?device=2&s_time=<?php print strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")))//start of the month?>&e_time=<?php print strtotime("now")?>">
			</TD>
		</TR>
	</TABLE>
		<A HREF = "login.php?logout=logout">Log Out</A>
		<A href="./main.php">Main Page</A>
	</BODY>
</HTML>