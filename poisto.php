<?php
session_start();
include("yhteys.php");

if ($_SESSION['rooli'] != "admin") {
    die("Ei käyttöoikeutta");
}

if (isset($_POST['poista'])) {

    $id = $_POST['id'];

    $conn->query("DELETE FROM Drinkki_Aines WHERE DrinkkiID=$id");
    $conn->query("DELETE FROM Drinkki WHERE DrinkkiID=$id");

    echo "Resepti poistettu.";
}

$tulos = $conn->query("SELECT DrinkkiID, Nimi FROM Drinkki");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Poista reseptejä</title>
<link rel="stylesheet" href="munTyyli.css">
</head>

<body>

<?php include("naviAdmin.php"); ?>

<div class="container">

<h2>Poista reseptejä</h2>

<?php
while($row = $tulos->fetch_assoc()){
?>

<form method="post">

<?php echo $row['Nimi']; ?>

<input type="hidden" name="id" value="<?php echo $row['DrinkkiID']; ?>">

<button type="submit" name="poista">Poista</button>

</form>

<?php
}
?>

</div>
</body>
</html>