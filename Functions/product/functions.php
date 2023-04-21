<?php
function getVoorraadTekst($actueleVoorraad) {
    if ($actueleVoorraad > 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}
function berekenVerkoopPrijs($adviesPrijs, $btw) {
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}

function getPopulairProducts($databaseConnection, $stockItemID){
    $sql = "SELECT stockitemID, StockItemName, stockitems.TaxRate, RecommendedRetailPrice, stockitemimages.imagepath, stockgroups.imagepath as backupimagepath
    FROM orderlines
    JOIN stockitemstockgroups USING(stockitemID)
    JOIN stockgroups USING(stockgroupID)
    JOIN stockitemimages USING(stockitemID)
    JOIN stockitems USING(stockitemID)
    WHERE orderID IN (
    select orderID
    FROM orderlines
    WHERE StockItemID = ?
    order by quantity DESC
    )
    GROUP BY stockitemID
    ORDER BY COUNT(stockitemID) DESC LIMIT 3;";

    $Statement = mysqli_prepare($databaseConnection, $sql);
    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
    mysqli_stmt_execute($Statement);
    $result = mysqli_stmt_get_result($Statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}