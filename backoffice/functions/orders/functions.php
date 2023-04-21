<?php
function getCustomerOrderHistory(mysqli $dbconnection, $customerID): array{
    $sql = "SELECT DISTINCT customerID, CustomerName, orderID, orderdate
    From orders
    JOIN Customers USING(CustomerID)
    WHERE customerID = ?
    ORDER BY orderdate DESC;";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $customerID);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function getProductsByOrder(mysqli $dbconnection, $orderID): array{
    $sql = "SELECT stockitemname, quantity, stockitems.unitprice
    FROM orderlines OL
    JOIN stockitems USING(stockitemID)
    WHERE orderID = ?;";
    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $orderID);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}