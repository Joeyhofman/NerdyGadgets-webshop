<?php

// REGISTRATIE
// maak nieuwe klant aan als NAW is ingevuld + wachtwoord + emailadres
function registerCustomer($email, $name, $address, $city, $password) {
    if (logonNameNotInUse($email) == 0) {
        $connection = connectToDatabase();
        $statement = mysqli_prepare($connection, "
        INSERT INTO customers (LogonName, HashedPassword, CustomerName, PostalAddressLine2, DeliveryAddressLine2, 
                               BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, 
                               DeliveryCityID, PostalCityID, AccountOpenedDate, StandardDiscountPercentage, 
                               IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, 
                               DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, 
                               LastEditedBy, ValidFrom, ValidTo)
        VALUES (?, ?, ?, ?, ?, 
                1, 1, 1, 1, 1, 1, \"2000-01-01\", 0.000, 0, 0, 0, \"(000) 000-0000\", \"(000) 000-0000\", 
                \"localhost\", \"localhost\", 0, \"PO 0\", 0, 1, \"2000-01-01 00:00:00\", \"9999-12-31-23:59:59\");
        ");
        mysqli_stmt_bind_param($statement, 'sssss', $email, $password, $name, $city, $address);
        mysqli_stmt_execute($statement);
        return mysqli_stmt_affected_rows($statement) == 1;
    }
}



// checkt of de gebruiker kan registreren
//      -- emailadres nog niet in gebruik
function logonNameNotInUse($email) {
    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
    SELECT logonName FROM customers WHERE logonName = ?
    ");
    mysqli_stmt_bind_param($statement, 's', $email);
    mysqli_stmt_execute($statement);

    mysqli_stmt_store_result($statement);
    return (mysqli_stmt_num_rows($statement));
}





// LOGIN
// checkt of klantnummer en wachtwoord overeenkomen in de database
function authenticateCustomer($email, $password) {
    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
    SELECT LogonName FROM customers 
    WHERE LogonName = ? AND hashedPassword = ?
    ");
    mysqli_stmt_bind_param($statement, 'ss', $email, $password);
    mysqli_stmt_execute($statement);

    mysqli_stmt_store_result($statement);
    return (mysqli_stmt_num_rows($statement));

}


// SAVE
// sla de gegevens op in een sessie
function saveUserData($userLogonName, $userName, $userAddress, $userCity) {
    $_SESSION["User"]["LogonName"] = $userLogonName;
    $_SESSION["User"]["Name"] = $userName;
    $_SESSION["User"]["Address"] = $userAddress;
    $_SESSION["User"]["City"] = $userCity;
}

// OPHALEN
// haal de naam op uit de database van een user
function getUserName($logonName) {

        $connection = connectToDatabase();
        $statement = mysqli_prepare($connection, "
        SELECT CustomerName FROM customers 
        WHERE logonName = ?
        ");
        mysqli_stmt_bind_param($statement, 's', $logonName);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $name);

    while (mysqli_stmt_fetch($statement)) {
        return($name);
    }

    mysqli_stmt_close($statement);

    mysqli_close($connection);

}

function getUserAddress($logonName) {

    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
    SELECT DeliveryAddressLine2 FROM customers 
    WHERE logonName = ?
    ");
    mysqli_stmt_bind_param($statement, 's', $logonName);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $address);

    while (mysqli_stmt_fetch($statement)) {
        return($address);
    }

    mysqli_stmt_close($statement);

    mysqli_close($connection);

}

function getUserCity($logonName) {

    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
    SELECT PostalAddressLine2 FROM customers 
    WHERE logonName = ?
    ");
    mysqli_stmt_bind_param($statement, 's', $logonName);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $city);

    while (mysqli_stmt_fetch($statement)) {
        return($city);
    }

    mysqli_stmt_close($statement);

    mysqli_close($connection);

}

function getUserID($logonName) {

    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
    SELECT CustomerID FROM customers 
    WHERE logonName = ?
    ");
    mysqli_stmt_bind_param($statement, 's', $logonName);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $userID);

    while (mysqli_stmt_fetch($statement)) {
        return($userID);
    }

    mysqli_stmt_close($statement);

    mysqli_close($connection);

}

function updateUserName($logonName, $newName) {
    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
        UPDATE customers SET CustomerName = ? 
                         WHERE LogonName = ? 
        ");
    mysqli_stmt_bind_param($statement, 'ss', $newName, $logonName);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;

}

function updateUserAddress($logonName, $newAddress) {
    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
        UPDATE customers SET DeliveryAddressLine2 = ? 
                         WHERE LogonName = ? 
        ");
    mysqli_stmt_bind_param($statement, 'ss', $newAddress, $logonName);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;

}

function updateUserCity($logonName, $newCity) {
    $connection = connectToDatabase();
    $statement = mysqli_prepare($connection, "
        UPDATE customers SET PostalAddressLine2 = ? 
                         WHERE LogonName = ? 
        ");
    mysqli_stmt_bind_param($statement, 'ss', $newCity, $logonName);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;

}

function logOut() {
    $_SESSION["User"] = array();
}

