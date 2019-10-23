<?php
session_start();
$_SESSION['zalogowany'] = false;
	if(isset($_POST['button'])) {
		$imie = $_POST['imie'];
		$nazwisko = $_POST['nazwisko'];
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		$haslo1 = $_POST['haslo1'];
		$email = $_POST['email'];

		

		$walidacja = true;

		//walidacja imienia

		if(strlen($imie)<3 || strlen($imie) > 40){
			$walidacja = false;
			$_SESSION['imie_error'] = "Imię ma zawierać od 3 do 40 znaków!";
		}

		//walidacja nazwiska
		if(strlen($nazwisko) < 5 || strlen($nazwisko) > 40){
			$walidacja = false;
			$_SESSION['nazwisko_error'] = "Nazwisko ma mieć od 5 do 40 znakow!";
		}
		//walidacja loginu
        if(strlen($login) < 5 || strlen($login) > 40){
            $walidacja = false;
            $_SESSION['login_error'] = "Login ma mieć od 5 do 40 znakow!";
		}
		elseif(ctype_alnum($login)==false){
            $walidacja = false;
            $_SESSION['login_error'] = "Dozwolone tylko litery i liczby bez polskich znakow!";
		}
		//walidacja hasla
		if(strlen($haslo)<8) {
			$walidacja =false;
			$_SESSION['haslo_error'] = "Hasło musi mieć przynajmniej 8 znaków!";
		}	 

		//czy takie same hasła 
		if($haslo1 !== $haslo){
			$_SESSION['haslo1_error'] = "Hasła się różnią, podaj poprawne hasło!";
		}


		//łączenie z bazą


		if($walidacja ==true){

			require_once("baza.php");
			$polaczenie = new mysqli($host, $db_user, $password, $db_name);

			if($polaczenie -> connect_errno!=0){
				$_SESSION['baza_error']= "Błąd połączenia z bazą danych";
			}
			//zabezpieczenia
			else {
				//html
				$imie = htmlspecialchars($imie);
				$nazwisko = htmlspecialchars($nazwisko);
				$login = htmlspecialchars($login);
				$haslo = htmlspecialchars($haslo);
				$email = htmlspecialchars($email);

				//sql
				$imie = $polaczenie->real_escape_string($imie);
				$nazwisko = $polaczenie->real_escape_string($nazwisko);
				$login =  $polaczenie->real_escape_string($login);
				$haslo  = $polaczenie->real_escape_string($haslo);
				$email =  $polaczenie->real_escape_string($email);

				//$haslo = password_hash($haslo, PASSWORD_DEFAULT);
				$haslo = base64_encode($haslo);




			$zapytanie = "INSERT INTO uzytkownicy VAlUES(NULL, '$imie', '$nazwisko', '$login',
			'$haslo','$email');";
			$polaczenie ->set_charset("utf-8");
			

			$zapytanie2 = "SELECT * FROM users WHERE login='$login';";
			$wynik = $polaczenie -> query($zapytanie2);

			 if($wynik ->num_rows>0){
				  $_SESSION['login'] = $login;
				
				$_SESSION['login_error'] ="Podany login jest już zajęty!";
			 }
			 

			

			if($polaczenie->query($zapytanie)){
				$_SESSION['baza_error'] = "Udało się poprawnie zarejestrować!";
				header('Location: index.php');
			}
			else {
				$_SESSION['baza_error'] = "Nie udało się poprawnie zarejestrować";
			}

			
			$polaczenie ->close();
		}}
		
	}

?>
<!DOCTYPE html>

<html>
<head>
	<title>System zarządzania tartakiem</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
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
<div id="form"><h4>Rejestracja</h4>
<form method="post">
	Podaj imię<br>
	<input type="text" name="imie" class="pole">
	<?php 
        if(isset($_SESSION['imie_error'])){
            echo $_SESSION['imie_error'];
            unset($_SESSION['imie_error']);
        }
    ?>
	<br>
	Podaj nazwisko<br>
	<input type="text" name="nazwisko" class="pole">
	<?php 
        if(isset($_SESSION['nazwisko_error'])){
            echo $_SESSION['nazwisko_error'];
            unset($_SESSION['nazwisko_error']);
        }
    ?>

	<br>
	Podaj login<br>
	<input type="text" name="login" class="pole" value="<?php 
		//zajęty login
        if(isset($_SESSION['login'])){
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>"
	>
	<?php 
        if(isset($_SESSION['login_error'])){
            echo $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }
    ?>
	
	<br>
	Podaj hasło<br>
	<input type="password" name="haslo" class="pole">
	<?php 
        if(isset($_SESSION['haslo_error'])){
            echo $_SESSION['haslo_error'];
            unset($_SESSION['haslo_error']);
        }
    ?>
	
	<br>
	Powtórz hasło<br>
	<input type="password" name="haslo1" class="pole">
	<?php
        if(isset($_SESSION['haslo1_error'])){
            echo $_SESSION['haslo1_error'];
            unset($_SESSION['haslo1_error']);
        }
    ?>
	
	<br>
	Podaj adres e-mail<br>
	<input type="email" name="email" class="pole">
	<!-- <div class="g-recaptcha" data-sitekey="6LfJ8XwUAAAAABf0DeVG1GHr_XCn6UoIYTFvqwO8"></div> -->

	<br> 
	<input type="submit" value="Zarejestruj" name="button" class="przycisk"><br>
	<?php
        if(isset($_SESSION['baza_error'])){
            echo $_SESSION['baza_error'];
            unset($_SESSION['baza_error']);
		}
		// if (isset($_POST['button'])) {
		// 	$secret = '	6LfJ8XwUAAAAAHpPAKgTYdBW7IEBCU_fxX2LEqZy';
		// 	$response = $_POST['g-recaptcha-response'];
		// 	$remoteip = $_SERVER['REMOTE_ADDR'];
			
		// 	$url = file_get_contents("https://www.google.com/reca...");
		// 	$result = json_decode($url, TRUE);
		// 	if ($result['success'] == 1) {
		// 	echo 'Nie jesteś botem';
		// 	}
		// 	else{
		// 	echo 'Błędnie wypełnione pole reCAPTCHA';
		// 	}
		// 	}
    ?>
</form>
	<div id="zarejestruj">
	Posiadasz już konto?<br><a href="index.php"><b>Zaloguj się!</b></a>
	</div>
</div>
</body>
</html>