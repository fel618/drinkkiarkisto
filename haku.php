<?php
session_start();

if (!isset($_SESSION['kayttaja_id'])) {
    header("Location: login.php");
    exit();
}

include("yhteys.php");
?>

<!DOCTYPE html>
<html lang="fi">
<head>

<meta charset="UTF-8">
<title>Drinkkihaku</title>

<link rel="stylesheet" href="munTyyli.css">
<script src="munJava.js"></script>

</head>

<body>

<?php include("navi.php"); ?>

<div class="container">
<h2>Hae drinkkejä</h2>

<form method="post">

Haku:<br>
<input type="text" name="haku">

<br><br>

<div class="search-type">

<button type="button" onclick="setSearch('nimi')" id="btnNimi">Nimi</button>
<button type="button" onclick="setSearch('aines')" id="btnAines">Ainesosa</button>

<input type="hidden" name="tyyppi" id="tyyppi" value="nimi">

</div>

<br><br>

<input type="submit" name="laheta" value="Lähetä">

</form>

<hr>

<?php

if (isset($_POST['laheta'])) {

    $haku = trim($_POST['haku']);
    $tyyppi = $_POST['tyyppi'];

    if ($haku == "") {

        $sql = "SELECT * FROM Drinkki WHERE Hyvaksytty=1";

    } 
    elseif ($tyyppi == "aines") {

        $sql = "SELECT DISTINCT d.*
                FROM Drinkki d
                JOIN Drinkki_Aines da ON d.DrinkkiID = da.DrinkkiID
                JOIN Aines a ON da.AinesID = a.AinesID
                WHERE a.Nimi LIKE '%$haku%'
                AND d.Hyvaksytty = 1";

    } 
    else {

        $sql = "SELECT *
                FROM Drinkki
                WHERE Nimi LIKE '%$haku%'
                AND Hyvaksytty = 1";

    }

    $tulos = $conn->query($sql);

    if ($tulos->num_rows > 0) {

        while ($row = $tulos->fetch_assoc()) {

            echo "<div class='drinkki'>";

            echo "<h3>".$row['Nimi']."</h3>";
            echo "<b>Juomalaji:</b> ".$row['Juomalaji']."<br><br>";

            $id = $row['DrinkkiID'];

            $aines = $conn->query("
            SELECT a.Nimi, da.Maara
            FROM Drinkki_Aines da
            JOIN Aines a ON da.AinesID = a.AinesID
            WHERE da.DrinkkiID=$id
            ");

            echo "<b>Ainekset:</b><br>";

            while ($r = $aines->fetch_assoc()) {
                echo $r['Nimi']." ".$r['Maara']."<br>";
            }

            echo "<br><b>Ohje:</b><br>".$row['Valmistusohje'];

            echo "</div>";
        }

    } else {

        echo "<p>Hakutuloksia ei löytynyt.</p>";

    }

}
?>
</div>
</body>
</html>