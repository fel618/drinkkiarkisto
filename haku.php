<?php
include("yhteys.php");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
<meta charset="UTF-8">
<title>Drinkkihaku</title>

<link rel="stylesheet" href="munTyyli.css">

</head>

<body>

<?php include("naviGuest.php"); ?>

<h2>Hae drinkkejä</h2>

<form method="post">

Haku:
<input type="text" name="haku">

<br><br>

<input type="radio" name="tyyppi" value="nimi" checked> Nimi
<input type="radio" name="tyyppi" value="aines"> Ainesosa

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

    } else if ($tyyppi == "nimi") {

        $sql = "SELECT * FROM Drinkki
                WHERE Nimi LIKE '%$haku%' AND Hyvaksytty=1";

    } else {

        $sql = "SELECT DISTINCT d.* FROM Drinkki d
                JOIN Drinkki_Aines da ON d.DrinkkiID = da.DrinkkiID
                JOIN Aines a ON da.AinesID = a.AinesID
                WHERE a.Nimi LIKE '%$haku%' AND d.Hyvaksytty=1";
    }

    $tulos = $conn->query($sql);

    while ($row = $tulos->fetch_assoc()) {

        echo "<div class='drinkki'>";

        echo "<h3>".$row['Nimi']."</h3>";
        echo "<p><b>Juomalaji:</b> ".$row['Juomalaji']."</p>";

        $id = $row['DrinkkiID'];

        $aines = $conn->query("
            SELECT a.Nimi, da.Maara
            FROM Drinkki_Aines da
            JOIN Aines a ON da.AinesID = a.AinesID
            WHERE da.DrinkkiID=$id
        ");

        echo "<p><b>Ainekset:</b></p>";

        while ($r = $aines->fetch_assoc()) {
            echo $r['Nimi']." ".$r['Maara']."<br>";
        }

        echo "<p><b>Ohje:</b> ".$row['Valmistusohje']."</p>";

        echo "</div><hr>";
    }
}
?>

</body>
</html>