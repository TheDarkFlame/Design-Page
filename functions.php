<?PHP 
	date_default_timezone_set("Africa/Johannesburg");
	function check_login(){
		//check first if there are user entries in the database, if not, allow access.
		$mysqli= new mysqli("127.0.0.1","root","","design_db");
		if($mysqli->connect_errno){//connect to db
			trigger_error($msqyli->connect_error);
		}
		$SQL_Message = "SELECT * FROM `login`";
		if($result = $mysqli->query($SQL_Message)){//query db
			if(count($result)!=0){//if we have an entry, check if it is valid, if invalid, redirect
				session_start();
				if(!(isset($_SESSION['login']) && !empty($_SESSION['login']))){
					header("Location:login.php");//redirect to login page
				}	
			}
			$result->free();
		}
		else{//if query fails print error
			trigger_error($mysqli->error." ".$SQL_Message);
		}
		$mysqli->close();
	}
	
	function logout(){
		session_start();
		session_destroy();
		header("Location:login.php");
	}
	
	function time_bounded_powerstats_query(&$time, &$power, $Device_ID, $Unix_Start_Date, $Unix_Stop_Date){//start and stop are in unix format (an integer)
		
		//"Y-m-d H:i:s" format is needed
		$start_date = getdate($Unix_Start_Date);
		$stop_date = getdate($Unix_Stop_Date);
		$start_sql_string = $start_date['year']."-".$start_date['mon']."-".$start_date['mday']." ".$start_date['hours'].":".$start_date['minutes'].":".$start_date['seconds'];
		$stop_sql_string = $stop_date['year']."-".$stop_date['mon']."-".$stop_date['mday']." ".$stop_date['hours'].":".$stop_date['minutes'].":".$stop_date['seconds'];
		
		$mysqli= new mysqli("127.0.0.1","root","","design_db");
		if($mysqli->connect_errno){//connect to db
			trigger_error($msqyli->connect_error);
		}
		$SQL_Message = "SELECT `Time_Recorded`, `Power_Reading` FROM `power_stats` WHERE `Device_ID` = '".$Device_ID."' AND `Time_Recorded` BETWEEN '".$start_sql_string."' AND '".$stop_sql_string."'";
		if($result = $mysqli->query($SQL_Message)){//query db
			while ($row = $result->fetch_assoc()){
				$time[] = strtotime($row['Time_Recorded']);
				$power[] = $row['Power_Reading'];
			}
			$result->free();
		}
		else{//if query fails print error
			trigger_error($mysqli->error." ".$SQL_Message);
		}
		$mysqli->close();
	}
	
	function time_bounded_energy_use($Unix_Start_Date, $Unix_Stop_Date, $Device_ID){
		$energy = 0;
		$power = array();
		$time = array();
		$current_time=time();
		time_bounded_powerstats_query($time, $power, $Device_ID, $Unix_Start_Date, $Unix_Stop_Date);
		$arrayLength = count($power);
		for($i=0; $i < $arrayLength - 1 ; $i++){
			$energy = $energy + (($power[$i]+$power[$i+1]) / 2 * abs($time[$i] - $time[$i+1]));//get the average of the two readings and multiply by the time between readings
		}
		return $energy;
	}
	
	function initflag($flagname,$defaultvalue){//initiates flags if they do not exist
	$mysqli= new mysqli("127.0.0.1","root","","design_db");
	if($mysqli->connect_errno){//connect to db
		trigger_error($msqyli->connect_error);
	}
	$SQL_Message = "SELECT * FROM `flags` WHERE Flag_ID = '".$flagname."'";
	if($result = $mysqli->query($SQL_Message)){//query db
			if (mysqli_num_rows($result)==0){
				$SQL_Message = "INSERT INTO `flags` (`Flag_ID`,`Flag_Value`) VALUES ('".$flagname."','".$defaultvalue."')";
				if($result2 = $mysqli->query($SQL_Message)){
				}
				else{//if query fails print error
					trigger_error($mysqli->error." ".$SQL_Message);
				}
			}
			$result->free();
		}
		else{//if query fails print error
			trigger_error($mysqli->error." ".$SQL_Message);
		}
		$mysqli->close();
}

function setflag($flagname,$flagvalue){//sets flags
	$mysqli= new mysqli("127.0.0.1","root","","design_db");
	if($mysqli->connect_errno){//connect to db
		trigger_error($msqyli->connect_error);
	}
	$SQL_Message = "UPDATE `flags` SET `Flag_Value` = '".$flagvalue."' WHERE `Flag_ID` = '".$flagname."'";
	if($result2 = $mysqli->query($SQL_Message)){}//do nothing
	else{//if query fails print error
		trigger_error($mysqli->error." ".$SQL_Message);
	}
	$mysqli->close();
}

function checkflag($flagname){//checks the flag and returns the result
	$mysqli= new mysqli("127.0.0.1","root","","design_db");
	if($mysqli->connect_errno){//connect to db
		trigger_error($msqyli->connect_error);
	}
	$SQL_Message = "SELECT * FROM `flags` WHERE Flag_ID = '".$flagname."'";
	if($result = $mysqli->query($SQL_Message)){//query db
			$row = $result->fetch_assoc();
			$returnval = $row["Flag_Value"];
			$result->free();
		}
		else{//if query fails print error
			trigger_error($mysqli->error." ".$SQL_Message);
		}
		$mysqli->close();
		return $returnval;
}
?>