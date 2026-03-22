<?php
session_start();
include("yhteys.php");

if (!isset($_SESSION['kayttaja_id'])) {
    header("Location: login.php");
    exit();
}

$virhe = "";

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ehdota reseptiä</title>

<link rel="stylesheet" href="munTyyli.css">

<script>
// JS
function lisaaAines() {

    let lista = document.getElementById("ainesLista");

    let div = document.createElement("div");
    div.classList.add("aines-rivi");

    div.innerHTML = `
        <select name="aines[]">
            <?php
            $ainekset = $conn->query("SELECT * FROM Aines");
            while($row = $ainekset->fetch_assoc()){
                echo "<option value='".$row['AinesID']."'>".$row['Nimi']."</option>";
            }
            ?>
        </select>

        <input type="text" name="maara[]" placeholder="Määrä">

        <button type="button" onclick="this.parentElement.remove()">X</button>
    `;

    lista.appendChild(div);
}

// lisätään heti yksi ainesosa
window.onload = function() {
    lisaaAines();
}
</script>

</head>

<body>

<?php include("navi.php"); ?>

<div class="container">

<h2>Ehdota reseptiä</h2>

<form method="post">

Nimi
<input type="text" name="nimi" required>

Juomalaji
<input type="text" name="juomalaji" required>

<h3>Ainekset</h3>

<div id="ainesLista"></div>

<br><br>

<button type="button" onclick="lisaaAines()">+ Lisää aines</button>

<br><br>

Ohje
<textarea name="ohje" required></textarea>

<input type="submit" name="laheta" value="Lähetä ehdotus">

</form>

<?php
if (isset($_POST['laheta'])) {

    $nimi = trim($_POST['nimi']);
    $juomalaji = trim($_POST['juomalaji']);
    $ohje = trim($_POST['ohje']);

    // ehdotus → ei hyväksytty
    $hyvaksytty = 0;

    $sql = "INSERT INTO Drinkki (Nimi, Juomalaji, Valmistusohje, Hyvaksytty, Lisaaja)
            VALUES ('$nimi','$juomalaji','$ohje',$hyvaksytty,".$_SESSION['id'].")";

    if ($conn->query($sql) === TRUE) {

        $drinkkiID = $conn->insert_id;

        if (isset($_POST['aines'])) {

            foreach ($_POST['aines'] as $i => $ainesID) {

                $maara = $_POST['maara'][$i];

                if ($maara != "") {

                    $conn->query("INSERT INTO Drinkki_Aines (DrinkkiID, AinesID, Maara)
                                  VALUES ($drinkkiID, $ainesID, '$maara')");
                }
            }
        }

        echo "<p>✔ Ehdotus lähetetty!</p>";
    }
}
?>

</div>

</body>
</html>