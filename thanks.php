<?php
include __DIR__ . "./includes/header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaling succesvol</title>
</head>
<body>
    <h1 class="text-center">Bedankt voor uw bestelling!</h1>
    <p class="text-center mt-2">uw bestelling is succesvol  afgerond.</p>
</body>
</html>

<?php
header("refresh:5;url=index.php");
?>