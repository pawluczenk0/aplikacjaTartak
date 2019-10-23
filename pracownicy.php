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
			<li><a class="active" href="pracownicy.php">Przejrzyj pracowników</a></li>
			<li><a href="pracdodaj.php">Dodaj pracowników</a></li>			
			<li><a href="kup.php">Kup drzewo</a></li>
			<li><a href="sprzedaj.php">Sprzedaj drzewo</a></li>
			<li><a class="wyloguj" href="wyloguj.php">Wyloguj się</a></li>			
		</ul>
	</div>

<!-- <div id="stopka"><b>Autor: Paweł Czerwiński </b><br>
	KL IVB1
</div> -->
</div>
<div id="form">
 <form method="post">
 	<h4>Wyszukaj pracownika</h4>

	
	<input type="submit" value="Wyświetl wszystkich pracowników" name="button" class="przycisk"><br>
	<?php 
        if(isset($_SESSION['pesel_error'])){
            echo $_SESSION['pesel_error'];
            unset($_SESSION['pesel_error']);
		}
		if(isset($_POST['button'])){
			require_once("funkcje.php");
			pokaz_pracownikow();
			
			 
		}
			 if(isset($_POST['usun'])){
				require_once("funkcje.php");
				usun_pracownikow();
				
				
				
				
			}
		 
	
		
		
		
    	?>

</form> 
</div>


<div id="stopka"><b>Autor: Paweł Czerwiński </b><br>
	KL IVB1
</div>

</body>
</html>