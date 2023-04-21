<!-- de inhoud van dit bestand wordt bovenaan elke pagina geplaatst -->
<?php
if(session_id() == "") session_start();
include __DIR__."/../database.php";
include __DIR__."/../Functions/cart/calculateAmountOfProductInCart.php";
$databaseConnection = connectToDatabase();
$ItemsInCart = calculateAmountOfProductInCart();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="./Public/JS/fontawesome.js"></script>
    <script src="./Public/JS/jquery.min.js"></script>
    <script src="./Public/JS/bootstrap.min.js"></script>
    <script src="./Public/JS/popper.min.js"></script>
    <script src="./Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="./Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="./Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./Public/CSS/typekit.css">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar" role="navigation" aria-label="hoofd">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
<!-- code voor US3: zoeken -->
<ul id="ul-class-navigation" role="navigation" aria-label="tweede">
            <li>
                <a href="login.php" id="shoppingcart-icon">
                    <i aria-hidden="true" class="fas fa-user"></i>
                </a>
                <a href="cart.php" id="shoppingcart-icon" aria-label="winkelmand: <?php print($ItemsInCart); ?> items in winkelmand">
                    <i aria-hidden="true" class="fas fa-shopping-cart"></i>
                    <span aria-hidden="true"><?php print($ItemsInCart); ?></span>
                </a>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search search"></i> Zoeken</a>
            </li>
        </ul>
<!-- einde code voor US3 zoeken -->
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


