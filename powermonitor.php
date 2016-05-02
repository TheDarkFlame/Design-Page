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
			<TD align="center">
				<H2>Main Supply</H2>
			</TD>
			<TD align="center">
				<H2>Appliance 1</H2>
			</TD>
		</TR>
		<TR>
			<TD colspan="2" align="center">
				<H3>Since: 1 Minute Ago</H3>
			</TD>
		</TR>
		<TR>
			<TD>
				<img src="graph.php?device=1&s_time=<?php print strtotime("-1 minute")//1 minute ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
			<TD>
				<img src="graph.php?device=2&s_time=<?php print strtotime("-1 minute")//1 minute ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
		</TR>
		<TR>
			<TD colspan="2" align="center">
				<H3>Since: 1 Day Ago</H3>
			</TD>
		</TR>
		<TR>
			<TD>
				<img src="graph.php?device=1&s_time=<?php print strtotime("-1 day")//1 week ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
			<TD>
				<img src="graph.php?device=2&s_time=<?php print strtotime("-1 day")//1 week ago?>&e_time=<?php print strtotime("now")?>">
			</TD>
		</TR>
		<TR>
			<TD colspan="2" align="center">
				<H3>Since: 1 Week Ago</H3>
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
			<TD colspan="2" align="center">
				<H3>Since: Beginning of Month</H3>
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