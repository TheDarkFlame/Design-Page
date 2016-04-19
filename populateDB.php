<?PHP
	//this script should generate random data for our database
	//http://stackoverflow.com/questions/13871409/php-wont-post-form-to-database
	date_default_timezone_set("Africa/Johannesburg");
	$mysqli = new mysqli("127.0.0.1", "root","","design_db");
	if($mysqli->connect_errno){
		trigger_error($mysqli->connect_error);
	}
	$current_date = getdate();
	$timestamp_segment = $current_date['year'] . "-" . $current_date['mon'] . "-" . $current_date['mday'] . " " . $current_date['hours'];
	$minutes = 0;
	$seconds = 0;
	for($i=1;$i<=100;$i++){//100 entries for each device
	$seconds+=40;
	if($seconds>59){
		$seconds-=60;
		$minutes++;
	}
		for($j=1;$j<=3;$j++){//3 devices
			$reading = rand(500,700);
			$rec_time = $timestamp_segment . ":" . $minutes . ":" . $seconds;
			$SQL_Message = "INSERT INTO `power_stats` (`Device_ID`,`Time_Recorded`,`Power_Reading`) VALUES ('".$j."','".$rec_time."','".$reading."')";
			if($mysqli->query($SQL_Message)){
			print "Successfully added : " . $j . ", " . $rec_time . ", " . $reading . "<BR>";
			}
			else
			{
			trigger_error($mysqli->error." ".$SQL_Message);
			}
		}
	}
	$mysqli->close();
?>