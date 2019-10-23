<?php
session_start();
if($_SESSION['zalogowany'] != true){
	header('Location: index.php');
}
else {
	$_SESSION['zalogowany'] = true;
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
<div id='tlo'></div>
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
			<li><a href="pracdodaj.php">Dodaj pracowników</a></li>
			
			<li><a href="kup.php">Kup drzewo</a></li>
			<li><a class="active "href="sprzedaj.php">Sprzedaj drzewo</a></li>
			<li><a class="wyloguj" href="index.php">Wyloguj się</a></li>
			
			
			
			
		</ul>
	</div>

<div id="stopka"><b>Autor: Paweł Czerwiński</b><br>
	KL IVB1
</div>
</div>
<div id="form"><h4>Sprzedaj drzewo</h4>
	<form method="post">
		Wybierz gatunek drzewa<br>
		<select name="gatunek" class="pole">
		<option>Jesion</option>
		<option>Buk</option>
		<option>Dąb</option>
		</select><br>
		Wpisz ilość kubików jaką chcesz sprzedać<br>
		<input type="number" name="sprzedajilosc" class="pole"><br>
		<input type="submit" value="Sprzedaj" name="button" class="przycisk">
		<?php
		if(isset($_POST['button'])){
			require_once("funkcje.php");
			sprzedaj_drzewo();
	}
	?>
	</form>
</div>



</body>
</html>