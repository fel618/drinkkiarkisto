<?php
session_start();

// Poistamme kaikki istunnon tiedot
session_unset();
session_destroy();

// Uudelleenohjaus kirjautumissivulle
header("Location: login.php");
exit();