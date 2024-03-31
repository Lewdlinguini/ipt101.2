<?php
session_start();


if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {

    header("Location: Loginform.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcoming</title>
    <link rel = "stylesheet" href ="Stylesheet2.css">
</head>
<body>
    <h1><b>Welcome</b></h1>
    <form action = "logout.php" method = "post">
    <button type = "submit">Logout</button>
    </form>
</body>
</html>