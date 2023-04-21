<?php
function getBiggestOrders(mysqli $dbconnection): array{
    $sql = "select CustomerID, CustomerName, COUNT(*) as AmountOfOrders, SUM(unitprice * quantity) as totalPrice
    FROM orderlines
    JOIN orders USING(orderID)
    JOIN customers USING(CustomerID)
    WHERE orderdate > DATE(NOW() - INTERVAL 1 MONTH)
    GROUP BY orderID
    ORDER BY totalprice DESC;";
    $statment = mysqli_prepare($dbconnection, $sql);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}