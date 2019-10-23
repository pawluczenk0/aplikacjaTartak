<?php
session_start();

if($_SESSION['zalogowany'] != true){
	header('Location: index.php');
}
else {
	$_SESSION['zalogowany'] = true;
}

if(isset($_POST['button'])) {
	$imie = $_POST['imie'];
	$nazwisko = $_POST['nazwisko'];
	$adres = $_POST['adres'];
	$pesel = $_POST['pesel'];
	$data = $_POST['data'];
	$telefon = $_POST['telefon'];

	

	$walidacja = true;

	//walidacja danych

	if(strlen($imie)<3 || strlen($imie) > 40){
		$walidacja = false;
		$_SESSION['imie_error'] = "Imię ma zawierać od 3 do 40 znaków!";
	}

	if(strlen($nazwisko) < 5 || strlen($nazwisko) > 40){
		$walidacja = false;
		$_SESSION['nazwisko_error'] = "Nazwisko ma mieć od 5 do 40 znakow!";
	}
	if(strlen($pesel)<11 || strlen($pesel)>11 ){
		$walidacja = false;
		$_SESSION['pesel_error'] = "PESEL musi się składać z 11 liczb!";
	}
	elseif(is_numeric($pesel)==false){
		$walidacja = false;
		$_SESSION['pesel_error'] ="PESEL musi składać się z liczb!";
	}
	 if(is_numeric($telefon)==false){
		$walidacja = false;
		 $_SESSION['telefon_error'] = "Numer telefonu musi się składać tylko z cyfr!";
	 }

	 if($adres == ""){
		$walidacja = false;
		$_SESSION['adres_error'] = "Proszę wpisać adres!";
	 }
	 if($data == ""){
		$walidacja = false;
		$_SESSION['data_error'] = "Proszę wpisać datę!";
	 }


	 //wpisywanie danych do bazy

	 if($walidacja==true){
		 require_once("baza.php");

		 $polaczenie = new mysqli($host, $db_user, $password, $db_name);

		 if($polaczenie ->connect_errno!=0){
			 $_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
		 }
		 else {

		 //zabezpieczenia 
		 $imie = htmlspecialchars($imie);
		 $nazwisko = htmlspecialchars($nazwisko);
		 $adres =htmlspecialchars($adres);
		 $pesel = htmlspecialchars($pesel);
		 $data = htmlspecialchars($data);
		 $telefon =htmlspecialchars($telefon);
		

		 $imie = $polaczenie->real_escape_string($imie);
		$nazwisko = $polaczenie->real_escape_string($nazwisko);
		$adres =$polaczenie->real_escape_string($adres);
		 $pesel = $polaczenie->real_escape_string($pesel);
		 $data = $polaczenie->real_escape_string($data);
		 $telefon =$polaczenie->real_escape_string($telefon);
		

		 }

		 $zapytanie="INSERT INTO pracownicy VALUES(NULL, '$imie', '$nazwisko', '$adres','$pesel','$data','$telefon'); ";
		 $polaczenie->set_charset("utf-8");

		 if($polaczenie->query($zapytanie)){
			 $_SESSION['baza_error'] = "Udało się dodać pracownika!";
		 }
		 else {
			 $_SESSION['baza_error'] = "Nie udało się dodać pracownika!";
		 }
		$polaczenie->close();
	 }

	

}

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
		<?php 
		if(isset($_SESSION['login'])){
		echo "Jesteś zalogowany jako: "."<br>".$_SESSION['login'];
		}
		
		?>
		</p>
		</div>
	<div id="menu">
		<ul>
			<li><a href="projekt.php">Stan drzewa</a></li>
			<li><a href="pracownicy.php">Przejrzyj pracowników</a></li>
			<li><a class="active" href="pracdodaj.php">Dodaj pracowników</a></li>
			<li><a href="kup.php">Kup drzewo</a></li>
			<li><a href="sprzedaj.php">Sprzedaj drzewo</a></li>
			<li><a class="wyloguj" href="wyloguj.php">Wyloguj się</a></li>			
		</ul>
	</div>

<div id="stopka"><b>Autor: Paweł Czerwiński </b><br>
	KL IVB1
</div>
</div>
<div id="form"><h4>Dodaj pracownika</h4>
	<form method="post">
		Imię pracownika<br><input type="text" name="imie" class="pole">
		<?php 
        if(isset($_SESSION['imie_error'])){
            echo $_SESSION['imie_error'];
            unset($_SESSION['imie_error']);
        }
	?>
	<br>

		Nazwisko pracownika<br><input type="text" name="nazwisko" class="pole">
		<?php 
        if(isset($_SESSION['nazwisko_error'])){
            echo $_SESSION['nazwisko_error'];
            unset($_SESSION['nazwisko_error']);
        }
    	?>
		<br>

		Adres zamieszkania <br><input type="text" name="adres" class="pole">
		<?php 
        if(isset($_SESSION['adres_error'])){
            echo $_SESSION['adres_error'];
            unset($_SESSION['adres_error']);
        }
		?>
		<br>

		PESEL pracownika<br><input type="text" name="pesel" class="pole" maxlength="11" minlength ="11">
		<?php 
        if(isset($_SESSION['pesel_error'])){
            echo $_SESSION['pesel_error'];
            unset($_SESSION['pesel_error']);
        }
	?>
		<br>

		Data urodzenia pracownika<br><input type="date" name="data" class="pole">
		<?php 
        if(isset($_SESSION['data_error'])){
            echo $_SESSION['data_error'];
            unset($_SESSION['data_error']);
        }
		?>
		<br>


		Numer telefonu komórkowego<br><input type="text" name="telefon" class="pole" maxlength="9" minlength ="9">
		<?php 
        if(isset($_SESSION['telefon_error'])){
            echo $_SESSION['telefon_error'];
            unset($_SESSION['telefon_error']);
        }
		?>
		<br>
		<input type="submit" value="Dodaj" name="button" class="przycisk">
		<br>
		<?php
        if(isset($_SESSION['baza_error'])){
            echo $_SESSION['baza_error'];
            unset($_SESSION['baza_error']);
        }
    ?>
	</form>
</div>




</body>
</html>