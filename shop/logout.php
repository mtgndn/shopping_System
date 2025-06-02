<!DOCTYPE html>
<html>
<head>
    <title>Alışveriş Sitesi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
session_destroy();
header("Location: login.php");
?>

</body>
</html>