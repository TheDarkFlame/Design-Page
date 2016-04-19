	<?PHP
	session_start();
	if(!(isset($_SESSION['login']) && !empty($_SESSION['login']))){
		header("Location:login.php");//redirect to login page
	}
	else{
		header("Location:main.php");//redirect to main page
	}
	?>