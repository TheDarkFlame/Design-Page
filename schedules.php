<HTML>
<HEAD>
	<LINK REL = Stylesheet TYPE="text/css" HREF="styles.css">
</HEAD>
<BODY  class="centeredWidthLarge">
	<?PHP 
	require_once('functions.php');
	check_login();//check user is logged in

	//handle the deleting of a schedule
	if(isset($_POST['delete_entries'])){
		if(isset($_POST['active_schedules'])){
			$deleteList=array();
			
			$mysqli = new mysqli("127.0.0.1","root","","design_db");
			if($mysqli->connect_errno){//connect to db
				trigger_error($msqyli->connect_error);
			}
			$deleteList = $_POST['active_schedules'];
			foreach ($deleteList as $listboxname){//http://stackoverflow.com/questions/2407284/how-to-get-multiple-selected-values-of-select-box-in-php
				//delete from db
				$SQL_Message = "DELETE FROM `schedules` WHERE `Entry_ID` = '".$listboxname."'";
				if( !(($mysqli->query($SQL_Message))) ){//query db
					trigger_error($mysqli->error." ".$SQL_Message);//if unsuccessful, print error
				}
			}
			setflag("schedule_modified","yes");//set the schedule_modified flag
			$mysqli->close();//close connection to db
		}
	}
	
	
	//handle the adding of a schedule
	if(isset($_POST['submit_schedule'])){
		$days=array();//(re)initialize the array of days selected
		$daySet=false;//checks if a day is set
		$deviceSet=false;//checks if a device id is set
		$Device_ID=array();
		$time_set=false;

		if( isset($_POST['start_time_h']) && isset($_POST['start_time_m']) && isset($_POST['end_time_h']) && isset($_POST['end_time_m']) ){
			$start_time_h = $_POST['start_time_h'];
			$start_time_m = $_POST['start_time_m'];
			$end_time_h = $_POST['end_time_h'];
			$end_time_m = $_POST['end_time_m'];
			$time_set = true;
			if ( ($end_time_h<$start_time_h) || (($end_time_m<$start_time_m)&&($end_time_h==$start_time_h)) ){//check that the start comes before the end
				$time_set = false;
				print "Start Time must be before End Time!!!<BR>";
			}
		}

		
		if(isset($_POST['day_mon'])){
			$days[]='Monday';
			$daySet=true;
		}
		if(isset($_POST['day_tue'])){
			$days[]='Tuesday';
			$daySet=true;
		}
		if(isset($_POST['day_wed'])){
			$days[]='Wednesday';
			$daySet=true;
		}
		if(isset($_POST['day_thu'])){
			$days[]='Thursday';
			$daySet=true;
		}
		if(isset($_POST['day_fri'])){
			$days[]='Friday';
			$daySet=true;
		}
		if(isset($_POST['day_sat'])){
			$days[]='Saturday';
			$daySet=true;
		}
		if(isset($_POST['day_sun'])){
			$days[]='Sunday';
			$daySet=true;
		}
	
		$dateString_start=array();
		$dateString_end=array();
		if(($daySet==true)&&($time_set==true)){//if all options are set we can do a request	
			//connect to db
			$mysqli = new mysqli("127.0.0.1","root","","design_db");
			if($mysqli->connect_errno){//connect to db
				trigger_error($msqyli->connect_error);
			}
			for($i=0;$i<count($days);$i++){
				$dateString_start[] = date('Y-m-d H:i:s',strtotime("+".$start_time_m." minutes, +".$start_time_h." hours",strtotime($days[$i])));//"next <insert day>" based on the first day of y2k as an arbitrary reference
				$dateString_end[] = date('Y-m-d H:i:s',strtotime("+".$end_time_m." minutes, +".$end_time_h." hours",strtotime($days[$i])));//"next <insert day>" based on the first day of y2k as an arbitrary reference
				$SQL_Message = "INSERT INTO `schedules` (`Day`,`Device_ID`,`Time_End`,`Time_Start`) VALUES ('".$days[$i]."','2','".$dateString_end[$i]."','".	$dateString_start[$i]."')";
				
				//send to db
				if( !(($mysqli->query($SQL_Message))) ){//query db
					trigger_error($mysqli->error." ".$SQL_Message);//if unsuccessful, print error
				}
			}
			$mysqli->close();//close connection to db
			setflag("schedule_modified","yes");//set the schedule_modified flag
		}
	}
	
	?>
	<FORM method="post" action="schedules.php">
		<TABLE class="GUIblock">
		<TH colspan="2">
			<H2>Create a New Schedule</H2>
		</TH>
			<TR>
				<TD>
					<H3>Select days</H3>
				</TD>
				<TD>
					<H3 align="center">Select Time</H3>
				</TD>
			</TR>
			<TR>
				<TD>
					<INPUT type="checkbox" Name="day_mon">Monday<BR>
					<INPUT type="checkbox" Name="day_tue">Tuesday<BR>
					<INPUT type="checkbox" Name="day_wed">Wednesday<BR>
					<INPUT type="checkbox" Name="day_thu">Thursday<BR>
					<INPUT type="checkbox" Name="day_fri">Friday<BR>
					<INPUT type="checkbox" Name="day_sat">Saturday<BR>
					<INPUT type="checkbox" Name="day_sun">Sunday<BR>
				</TD>
				<TD>
					<TABLE>
						<TR>
							<TD align="center">
								Start Time
							</TD>
						</TR>
						<TR>
							<TD>
								<Input type="number" min="0" max="23" class="numberInput" name="start_time_h" maxlength="2" size="1" placeholder="hh" required>:
								<Input type="number" min="0" max="59" class="numberInput" name="start_time_m" maxlength="2" size="1" placeholder="mm" required>
							</TD>
						</TR>
						<TR>
							<TD>
								<BR>
							</TD>
						</TR>
						<TR>
							<TD align="center">
								End Time
							</TD>
						</TR>
						<TR>
							<TD>
								<Input type="number" min="0" max="23" class="numberInput" name="end_time_h" maxlength="2" size="1" placeholder="hh" required>:
								<Input type="number" min="0" max="59" class="numberInput" name="end_time_m" maxlength="2" size="1" placeholder="mm" required>
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>
			</TR>
				<TD>
					<INPUT type="submit" name="submit_schedule" value="Submit">
				</TD>
			</TR>
		</TABLE>
	</FORM>
	<?PHP
		$mysqli= new mysqli("127.0.0.1","root","","design_db");
		if($mysqli->connect_errno){//connect to db
			trigger_error($msqyli->connect_error);
		}
		$SQL_Message = "SELECT `Day`, `Time_Start`, `Time_End`, `Entry_ID` FROM `schedules`";
		if( !($result = $mysqli->query($SQL_Message)) ){//query db, if query fails print error
			trigger_error($mysqli->error." ".$SQL_Message);
		}
	?>
	<FORM method="post" action="schedules.php">
		<TABLE>
			<TR>
				<TD>
					<select name="active_schedules[]" size=<?php print '"'.(mysqli_num_rows( $result ) + 2).'"'?> multiple="true">
					<option disabled>   Day   |Start Time|End  Time</option>
					<option disabled>──────────────────</option>
					<?PHP
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							printf('<option value="%d">',$row["Entry_ID"]);//<option value=entryID>
							printf("%9s | %8.8s | %8.8s",$row["Day"],$row["Time_Start"],$row["Time_End"]);//the name displayed to user
						}
						$result->free();
						$mysqli->close();
					?>
				</select>
				</TD>
			</TR>
			<TR>
				<TD>
					<INPUT type="submit" name="delete_entries" value="Delete">
				</TD>
			</TR>
		</TABLE>
	</FORM>
<P>
<A HREF = "login.php?logout=logout">Log Out</A>
<A href="./main.php">Main Page</A>
</BODY>
</HTML>