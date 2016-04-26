<HTML>
	<HEAD>
		<TITLE>Register a New user</Title>
	</HEAD>
	<BODY>
		<?PHP 
			require_once('functions.php');
			check_login();//check user is logged in
			
				$errorMessage="";
				$get_uname = "";
				$get_pword1 = "";
				$get_pword2 = "";
			
				//check button is pressed
			if(isset($_POST['submit_button'])){
				//get input
				$get_uname = $_POST['username'];
				$get_pword1 = $_POST['password1'];
				$get_pword2 = $_POST['password2'];
			
				//check input for injection attacks
				$uname = htmlspecialchars($get_uname);
				$pword1 = htmlspecialchars($get_pword1);
				$pword2 = htmlspecialchars($get_pword2);
			
				//check password verfied correctly
				if(!($pword1 === $pword2)){
					$errorMessage = $errorMessage . "passwords do not match";
				}
				else{
					//check length of usernames and passwords
					$uLength = strlen($uname);
					$pLength = strlen($pword1);

					if($uLength >=5 && $uLength<20){//username is between 6 and 20
						$errorMessage="";
					}
					else{
						$errorMessage = $errorMessage . "Username should be between 6 and 20 characters;<P>";
					}
					if($pLength >=5 && $pLength<16){//password is between 8 and 16
						$errorMessage="";
					}
					else{
						$errorMessage = $errorMessage . "Password should be between 8 and 16 characters;<P>";
					}

					//if no errors with length, we add it to the database
					if($errorMessage == ""){
						$db_handle = mysqli_connect("127.0.0.1","root","","design_db");
						$SQL_Message = "SELECT * FROM `login` WHERE `username` = '".$uname."'";
						//here we check if the desired username already exists
						//below snippet is from here: http://php.net/manual/en/mysqli-stmt.num-rows.php
						if($stmt = mysqli_prepare($db_handle, $SQL_Message)){
							mysqli_stmt_execute($stmt);
							mysqli_stmt_store_result($stmt);
							$rowCount = mysqli_stmt_num_rows($stmt);
							mysqli_stmt_close($stmt);
							if( $rowCount > 0){//if there is already a row with the defined username
								$errorMessage = "Username already exists";
							}
							else{//there is no entry already, we want to add this to the db
								$SQL_Message = "INSERT INTO `login` (`username`, `password`) VALUES ('".$uname."','".md5($pword1)."')";//store the md5 hash of the password
								$result = mysqli_query($db_handle, $SQL_Message);
								$errorMessage = $uname . " registered";
								$uname="";
								$pword1="";
								$pword2="";
							}
						}
						mysqli_close($db_handle);
					}
				}
			}
		?>
		
		<H1 class="centeredWidth">Register New User</H1>
		<FORM class="centeredHeightAndWidth" METHOD="POST" ACTION="registeruser.php">
			<INPUT TYPE="text" placeholder="username" name="username" value=<?PHP print $get_uname?>><BR>
			<INPUT TYPE="password" placeholder="enter password" name="password1" value=<?PHP print $get_pword1 ?>><BR>
			<INPUT TYPE="password" placeholder="re-enter password" name="password2" value=<?PHP print $get_pword2 ?>><BR>
			<INPUT TYPE="submit" Name="submit_button" value="Register">
		</FORM>

		<?PHP print $errorMessage . "<P>";?>
		<P>
		<A HREF = "login.php?logout=logout">Log Out</A>
		<A href="./main.php">Main Page</A>
	</BODY>
</HTML>