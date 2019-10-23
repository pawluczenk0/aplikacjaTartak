<?php



function pokaz_pracownikow(){
    require("baza.php");
    //$pesel = $_POST['pesel'];
	$polaczenie = new mysqli($host, $db_user, $password, $db_name);
	if($polaczenie ->connect_errno!=0){
		$_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
	}
	
    $zapytanie = "SELECT imie, nazwisko, adres, data_urodzenia, nr_telefonu, pesel FROM pracownicy";
    
	$wynik = $polaczenie->query($zapytanie);
	// if($wynik ->num_rows ==0){
	// 	$_SESSION['pesel_error'] = "Brak pracownika o podanym PESELU!";
    //  }
    //  else {
       
		echo '<table>';
        echo '<tr>';
		echo '<td><b>imie</b></td>';
		echo '<td><b>nazwisko</b></td>';
        echo '<td><b>adres</b></td>';
		echo '<td><b>data urodzenia</b></td>';
        echo '<td><b>nr telefonu</b></td>';
        echo '<td><b>pesel</b></td>';
        echo '</tr>';
		$i = 1;
		while($wiersz = $wynik->fetch_assoc()){
			echo "<tr>";
			foreach($wiersz as $wartosc){
				echo "<td>$wartosc</td>";
            }
           
            echo "</tr>";
            $i++;
            
        }
        echo "</table>";
        echo '<form action="" method="post">
			<br>
			<b>Chcesz usunąć pracownika? Wpisz jego numer PESEL</b><br>
			   <input type="text" name="peselusun" maxlength="11" minlength ="11"><br>
			   <input type="submit" name="usun" value="Usuń pracownika" class="przycisk"><br>
			 </form>';        
}

function usun_pracownikow(){
    
    require_once("baza.php");
    $peselusun = $_POST['peselusun'];
    $polaczenie = new mysqli($host, $db_user, $password, $db_name);
	if($polaczenie ->connect_errno!=0){
		$_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
    }
    $zapytanie2 = "SELECT * FROM pracownicy WHERE pesel='$peselusun'";
    $wynik = $polaczenie -> query($zapytanie2);
    //sprawdzenie czy istnieje pracownik o podanym numerze pesel
    if($wynik->num_rows==0){
        pokaz_pracownikow();
        echo "Pracownik o podanym numerze PESEL <b>nie istnieje!</b>";
    }
    else{
    $zapytanie = "DELETE FROM pracownicy WHERE pesel='$peselusun'";
    $polaczenie->query($zapytanie);
    pokaz_pracownikow();   
    echo "Pracownik o numerze PESEL: <b>$peselusun</b> został pomyślnie usunięty";
    }

}


function pokaz_drzewo(){
    require_once("baza.php");
    $gatunek = $_POST['gatunek'];
    
    
    
	$polaczenie = new mysqli($host, $db_user, $password, $db_name);
	if($polaczenie ->connect_errno!=0){
		$_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
    }
    $zapytanie = "SELECT gatunek, ilosc FROM drzewo WHERE gatunek='$gatunek'";
    $wynik = $polaczenie->query($zapytanie);

    echo '<table>';
        echo '<tr>';
		echo '<td><b>Gatunek</b></td>';
		echo '<td><b>Ilosc kubików</b></td>';      
        echo '</tr>';
		$i = 1;
		while($wiersz = $wynik->fetch_assoc()){
			echo "<tr>";
			foreach($wiersz as $wartosc){
				echo "<td>$wartosc</td>";
			}
        }
        echo "</table>";
       
        $polaczenie ->close();

}





function kup_drzewo(){
    require_once("baza.php");
    
    $gatunek = $_POST['gatunek'];
    $ilosc = $_POST['kupilosc'];
    
    
	$polaczenie = new mysqli($host, $db_user, $password, $db_name);
	if($polaczenie ->connect_errno!=0){
		$_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
    }
    else {
    $zapytanie = "UPDATE drzewo SET ilosc=(ilosc+'$ilosc') WHERE gatunek = '$gatunek'";

    $zapytanie2 = "SELECT SUM(ilosc) as ilosc FROM drzewo";
    $wynik2 = $polaczenie->query($zapytanie2);
    $wiersz2 = $wynik2->fetch_assoc(); 

    // $wynik = $polaczenie->query($zapytanie);
    if(empty($ilosc)){
        echo "<br>";
        echo "<b>Musisz wpisać ilość!</b>";
    }
    else{
        if(($wiersz2['ilosc'] + $ilosc) >500){
            echo "<br>";
            echo "<b>Nie posiadasz wystarczającej ilości miejsca w magazynie!</b><br>";
            echo "Aktualnie posiadasz miejsca na<b> ".(500 - $wiersz2['ilosc'])."</b> kubiki drzewa";
        }
        else{
            $wynik = $polaczenie->query($zapytanie);
            echo "<br>";
            echo "Kupiłeś <b>$ilosc</b> kubików drzewa <b>$gatunek</b>";
    }
}
    
    $polaczenie ->close();
}
 }

function sprzedaj_drzewo(){
    require_once("baza.php");
    
    $gatunek = $_POST['gatunek'];
    $ilosc = $_POST['sprzedajilosc'];
    
    
	$polaczenie = new mysqli($host, $db_user, $password, $db_name);
	if($polaczenie ->connect_errno!=0){
		$_SESSION['baza_error'] = "Błąd podczas łączenia z bazą danych!";
    }
    $zapytanie2 = "SELECT * FROM drzewo WHERE gatunek = '$gatunek'";
    $wynik = $polaczenie -> query($zapytanie2);
    $wiersz = $wynik->fetch_assoc();
  

    if(empty($ilosc)){
        echo "<br>";
        echo "<b>Musisz wpisać ilość!</b>";
      
    }
    else{
        if($wiersz['ilosc']<$ilosc){
            echo "<br>";
            echo "Nie posiadasz wystarczającej ilości drzewa!";
            echo "<br>";
            echo "Aktualnie posiadasz <b>".$wiersz['ilosc']."</b> kubików drzewa <b>".$gatunek."</b>";
            
        }
        else {    
            $zapytanie = "UPDATE drzewo SET ilosc=(ilosc-'$ilosc') WHERE gatunek = '$gatunek'";
            $polaczenie->query($zapytanie);
            
    echo "<br>";
    echo "Sprzedałeś <b>$ilosc</b> kubików drzewa <b>$gatunek</b>";
        }
    }
    $polaczenie ->close();
}
?>