<?php
session_start();
include("yhteys.php");

// TEST (убери когда появится login.php)
$_SESSION['rooli'] = "admin";
$_SESSION['id'] = 1;

if (!isset($_SESSION['rooli'])) {
    die("Ei käyttöoikeutta");
}

if ($_SESSION['rooli'] == "admin") {
    $hyvaksytty = 1;
} else {
    $hyvaksytty = 0;
}

$virhe = "";

if (isset($_POST['laheta'])) {

    $nimi = trim($_POST['nimi']);
    $juomalaji = trim($_POST['juomalaji']);
    $ohje = trim($_POST['ohje']);

    if ($nimi == "") {
        $virhe = "Nimi ei saa olla tyhjä";
    } else {

        $tarkistus = $conn->query("SELECT * FROM Drinkki WHERE Nimi='$nimi'");

        if ($tarkistus->num_rows > 0) {
            $virhe = "Drinkki on jo olemassa";
        } else {

            $sql = "INSERT INTO Drinkki (Nimi, Juomalaji, Valmistusohje, Hyvaksytty, Lisaaja)
                    VALUES ('$nimi','$juomalaji','$ohje',$hyvaksytty,".$_SESSION['id'].")";

            if ($conn->query($sql) === TRUE) {

                $last_id = $conn->insert_id;

                // Haetaan kaikki ainekset
                $ainekset = $_POST['aines'];
                $maarat = $_POST['maara'];

                for ($i=0; $i<count($ainekset); $i++) {

                    $aines = $ainekset[$i];
                    $maara = trim($maarat[$i]);

                    if ($maara != "") {

                        $conn->query("INSERT INTO Drinkki_Aines (DrinkkiID, AinesID, Maara)
                                      VALUES ($last_id, $aines, '$maara')");
                    }
                }

                echo "<p style='color:green;'>Resepti lisätty!</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>

<meta charset="UTF-8">
<title>Lisää resepti</title>

<link rel="stylesheet" href="munTyyli.css">
<script src="munJava.js"></script>

</head>

<body>

<?php include("navi.php"); ?>
<div class="container">
<h2>Lisää uusi drinkki</h2>

<?php
if ($virhe != "") {
    echo "<p style='color:red;'>$virhe</p>";
}
?>

<form method="post">

Nimi:<br>
<input type="text" name="nimi"><br><br>

Juomalaji:<br>
<input type="text" name="juomalaji"><br><br>

<h3>Ainekset</h3>

<div id="ainesLista">

<div class="ainesRivi">

<select name="aines[]">

<?php
$ainekset = $conn->query("SELECT * FROM Aines");

while($row = $ainekset->fetch_assoc()){
    echo "<option value='".$row['AinesID']."'>".$row['Nimi']."</option>";
}
?>

</select>

Määrä:
<input type="text" name="maara[]">

</div>

</div>

<br>

<button type="button" onclick="lisaaAines()">Lisää aines</button>

<br><br>

Ohje:<br>
<textarea name="ohje" rows="5" cols="40"></textarea>

<br><br>

<input type="submit" name="laheta" value="Lisää resepti">

</form>
</div>
</body>
</html>