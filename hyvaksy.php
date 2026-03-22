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

if (isset($_POST['hyvaksy'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE Drinkki SET Hyvaksytty=1 WHERE DrinkkiID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: hyvaksy.php");
    exit();
}

if (isset($_POST['hylkaa'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM Drinkki_Aines WHERE DrinkkiID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM Drinkki WHERE DrinkkiID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: hyvaksy.php");
    exit();
}

$tulos = $conn->query("SELECT DrinkkiID, Nimi FROM Drinkki WHERE Hyvaksytty=0");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hyväksy ehdotukset</title>
<link rel="stylesheet" href="munTyyli.css">
</head>

<body>

<?php include("navi.php"); ?>

<div class="container">

<h2>Drinkkiehdotukset</h2>

<?php
while($row = $tulos->fetch_assoc()){
?>

<form method="post">

<?php echo $row['Nimi']; ?>

<input type="hidden" name="id" value="<?php echo $row['DrinkkiID']; ?>">

<button type="submit" name="hyvaksy">Hyväksy</button>

<button type="submit" name="hylkaa">Hylkää</button>

</form>

<?php
}
?>

</div>
</body>
</html>