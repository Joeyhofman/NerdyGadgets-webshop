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
    <div class="container">
        <?php if(isset($productRemovedAlert)){ ?>
            <div class="alert alert-success" role="alert">
                <span>Product verwijderd!</span>
            </div>
        <?php } ?>
    </div>
    <div class="d-flex justify-content-around mb-5">
        <div class="offset-1">
            <h1>Winkelmandje </h1>
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
                    <form action="order.php" >
                        <button type="submit" class="btn btn-success px-5 py-1 mb-4 mt-5" <?php if ($amountOfProducts == 0) print("disabled"); ?>>Bestellen</button>
                    </form>
                </div>
            </div>
            <!-- Eind overzicht -->
        </div>
        <!-- Begin items -->
        <div class="col" role="list" aria-label="Items in winkelmand">
            <?php
                if($amountOfProducts == 0){
                    ?>
                    <div role="alert" aria-labelledby="shoppingcart__empty">
                        <h3 id="shoppingcart__empty" class="text-center">U heeft nog geen producten in uw winkelmand.</h3>
                    </div>
                    <?php
                }else {
                foreach($shoppingcart as $stockItemID => $product){ ?>
                <!-- Begin item -->
                    <div class="productframe row mb-2" role="listitem">
                        <div class="col-auto">
                            <?php
                            if (!empty($product['ImagePath'])) { ?>
                                <div class="ImgFrame"
                                    style="background-image: url('<?php print "Public/StockItemIMG/" . $product['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;">
                                </div>
                            <?php } else if (isset($product['BackupImagePath'])) { ?>
                                <div class="ImgFrame"
                                    style="background-image: url('<?php print "Public/StockGroupIMG/" . $product['BackupImagePath']; ?>'); background-size: cover;">
                                </div>
                            <?php }
                            ?>
                        </div>
                        <div class="col-4">
                            <h1 class="StockItemID">Artikelnummer: <?php print $stockItemID; ?></h1>
                            <p class="StockItemName"><?php print $product["StockItemName"]; ?></p>
                            <p>Prijs per stuk:  <?php print("€" . $product["StockItemPrice"]); ?></p>
                        </div>
                        <div class="col-1">
                            <div id="ShoppingcartProductAmountControls">
                                <form action="" method="POST">
                                    <input type="hidden" name="StockItemID" value="<?php print($stockItemID); ?>">

                                    <button id="ShoppingcartProductAmountControls__IncrementButton" class="btn btn-success w-100" name="increment"  type="submit" aria-label="aantal verhogen naar <?php print($product["Amount"] + 1); ?>">+</button>
                                    <p class="ShoppingcartProductAmountControls__Amount text-center my-1"><?php print($product["Amount"]); ?></p>
                                    <button id="ShoppingcartProductAmountControls__DecrementButton" class="btn btn-danger w-100" name="decrement" type="submit" aria-label="aantal verlagen naar <?php print($product["Amount"] - 1); ?>" <?php if($product["Amount"] == 1) print("disabled"); ?>>-</button>
                                </form>
                            </div>
                        </div>
                        <div class="offset-1 col-2">
                            <form action="" method="POST">
                                <input type="hidden" name="StockItemID" value="<?php print ($stockItemID);?>"  >
                                <button type="submit" name="delete" aria-label="Verwijder"><i aria-hidden="true" class="fas fa-trash p-2"></i></button>
                            </form>
                            <h1 class="StockItemPriceText"><?php print("€" . calculateTotalPriceForProductInCart($stockItemID)); ?></h1>
                            <h6>Inclusief BTW </h6>
                        </div>
                    </div>
                    <!-- Eind item -->
                <?php } ?>
            <?php }?>
        </div>
        <!-- Eind items -->
    </div>
</div>
<?php
include __DIR__ . "./includes/footer.php";
?>

