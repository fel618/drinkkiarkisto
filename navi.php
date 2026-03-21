<?php
if (!isset($_SESSION['rooli'])) {
    include("naviGuest.php");
}
else if ($_SESSION['rooli'] == "admin") {
    include("naviAdmin.php");
}
else {
    include("naviUser.php");
}
?>