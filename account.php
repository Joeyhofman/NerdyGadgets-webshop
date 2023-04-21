<?php
include "Functions/Users/functions.php";

include __DIR__ . "./includes/header.php";

// Login checks
// -- submit knop, email en wachtwoord komen overeen in database
if (isset($_POST["submitLogin"])) {
    if (authenticateCustomer($_POST["customerEmail"], $_POST["customerPassword"]) == 1) {
        // Start de sessie als dat nog niet gedaan is
        if(session_id() == "") session_start();

        // Slaat NAW gegevens op in een sessie
        saveUserData($_POST["customerEmail"], getUserName($_POST["customerEmail"]), getUserAddress($_POST["customerEmail"]), getUserCity($_POST["customerEmail"]));

    } else {

        /* User feedback: Onjuist email of wachtwoord */
        $_SESSION["Error"]["InvalidCredientials"] = "<h2>Onjuist Email of wachtwoord!</h2>";

        header("Location: login.php");
    }

}

// Registratie checks
// -- submit knop, email niet al in gebruik
elseif (isset($_POST["submitRegister"])) {
    if (logonNameNotInUse($_POST["customerEmail"]) == 0) {
        // Start de sessie als dat nog niet gedaan is
        if (session_id() == "") session_start();

        // Maakt een nieuwe klant aan in de database
        registerCustomer($_POST["customerEmail"], $_POST["customerName"], $_POST["customerAddress"], $_POST["customerCity"], $_POST["customerPassword"]);

        // Slaat NAW gegevens op in een sessie
        saveUserData($_POST["customerEmail"], $_POST["customerName"], $_POST["customerAddress"], $_POST["customerCity"]);
    } else {

        /* User feedback: Email is al in gebruik */
        $_SESSION["Error"]["EmailInUse"] = "<h2>Dit Email is al in gebruik!</h2>";

        header("Location: login.php");

    }
}

// Update checks
// -- update knop
elseif(isset($_POST["updateData"])) {

    // update NAW gegevens
    updateUserName($_POST["customerEmail"], $_POST["customerName"]);
    updateUserAddress($_POST["customerEmail"], $_POST["customerAddress"]);
    updateUserCity($_POST["customerEmail"], $_POST["customerCity"]);

    // sla NAW gegevens op in een sessie
    saveUserData($_POST["customerEmail"], $_POST["customerName"], $_POST["customerAddress"], $_POST["customerCity"]);

    print("<h2>Je gegevens zijn aangepast!</h2>");
}

elseif(isset($_POST["logOut"])) {
    /* User Feedback: Je bent uitgelogd
        ik weet dat het geen error is. */
    $_SESSION["Error"]["LoggedOut"] = "<h2>Je bent uitgelogd!</h2>";

    logOut();

    header("Location: login.php");
}

// anders stuur terug naar inlog pagina
else {

    header("Location: login.php");
}

?>



<body>
<div>
    <div>
        <h1>Accountgegevens</h1>
        <form method="post" action="account.php">
            <input type="hidden" id="customerEmail" name="customerEmail" value="<?php print($_POST["customerEmail"]); ?>">
            <label for="customerName">Naam:</label>
            <input type="text" id="customerName" name="customerName" value="<?php print(getUserName($_POST["customerEmail"])); ?>" required><br><br>
            <label for="customerAddress">Adres:</label>
            <input type="text" id="customerAddress" name="customerAddress" value="<?php print(getUserAddress($_POST["customerEmail"])); ?>" required><br><br>
            <label for="customerCity">Woonplaats:</label>
            <input type="text" id="customerCity" name="customerCity" value="<?php print(getUserCity($_POST["customerEmail"])); ?>" required><br><br>
            <button type="submit" name="updateData" class="btn btn-success">Update Gegevens</button>
        </form>
        <br>
        <form method="post" action="account.php">
            <button type="submit" name="logOut" class="btn btn-success">Uitloggen</button>
    </div>
</div>
