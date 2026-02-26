<?php
include("yhteys.php");

// Tietokantayhteys
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drinkityan"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$virhe = "";
$onnistui = "";

// Lomakkeen käsittely
if (isset($_POST["lisaa"])) {

    if (empty($_POST["nimi"])) {
        $virhe = "Aines ei saa olla tyhjä.";
    } else {
        $nimi = trim($_POST["nimi"]);

        // Tarkistetaan löytyykö aines jo
        $stmt = $conn->prepare("SELECT AinesID FROM aines WHERE Nimi = ?");
        $stmt->bind_param("s", $nimi);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $virhe = "Aines on jo olemassa.";
        } else {
            // Lisätään uusi aines
            $stmt = $conn->prepare("INSERT INTO aines (Nimi) VALUES (?)");
            $stmt->bind_param("s", $nimi);
            $stmt->execute();
            $onnistui = "Aines lisätty onnistuneesti.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Aineksen lisäys</title>
</head>
<body>

<h2>Lisää uusi aines</h2>

<?php
if ($virhe != "") {
    echo "<p style='color:red;'>$virhe</p>";
}
if ($onnistui != "") {
    echo "<p style='color:green;'>$onnistui</p>";
}
?>

<form method="post">
    <input type="text" name="nimi" placeholder="Aineksen nimi">
    <br><br>
    <input type="submit" name="lisaa" value="Lisää">
</form>

<hr>

<h3>Järjestelmässä olevat ainekset</h3>

<ul>
<?php
$tulos = $conn->query("SELECT Nimi FROM aines ORDER BY Nimi");
while ($rivi = $tulos->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($rivi["Nimi"]) . "</li>";
}
?>
</ul>

</body>
</html>

<?php
$conn->close();
?>
