<?php 
session_start();


?>
<!DOCTYPE html>
<html>
<head>
	<title>System zarządzania tartakiem</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div>
<div id="tytul">SYSTEM ZARZĄDZANIA TARTAKIEM<p> 
		Nie jesteś zalogowany
		</p>
		</div>
	

<div id="stopka"><b>Autor: Paweł Czerwiński</b><br>
	KL IVB1
</div>
</div>
<div id="form"><h4>Zaloguj się do systemu</h4>
<form action="logowanie.php" method="post">
	Podaj login<br>
	<input type="text" name="login" class="pole"><br>
	Wpisz hasło<br>
	<input type="password" name="haslo" class="pole"><br>
	<input type="submit" value="Zaloguj" name="loguj" class="przycisk" >	
	</form><br>
    <?php 
        if(isset($_SESSION['log_error'])) {
            echo ($_SESSION['log_error']);
            unset($_SESSION['log_error']);
        }
    ?>
	

	
	<div id="zarejestruj">
	Nie posiadasz konta?<br><a href="rejestracja.php"><b> Zarejestruj się!</b></a>
	</div>
</div>

</body>
</html>