<HTML>
<HEAD>
	<TITLE>Power Monitor</TITLE>
	<LINK REL = Stylesheet TYPE="text/css" HREF="styles.css">

	<?PHP
		require_once('functions.php');
		check_login();
		
		if(isset($_POST["submit_cost"])){		
			if(is_numeric($_POST["cost_of_power"])){//if price is set and is numeric
				$price = abs($_POST["cost_of_power"]);//ensure the value is positive
				$price = $price/100; //convert cents/Wh->Rands/Wh
				$price = $price/1000; //convert Rands/kWh -> Rands/Wh
				setflag("price",$price);//save price in R/Wh
			}
		}
		if(!(isset($price))){//get price from database if not set
			$price = checkflag("price");
		}
		
		if(!(isset($operating_mode))){//get operating mode from database if not set
			$operating_mode = checkflag("operating_mode");
		}
		
		if(isset($_POST["on_button"])){
			$operating_mode = "on";
			setflag("operating_mode",$operating_mode);
			setflag("mode_modified","yes");//set the mode_modified flag
		}
		if(isset($_POST["off_button"])){
			$operating_mode = "off";
			setflag("operating_mode",$operating_mode);
			setflag("mode_modified","yes");//set the mode_modified flag
		}
		if(isset($_POST["auto_button"])){
			$operating_mode = "scheduled";
			setflag("operating_mode",$operating_mode);
			setflag("mode_modified","yes");//set the mode_modified flag
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
<BODY class="centeredWidthLarge">

	
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
								$quantity = time_bounded_energy_use(strtotime("-1 day"), strtotime("+0 seconds"), "1")/3600;//1 watt-hour=3600 joules
								printf ("%.0f Wh",$quantity);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$quantity*$price);
							?>
						</TD>
					</TR>
					<TR>
						<TD>Latest Power Reading:</TD>
						<TD>
							<?PHP
								$result = array();
								query_latest_entry($row,"1");
								printf("%.0f Watts (at %s)",$row['Power_Reading'],$row['Time_Recorded']);
								$dev1_latest = $row['Power_Reading'];
							?></TD>
					</TR>
					<TR>
						<TD>Total Consumption This Month:</TD>
						<TD>
							<?PHP
								$use_dev1_monthly = time_bounded_energy_use(strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y"))), strtotime("+0 seconds"), "1")/3600;
								printf ("%.0f Wh",$use_dev1_monthly);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$use_dev1_monthly*$price);
							?>
						</TD>
					</TR>
					<TR>
						<TD>Projected Total Month Consumption:</TD>
						<TD>
							<?PHP
								$month_time_elapsed = strtotime("+0 seconds") - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								$month_time_total = strtotime("+1 month",mktime('0','0','0',date("n"),'0',date("Y"))) - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								$quantity = $use_dev1_monthly * $month_time_total / $month_time_elapsed;
								printf("%.0f Wh",$quantity);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$quantity*$price);
							?>
						</TD>
					</TR>
				</TABLE>
			</TD>

		</TR>
		<TR>
			<TD>
				<TABLE class="GUIblock">
					<TH>Appliance</TH>
										<TR>
						<TD>Total Consumption over last 24 hours:</TD>
						<TD>
							<?PHP
								$quantity = time_bounded_energy_use(strtotime("-1 day"), strtotime("+0 seconds"), "2")/3600;//1 watt-hour=3600 joules
								printf ("%.0f Wh",$quantity);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$quantity*$price);
							?>
						</TD>
					</TR>
					<TR>
						<TD>Latest Power Reading:</TD>
						<TD>
							<?PHP
								$result = array();
								query_latest_entry($row,"2");
								printf("%.0f Watts (at %s)",$row['Power_Reading'],$row['Time_Recorded']);
								$dev2_latest = $row['Power_Reading'];
							?></TD>
					</TR>
					<TR>
						<TD>Total Consumption This Month:</TD>
						<TD>
							<?PHP
								$use_dev2_monthly = time_bounded_energy_use(strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y"))), strtotime("+0 seconds"), "2")/3600;
								printf ("%.0f Wh",$use_dev2_monthly);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$use_dev1_monthly*$price);
							?>
						</TD>
					</TR>
					<TR>
						<TD>Projected Total Month Consumption:</TD>
						<TD>
							<?PHP
								$month_time_elapsed = strtotime("+0 seconds") - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								$month_time_total = strtotime("+1 month",mktime('0','0','0',date("n"),'0',date("Y"))) - strtotime("+1 day",mktime('0','0','0',date("n"),'0',date("Y")));
								$quantity = $use_dev2_monthly * $month_time_total / $month_time_elapsed;
								printf("%.0f Wh",$quantity);
							?>
						</TD>
						<TD>
							<?PHP
								printf ("R%.2f",$quantity*$price);
							?>
						</TD>
					</TR>
					<TR>
						<TD>Current Percentage of Total:</TD>
						<TD>
							<?PHP
							if ($dev1_latest!=0){
								printf ("%f.1%%",($dev2_latest*100)/$dev1_latest);
							}
							else printf("%%");
							?>
						</TD>
					</TR>

				</TABLE>
			</TD>

		</TR>
		<TR>
			<TD>
				<?PHP
					//this area needs to be finished!!!
					$SQL_Message = "SELECT `Device_ID`, `Flag_ID`, `Flag_Value` FROM `flags` WHERE `Flag_ID`='device_connected'";
					if($result = $mysqli->query($SQL_Message)){
						$row = $result->fetch_assoc();
					}
					else{//if query fails print error
						trigger_error($mysqli->error." ".$SQL_Message);
					}
				?>
			</TD>
		</TR>
		<TR>
			<TD>
				<FORM METHOD="POST" ACTION="main.php">
					<LABEL for="cost_of_power">set power cost per kiloWatt-hour (c/kWh):</LABEL>
					<INPUT type = "text" name="cost_of_power" placeholder=<?php print "'".$price*"100000"."'"?>>c
					<INPUT type = "submit" name = "submit_cost" value="submit">
				</FORM>
				</TD>
		</TR>
		<TR>
			<TD colspan=3>
				<TABLE>
					<TR>
						<TH colspan=3>Operating mode</TH>
					</TR>
					<TR>
					<?PHP
						$stylestring_start = '"background-image:url(./resources/button_';
						$stylestring_end = '.png);width: 71px;height: 40px; background-position: 0px 0px;	border: 0px;"';
						if ($operating_mode=="on"){
							$style_on = $stylestring_start."ON_grey".$stylestring_end;
						}
						else{
							$style_on = $stylestring_start."ON".$stylestring_end;
						}
						
						if ($operating_mode=="off"){
							$style_off = $stylestring_start."OFF_grey".$stylestring_end;
						}
						else{
							$style_off = $stylestring_start."OFF".$stylestring_end;
						}
						
						if ($operating_mode=="scheduled"){
							$style_auto = $stylestring_start."AUTO_grey".$stylestring_end;
						}
						else{
							$style_auto = $stylestring_start."AUTO".$stylestring_end;
						}
					?>
						<TD>
							<FORM METHOD="POST" ACTION="main.php">
								<INPUT type="submit" name="on_button" value="" style=<?PHP print $style_on?>>
							</FORM>
						</TD>
						<TD>
							<FORM METHOD="POST" ACTION="main.php">
								<INPUT type="submit" name="auto_button" value="" style=<?PHP print $style_auto?>>
							</FORM>
						</TD>
						<TD>
							<FORM METHOD="POST" ACTION="main.php">
								<INPUT type="submit" name="off_button" value="" style=<?PHP print $style_off?>>
							</FORM>
						</TD>
					</TR>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD>
				<A href="./powermonitor.php">See Graphs</A>
				<A href="./schedules.php">Schedule Management Page</A>
				<A href="./registeruser.php">User Management Page</A>
				<A HREF = "login.php?logout=logout">Log Out</A>
			</TD>
		</TR>
	</TBODY>
	</TABLE>
</BODY>
</HTML>