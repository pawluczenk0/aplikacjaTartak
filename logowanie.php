<?php 
session_start();


?>
<?php

	if(isset($_POST['loguj'])) {

		if(empty($_POST['login']) || empty($_POST['haslo'])){		
		$_SESSION['log_error'] = "Wpisz poprawnie login i hasło!";
		header('Location: index.php');
		}
	
	

		//logowanie 
		require_once("baza.php");
		$polaczenie = new mysqli($host, $db_user, $password, $db_name);

		$login = $_POST['login'];
		$haslo = $_POST['haslo'];

		//zabezpieczenia

		$login = htmlspecialchars($login);
		$haslo = htmlspecialchars($haslo);

		$login = $polaczenie -> real_escape_string($login);
		$haslo = $polaczenie -> real_escape_string($haslo);

		//sprawdzanie czy istnieje uzytkownik

		$zapytanie = "SELECT * FROM uzytkownicy WHERE login = '$login';";

		
		$wynik = $polaczenie -> query($zapytanie);
		

		if ($wynik ->num_rows == 1) {
			//sprawdzamy haslo uzytkownika
			$wiersz= $wynik->fetch_assoc();
			
			//if($haslo == $wiersz['haslo']){	
			if($haslo == base64_decode($wiersz['haslo'])){
			//if(password_verify($haslo, $wiersz['haslo'])){
				$_SESSION['login'] = $login;
				$_SESSION['zalogowany'] = true;	
				header('Location: projekt.php');
			}
			else {			
				$_SESSION['log_error']= "Podane hasło jest niepoprawne!";
				header('Location: index.php');
			}
			}       
		else {
			$_SESSION['log_error']= "Podany użytkownik nie istnieje!";
			header('Location: index.php');
		}
	}
	

	
		
		


	// 	if(($login==$wiersz['login']) && ($haslo==$wiersz['haslo'])){ 
	// 		$_SESSION['login'] = $login;
	// 		$_SESSION['zalogowany'] = true;	
	// 		header('Location: projekt.php');
	// }
	$polaczenie ->close();
?>
