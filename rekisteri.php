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

if (isset($_POST["rekisteroidy"])) {

    $kayttajatunnus = trim($_POST["kayttajatunnus"]);
    $salasana = $_POST["salasana"];
    $sahkoposti = trim($_POST["sahkoposti"]);

    if ($kayttajatunnus == "") {
        $virhe = "Käyttäjätunnus ei saa olla tyhjä.";
    } else {
        // Tarkista onko käyttäjätunnus jo olemassa
        $stmt = $conn->prepare("SELECT KayttajaID FROM kayttaja WHERE Kayttajatunnus = ?");
        $stmt->bind_param("s", $kayttajatunnus);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $virhe = "Käyttäjätunnus on jo käytössä.";
        } else {
            // Salasanan hash
            $salasanaHash = password_hash($salasana, PASSWORD_DEFAULT);
            $rooli = "user";

            // Lisää käyttäjä
            $stmt = $conn->prepare(
                "INSERT INTO kayttaja (Kayttajatunnus, Salasana, Sahkoposti, Rooli)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $kayttajatunnus, $salasanaHash, $sahkoposti, $rooli);
            $stmt->execute();

            $onnistui = "Rekisteröinti onnistui!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Rekisteröidy</title>
</head>
<body>

<h1>Rekisteröidy drinkkiarkiston käyttäjäksi</h1>

<?php
if ($virhe != "") {
    echo "<p style='color:red;'>$virhe</p>";
}
if ($onnistui != "") {
    echo "<p style='color:green;'>$onnistui</p>";
}
?>

<form method="post">
    <input type="text" name="kayttajatunnus" placeholder="Käyttäjätunnus"><br><br>
    <input type="password" name="salasana" placeholder="Salasana"><br><br>
    <input type="email" name="sahkoposti" placeholder="Sähköposti"><br><br>

    <input type="submit" name="rekisteroidy" value="Rekisteröidy">
</form>

<p>
Rekisteröityessäsi hyväksyt henkilötietojesi käsittelyehdot.<br>
<a href="#">Lue drinkkiarkiston tietosuojaseloste</a>
</p>

</body>
</html>

<?php
$conn->close();
?>
