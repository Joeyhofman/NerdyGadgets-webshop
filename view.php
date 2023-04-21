<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include "./Functions/product/functions.php";
include "./Functions/cart/functions.php";
include __DIR__ . "./includes/header.php";

//kijken of het product toevoegen formulier is verstuurd.
if (isset($_POST["AddStockItem"])) {
    $itemArray = array(
        "StockItemName" => $_POST["StockItemName"],
        "StockItemID" => $_POST["StockItemID"],
        "StockItemPrice" => $_POST["StockItemPrice"],
        "ImagePath" => $_POST["ImagePath"],
        "BackupImagePath" => ""
    );

    addProductToCart($itemArray);


    header("Location: ./cart.php");
}

$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);

$populairStockItems = getPopulairProducts($databaseConnection, $_GET["id"]);
?>
<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $StockItem['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <form action="" method="POST">
                            <input type="hidden" name="StockItemName" value="<?php print($StockItem["StockItemName"]); ?>">
                            <input type="hidden" name="StockItemID" value="<?php print($StockItem["StockItemID"]); ?>" />
                            <input type="hidden" name="StockItemPrice" value="<?php print(number_format($StockItem['SellPrice'], 2)); ?>" />
                            <input type="hidden" name="ImagePath" value="<?php print($StockItemImage[0]['ImagePath']) ?? ""; ?>" />
                            <button type="submit" name="AddStockItem" <?php if ($StockItem['QuantityOnHand'] <= 0) { print("disabled"); } ?>><i class="fas fa-cart-plus p-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails']; ?></p>

            <p> <?php
                $IsChillerStock = CheckIsChillerStock($_GET['id'], $databaseConnection);
                if ($IsChillerStock == 1){
                    print ("Koelruimtetemperatuur: ".GetColdroomTemperature($databaseConnection)."˚C");;
                }



                ?></p>


        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <div id="StockItemSpecifications" class="w-100 mt-5 p-1">
            <h3 class="text-center mb-4">Mensen kochten ook</h3>
            <div class="d-flex justify-content-around">
                <?php foreach($populairStockItems as $stockitem) {?>
                <a  href="./view.php?id=<?php print($stockitem["stockitemID"]); ?>">
                    <div class="d-flex flex-column">
                        <div class="ImgFrame" style="background-image: url('<?php print "Public/StockItemIMG/" . $stockitem['imagepath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
                        <h5 class="StockItemName mt-3"><?php print($stockitem["StockItemName"]); ?></h5>
                        <h1 class="StockItemPriceText"><?php print "€" . sprintf("%0.2f", berekenVerkoopPrijs($stockitem["RecommendedRetailPrice"], $stockitem["TaxRate"])); ?></h1>
                        <h6 class="StockItemName">Inclusief BTW </h6>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>
<div>
    <div>
        <script src="rating.js"></script>
        <link rel="stylesheet" href="Public/CSS/style.css">
        <div class="container">
            <h2>Reviews</h2>
            <?php
            include "includes/menu.php";
            include "Rating.php";
            $rating = new Rating();
            $itemDetails = $rating->getItem($_GET["id"]);
            foreach($itemDetails as $item){
            $average = $rating->getRatingAverage($item["StockItemID"]);
            ?>
            <div class="row">
            </div>
            <div class="col-sm-4">
                <div><span class="average"><?php printf('%.1f', $average); ?> <small>/ 5</small></span> <span class="rating-reviews"><a href="show_rating.php?item_id=<?php print $item["StockItemID"]; ?>">Rating & Reviews</a></span></div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<?php include("includes/footer.php");?>





