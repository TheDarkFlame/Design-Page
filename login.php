<HTML>
	<HEAD>
		<TITLE>Power Monitor: Login</TITLE>
		<LINK REL = Stylesheet TYPE="text/css" HREF="styles.css">
		<?PHP
			$uname = "";
			$pword = "";
			$errorMessage = "";
			$num_rows = 0;

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				//get user info in
				$uname = $_POST['username'];
				$pword = $_POST['password'];
				//remove characters for breaking in
				$uname = htmlspecialchars($uname);
				$pword = htmlspecialchars($pword);
				//connect to the database
				$mysqli = new mysqli("127.0.0.1","root","","design_db");
				if($mysqli->connect_errno){
					trigger_error($mysqli->connect_error);
				}
				else
				{
					$SQL_query = "SELECT * FROM `login` WHERE `Username` = '".$uname."' AND `Password` = '".md5($pword)."'";
					if($result = $mysqli->query($SQL_query)){
						
						if( $result->num_rows > 0){//if the number of rows isn't 0
						//we now start a session on the website
							session_start();
							$_SESSION['login'] = '1';
							header("Location: main.php");//redirect user after they have logged in
						}
						else{
							$errorMessage = "Invalid Login Details";
							session_start();
							$_SESSION['login'] = '';//set blank entry for if user hasn't logged in correctly, (and redirect them on other pages)
						}
					}
					else{//error with sql query
						trigger_error($mysqli->error ." ". $SQL_query);
					}
				}
			}
		?>
	</HEAD>
	<BODY>
	<H1 class="centeredWidth">LOGIN</H1>
		<FORM class="centeredHeightAndWidth" METHOD="POST" ACTION="login.php">
			<INPUT TYPE="text" placeholder="username" name="username"><BR>
			<INPUT TYPE="password" placeholder="password" name="password"><BR>
			<INPUT TYPE="submit" Name="submit_button" value="Login">
		</FORM>
	</BODY>
</HTML>