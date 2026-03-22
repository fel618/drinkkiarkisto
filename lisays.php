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
<html lang="fi">
<head>

<meta charset="UTF-8">
<title>Lisää resepti</title>

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
<h2>Lisää uusi drinkki</h2>

<?php if (!empty($virhe)) { ?>
    <p style="color:red;"><?php echo $virhe; ?></p>
<?php } ?>

<form method="post">

Nimi:<br>
<input type="text" name="nimi"><br><br>

Juomalaji:<br>
<input type="text" name="juomalaji"><br><br>

<h3>Ainekset</h3>

<div id="ainesLista"></div>

<br>

<button type="button" onclick="lisaaAines()">+ Lisää aines</button>

<br><br>

Ohje:<br>
<textarea name="ohje" rows="5" cols="40"></textarea>

<br><br>

<input type="submit" name="laheta" value="Lisää resepti">

</form>

<?php
if (isset($_POST['laheta'])) {

    $nimi = trim($_POST['nimi']);
    $juomalaji = trim($_POST['juomalaji']);
    $ohje = trim($_POST['ohje']);
    $hyvaksytty = 1; // lisäyksessä hyväksytty heti

    if ($nimi == "") {
        $virhe = "Nimi ei saa olla tyhjä";
    } else {

        $tarkistus = $conn->query("SELECT * FROM Drinkki WHERE Nimi='$nimi'");

        if ($tarkistus->num_rows > 0) {
            $virhe = "Drinkki on jo olemassa";
        } else {

            $sql = "INSERT INTO Drinkki (Nimi, Juomalaji, Valmistusohje, Hyvaksytty, Lisaaja)
                    VALUES ('$nimi','$juomalaji','$ohje',$hyvaksytty,".$_SESSION['kayttaja_id'].")";

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

                echo "<p style='color:green;'>Resepti lisätty!</p>";
            }
        }
    }
}
?>

</div>
</body>
</html>