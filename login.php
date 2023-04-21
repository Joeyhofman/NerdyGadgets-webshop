<?php
include __DIR__ . "./includes/header.php";

if (isset($_SESSION["Error"]["EmailInUse"])) {
    print($_SESSION["Error"]["EmailInUse"]);

    unset($_SESSION["Error"]);
}
if (isset($_SESSION["Error"]["InvalidCredientials"])) {
    print($_SESSION["Error"]["InvalidCredientials"]);

    unset($_SESSION["Error"]);
}
if (isset($_SESSION["Error"]["LoggedOut"])) {
    print($_SESSION["Error"]["LoggedOut"]);

    unset($_SESSION["Error"]);
}

?>
<body>
<div>
    <div>
        <h1>Inloggen</h1>
        <form action="account.php" method="post">
            <label for="customerEmail">Email:</label>
            <input type="text" id="customerEmail" name="customerEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required><br><br>
            <label for="customerPassword">Wachtwoord:</label>
            <input type="password" id="customerPassword" name="customerPassword" required><br><br>
            <button type="submit" name="submitLogin" class="btn btn-success">Inloggen</button>
        </form>
    </div>
    <br>
    <div>
        <h1>Registreren</h1>
        <form method="post" action="account.php">
            <label for="customerEmail">Email:</label>
            <input type="text" id="customerEmail" name="customerEmail" required><br><br>
            <label for="customerName">Naam:</label>
            <input type="text" id="customerName" name="customerName" pattern="^(\w\w+)\s(\w+)$" required><br><br>
            <label for="customerAddress">Adres:</label>
            <input type="text" id="customerAddress" name="customerAddress" required><br><br>
            <label for="customerCity">Woonplaats:</label>
            <input type="text" id="customerCity" name="customerCity" required><br><br>
            <label for="customerPassword">Wachtwoord:</label>
            <input type="password" id="customerPassword" name="customerPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required
                   title="Het wachtwoord moet tenminste één nummer, één hoofdletter, één kleine letter en tenminste 8 of meer tekens bevatten."><br><br>
            <button type="submit" name="submitRegister" class="btn btn-success">Registreren</button>
        </form>
    </div>
</div>
