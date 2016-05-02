	<?PHP
	require_once './functions.php';
	initflag("operating_mode","on");//on, off, scheduled
	initflag("device_connected","no");//yes, no
	initflag("schedule_modified","no");//yes, no
	initflag("price",".001475");//any numeric value
	initflag("mode_modified","no");//yes,no
	session_start();
	check_login();//checks if user is logged in first
	header("Location:main.php");//redirect to main page
	?>