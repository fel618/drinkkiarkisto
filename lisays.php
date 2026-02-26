<?php
session_start();
include("yhteys.php"); 
// FOR TEST
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

                for ($i=1; $i<=3; $i++) {

                    $aines = $_POST["aines$i"];
                    $maara = trim($_POST["maara$i"]);

                    if ($maara != "") {
                        $yhteys->query("INSERT INTO Drinkki_Aines (DrinkkiID, AinesID, Maara)
                                        VALUES ($last_id, $aines, '$maara')");
                    }
                }

                echo "Resepti lisätty!";
            }
        }
    }
}
?>

<h2>Lisää resepti</h2>

<form method="post">
Nimi: <input type="text" name="nimi"><br><br>
Juomalaji: <input type="text" name="juomalaji"><br><br>

Raaka-aine:<br>

<?php
$ainekset = $conn->query("SELECT * FROM Aines");
for ($i=1; $i<=3; $i++) {
    echo "<select name='aines$i'>";
    $ainekset->data_seek(0);
    while($row = $ainekset->fetch_assoc()) {
        echo "<option value='".$row['AinesID']."'>".$row['Nimi']."</option>";
    }
    echo "</select>";
    echo " Määrä: <input type='text' name='maara$i'><br><br>";
}
?>

Ohje:<br>
<textarea name="ohje"></textarea><br><br>

<input type="submit" name="laheta" value="Lisää resepti">
</form>

<?php echo $virhe; ?>