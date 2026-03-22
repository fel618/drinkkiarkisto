<?php
session_start();
include("yhteys.php");

if (!isset($_SESSION['kayttaja_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rooli'] != "admin") {
    die("Ei käyttöoikeutta");
}

// Käyttäjän poistaminen
if (isset($_POST['poista'])) {

    $id = $_POST['id'];

    $conn->query("DELETE FROM kayttaja WHERE KayttajaID=$id");

    echo "<p style='color:green;'>Käyttäjä poistettu.</p>";
}

// Hankitaan käyttäjäluettelo
$tulos = $conn->query("SELECT KayttajaID, Kayttajatunnus FROM kayttaja");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
<meta charset="UTF-8">
<title>Poista käyttäjiä</title>
<link rel="stylesheet" href="munTyyli.css">
</head>

<body>

<?php include("navi.php"); ?>

<div class="container">

<h2>Poista käyttäjiä</h2>

<?php
while($row = $tulos->fetch_assoc()){
?>

<form method="post">

<?php echo $row['Kayttajatunnus']; ?>

<input type="hidden" name="id" value="<?php echo $row['KayttajaID']; ?>">

<button type="submit" name="poista">Poista</button>

</form>

<?php
}
?>

</div>
</body>
</html>