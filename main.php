<HTML>
<HEAD>
	<TITLE>Power Monitor</TITLE>
	<LINK REL = Stylesheet TYPE="text/css" HREF="styles.css">

	<?PHP
		require_once('functions.php');
		
		//check if we want to download the setup utility
		if(isset($_POST['setupImage'])){
			exec("setup.exe");
		}
		
		//connect to the mysql database
		$mysqli= new mysqli("127.0.0.1","root","","design_db");
		if($mysqli->connect_errno){//connect to db
			trigger_error($msqyli->connect_error);
		}
		//get only the last entry for a device
		function query_latest_entry(&$row,$Device_ID){
			global $mysqli;
			$SQL_Message = "SELECT `Power_Reading`, `Time_Recorded` FROM `power_stats` WHERE `Device_ID` = '".$Device_ID."' ORDER BY `Time_Recorded` DESC LIMIT 1";
			if($result = $mysqli->query($SQL_Message)){
				$row = $result->fetch_assoc();
			}
			else{//if query fails print error
				trigger_error($mysqli->error." ".$SQL_Message);
			}
		}
	?>

	
</HEAD>
<BODY>

	
	<H1>Power Monitor</H1>
	<TABLE>
	<TBODY>
		<TR>
			<TD>
				<TABLE class="GUIblock">
					<TH colspan="2">Main Zone</TH>
					<TR>
						<TD>Total Consumption over last 24 hours:</TD>
						<TD>
							<?PHP
								print time_bounded_energy_use(strtotime("-1 day"), strtotime("+0 seconds"), "1")
							?>
						</TD>
					</TR>
					<TR>
						<TD>Latest Power Reading:</TD>
						<TD>
							<?PHP
								$result = array();
								query_latest_entry($row,"1");
								print $row['Power_Reading']." (at ".$row['Time_Recorded'].")";
							?></TD>
					</TR>
					<TR>
						<TD>Total Consumption This Month:</TD>
						<TD>
							<?PHP
								$use_dev1_monthly = time_bounded_energy_use(strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y"))), strtotime("+0 seconds"), "1");
								print $use_dev1_monthly;
							?>
						</TD>
					</TR>
					<TR>
						<TD>Projected Total Month Cost:</TD>
						<TD>
							<?PHP
								$month_time_elapsed = strtotime("+0 seconds") - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								$month_time_total = strtotime("+1 month",mktime('0','0','0',date("n"),'0',date("Y"))) - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								print $use_dev1_monthly * $month_time_total / $month_time_elapsed
							?>
					</TR>
				</TABLE>
			</TD>

		</TR>
		<TR>
			<TD>
				<TABLE class="GUIblock">
					<TH>Zone 1</TH>
					<TR>
						<TD>Consumption over last 24 hours:</TD>
					</TR>
					<TR>
						<TD>Latest Power Reading:</TD>
					</TR>
					<TR>
						<TD>Current Month Cost:</TD>
					</TR>
					<TR>
						<TD>Projected Total Month Cost:</TD>
					</TR>
					<TR>
						<TD>Current Percentage of Total:</TD>
					</TR>
				</TABLE>
			</TD>

		</TR>
		<TR>
			<TD>
				<FORM METHOD="PUSH" ACTION="main.php">
					<LABEL for="cost_of_power">set power cost : R</LABEL>
					<INPUT type = "text" name="cost_of_power">
					<INPUT type = "button" name = "submit_cost" value="submit">
				</FORM>
				</TD>
		</TR>
		<TR>
					<TD>
				<A href="./powermonitor.php">See Graphs</A>
				<A href="./schedules.php">Schedule Management Page</A>
				<A href="./registeruser.php">User Management Page</A>
				<A href="./setup.exe">Setup Utility</A>
			</TD>
		</TR>
	</TBODY>
	</TABLE>
</BODY>
</HTML>