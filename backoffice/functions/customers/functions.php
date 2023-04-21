<?php

function getCustomerByID(mysqli $dbconnection, int $customerID): array{
    $sql = "select CustomerID, customername, deliveryaddressline2, deliverypostalcode, cities.cityname, LogonName as EmailAddress, phonenumber, IsBlacklisted
    FROM customers
    JOIN cities
    ON customers.DeliveryCityID = cities.cityID WHERE CustomerID = ?";

    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $customerID);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function getCustomers(mysqli $dbconnection): array{
    $sql = "select CustomerID, customername, deliveryaddressline2, deliverypostalcode, cities.cityname, LogonName as EmailAddress, phonenumber, IsBlacklisted
    FROM customers
    JOIN cities
    ON customers.DeliveryCityID = cities.cityID LIMIT 100;";
    $statment = mysqli_prepare($dbconnection, $sql);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function customerHasOrderHistory(mysqli $dbconnection, int $customerID): bool{
    $sql = "select orderID
    FROM orders
    JOIN customers USING(CustomerID)
    WHERE CustomerID = ? LIMIT 1;";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $customerID);
    mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $rows = mysqli_num_rows($result);

    if($rows > 0) return false;
    return true;
}

function updateCustomer(mysqli $dbconnection, int $customerID, string $fullName, string $address, string $city, string $emailAddress, string $phoneNumber){

    $citySql = "SELECT CityID, cityname FROM cities
    WHERE Cityname = ?;";
    $cityStatment = mysqli_prepare($dbconnection, $citySql);
    mysqli_stmt_bind_param($cityStatment, "s", $city);
    mysqli_stmt_execute($cityStatment);
    $cityResult = mysqli_stmt_get_result($cityStatment);
    $cityResult = mysqli_fetch_all($cityResult, MYSQLI_ASSOC);

    if(count($cityResult) > 0){
        $cityID = $cityResult[0]["CityID"];
    }else{
        $lastCityID = mysqli_fetch_array(mysqli_query($dbconnection, "SELECT cityID FROM cities ORDER BY cityID DESC LIMIT 1"), MYSQLI_ASSOC)["CityID"] + 1;
        $insertCitySql = "INSERT INTO cities (cityID, cityName, stateProvinceID, LastEditedBy, validFrom, validTo) VALUES (?, ?, ?, ?, ?, ?);";
        $insertCityStatment = mysqli_prepare($dbconnection, $insertCitySql);
        $provinceID = 1;
        $lastEditedBy = 1;
        $validFrom = "2022-12-12";
        $validTo = "2023-12-12";
        mysqli_stmt_bind_param($insertCityStatment, "isiiss", $lastCityID, $city, $provinceID, $lastEditedBy, $validFrom, $validTo);
        $insertCityResult = mysqli_stmt_execute($insertCityStatment);
        if($insertCityResult){
            $cityID = $lastCityID;
        }else{
            return false;
        }
    }

    $sql = "UPDATE Customers SET  CustomerName = ?, DeliveryCityID = ?, DeliveryAddressLine2 = ?, PhoneNumber = ?, LogonName = ? WHERE CustomerID = ?";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "sisssi", $fullName, $cityID, $address, $phoneNumber, $emailAddress, $customerID);
    $result = mysqli_stmt_execute($statment);
    return $result;
}

function deleteCustomer(mysqli $dbconnection, int $customerID){
    $sql = "DELETE FROM Customers WHERE CustomerID = ?";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $customerID);
    $result = mysqli_stmt_execute($statment);
    return $result;
}

function setBlacklistStatus(mysqli $dbconnection, int $customerID, bool $blackliststatus): bool {
    $sql = "UPDATE Customers SET IsBlacklisted = ? WHERE CustomerID = ?;";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "ii", $blackliststatus, $customerID);
    $result = mysqli_stmt_execute($statment);
    return $result;
}