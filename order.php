<?php
include "./Functions/product/functions.php";
include "./Functions/cart/functions.php";


if(isset($_POST["increment"])) incrementProductInCart($_POST["StockItemID"]);
if(isset($_POST["decrement"])) decrementProductInCart($_POST["StockItemID"]);
if(isset($_POST["delete"])) {
    removeItemFromCart($_POST["StockItemID"]);
    $productRemovedAlert = true;
}


$continueShoppingURL = "browse.php";


include __DIR__ . "./includes/header.php";


$amountOfProducts = calculateAmountOfProductInCart();
$shoppingcart = getCart();
$totalPrice = calculateTotalPrice();
?>

<body>
<div class="container-fluid">

    <div class="d-flex justify-content-around mb-5">
        <div class="offset-1">
            <h1>Bestellen </h1>
        </div>
        <div>
            <a href="<?php print($continueShoppingURL); ?>" class="btn btn-success btn-lg">Verder winkelen</a>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-3">
            <!-- Begin overzicht -->
            <div class="ShoppingcartOverview mt-2">
                <div class="text-left">
                    <h2 id="ShoppingcartOverview__Header my-2 ">Overzicht</h2>
                    <p id="ShoppingcartOverview__AmountOfProducts">Aantal producten: <?php print($amountOfProducts); ?></p>
                    <p id="ShoppingcartOverview__TotalPrice">Totaalprijs: <?php print("€" . $totalPrice); ?></p>
                </div>
            </div>
            <!-- Eind overzicht -->
        </div>
        <!-- Begin formulier -->
        <div class="col" role="list">
            <form action="./idealdemo.php" method="post">
                <label for="customerName">naam:</label>
                <input type="text" id="customerName" name="customerName" value="<?php if (isset($_SESSION["User"]["Name"])) { print($_SESSION["User"]["Name"]); } ?>" required><br><br>
                <label for="customerAddress">adres:</label>
                <input type="text" id="customerAddress" name="customerAddress" value="<?php if (isset($_SESSION["User"]["Address"])) { print($_SESSION["User"]["Address"]); } ?>" required><br><br>
                <label for="customerCity">woonplaats:</label>
                <input type="text" id="customerCity" name="customerCity" value="<?php if (isset($_SESSION["User"]["City"])) { print($_SESSION["User"]["City"]); } ?>" required><br><br>
                <label for="customerEmail">email:</label>
                <input type="email" id="customerEmail" name="customerEmail" value="<?php if (isset($_SESSION["User"]["LogonName"])) { print($_SESSION["User"]["LogonName"]); } ?>" required><br><br>
                <input type="submit" value="doorgaan naar ideal" class="btn btn-success">
            </form>

        </div>
        <!-- Eind formulier -->
    </div>
</div>
<?php
include __DIR__ . "./includes/footer.php";
?>

