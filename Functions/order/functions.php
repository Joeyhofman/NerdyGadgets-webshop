<?php




// plaats een bestelling met een nieuwe bestelcode en klantnummer 1062 <- dummy klant (database tabel: order)
function placeOrder($connection) {
    $cart = getCart();
    if(count($cart) < 1) return;
    // sql query om order aan te maken.
    // doet nog niks met de winelmand!

    // SECURITYSCAN 4.5: Begint de transactie
    mysqli_begin_transaction($connection);

    // SQL: Maakt een nieuwe bestelling aan
    if (isset($_SESSION["User"]["LogonName"])) {
        $customerID = getUserID($_SESSION["User"]["LogonName"]);

        // get current date
        $orderDate = date("Y-m-d");

        $statement = mysqli_prepare($connection, "
        INSERT INTO orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen) 
        VALUES             (?,       1,                   1,               ?, \"2000-01-01\",  1,                        1,            ?)
        ");
        mysqli_stmt_bind_param($statement, 'iss', $customerID, $orderDate, $orderDate);
    } else {
        $statement = mysqli_prepare($connection, "
        INSERT INTO orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen) 
        VALUES             (1062,       1,                   1,               ?, \"2000-01-01\",  1,                        1,            ?)
        ");
        mysqli_stmt_bind_param($statement, 'ss', $orderDate, $orderDate);
    }

    // voert de query hierboven uit
    mysqli_stmt_execute($statement);

    // haalt de order ID op die zojuist is aangemaakt
    $lastOrderID = (mysqli_insert_id($connection));

    // SQL: Plaats de bstelregels per artikel in het winkelmand
    placeOrderlines($connection, $lastOrderID, $cart);

    // SQL: Update het productaantal
    updateSelectedProducts($connection, $cart);

    // SECURITYSCAN 4.5: Commit de transactie
    mysqli_commit($connection);

}

// loopt over alle artikelen in de winkelmand om het aantal te verminderen
function updateSelectedProducts($connection, $cart) {
    foreach ($cart as $productID => $productValue) {
        updateProductAmount($connection, $productID, $productValue["Amount"]);
    }
    emptyCart();
}

// verminder het artikelaantal van het product met de bestelde hoeveelheid (database tabel: stockitemholdings)
function updateProductAmount($connection, $stockItemID, $amount) {
    // sql query om productaantal up te daten

    $statement = mysqli_prepare($connection, "
        UPDATE stockitemholdings
        SET QuantityOnHand = (QuantityOnHand - ?)
        WHERE StockItemID = ?;
        ");
    mysqli_stmt_bind_param($statement, 'ii', $amount, $stockItemID);
    mysqli_stmt_execute($statement);
}

// plaats meerdere bestelregels met de bestelcode, productcode en het aantal van dat product (database tabel: orderlines)
function placeOrderlines($connection, $orderID, $cart) {

    foreach ($cart as $productID => $productValue) {
        // query orderlines

        $statement = mysqli_prepare($connection, "
        INSERT INTO orderlines (OrderID, StockItemID, Description, PackageTypeID, Quantity, UnitPrice, TaxRate, PickedQuantity, LastEditedBy, LastEditedWhen)
				VALUES         (?,   	 ?, 		  \"Lorem Ipsum\", 1, 		  ?,        ?,		   15.000,  ?,		 	    1,            \"2000-01-01 00:00:00\");
        ");
        mysqli_stmt_bind_param($statement, 'iiidi', $orderID, $productID, $productValue["Amount"], $productValue["StockItemPrice"], $productValue["Amount"]);
        mysqli_stmt_execute($statement);

    }
}
