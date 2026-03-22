<?php
session_start();
require_once("yhteys.php");

if (isset($_POST['login'])) {

    $tunnus = $_POST['tunnus'];
    $salasana = $_POST['salasana'];

    $sql = "SELECT * FROM kayttaja WHERE Kayttajatunnus = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tunnus);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($salasana, $user['Salasana'])) {

        $_SESSION['kayttaja_id'] = $user['KayttajaID'];
        $_SESSION['rooli'] = $user['Rooli'];

        header("Location: haku.php");
        exit();

    } else {
        $virhe = "Väärä tunnus tai salasana";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kirjaudu</title>
    <link rel="stylesheet" href="munTyyli.css">
</head>
<body>
<?php include("navi.php"); ?>
<div class="container">
    <h2>Kirjaudu</h2>

    <?php if (isset($virhe)) echo "<p style='color:red;'>$virhe</p>"; ?>

    <form method="POST">
        <label>Käyttäjätunnus</label>
        <input type="text" name="tunnus" required>

        <label>Salasana</label>
        <input type="password" name="salasana" required>

        <button type="submit" name="login">Kirjaudu</button>
    </form>
</div>

</body>
</html>