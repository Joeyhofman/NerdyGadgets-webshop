<?php
include("../includes/header.php");
include("../../Functions/auth/functions.php");
include("../../Functions/validators/functions.php");
include("../functions/customers/functions.php");
include("../../includes/formmodal.php");
include("../functions/orders/functions.php");

$navigationIndex = "Klanten overzicht";

$user = getAuthenticatedUser();
$userName = $user["FullName"];

$userIsCustomerService = userHasRole($databaseConnection, $user, "klantenservice");
$userIsAdministration = userHasRole($databaseConnection, $user, "administratie");
$userIsAdministrator = userHasRole($databaseConnection, $user, "beheerder");

if(!$user) header("Location: ../login.php");
if(!$userIsCustomerService) header("Location: ../login.php");

$orders = [];
if(isset($_GET["search"])){
    $customerID = htmlspecialchars($_GET["searchQuery"]);
    if(validateInt($customerID)){
        $orders = getCustomerOrderHistory($databaseConnection, $customerID);
    }
}
?>

<div class="row">
    <div class="col-auto">
        <?php include("../includes/sidebar.php"); ?>
    </div>
    <div class="col">
        <div class="container-fluid">
            <div class="d-flex flex-column align-items-center">
            <h1 class="my-5">Bestellingen</h1>
                <form action="" method="GET">
                    <div class="d-flex mt-2 mb-4">
                        <input type="search" name="searchQuery" placeholder="klatnummer" value="<?php if(isset($customerID)) print($customerID); ?>">
                        <button class="btn btn-success mx-3" name="search">Zoeken</button>
                    </div>
                </form>
            </div>
            <?php  if($orders) {?>
            <table class="table text-white bg-dark">
                <thead>
                    <tr>
                    <th scope="col">klantnr</th>
                    <th scope="col">naam</th>
                    <th scope="col">bestelnummer</th>
                    <th scope="col">producten</th>
                    <th scope="col">aantal</th>
                    <th scope="col">stuksprijs</th>
                    <th scope="col">Totaal bedrag</th>
                    <th scope="col">datum bestelling</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order){ ?>
                    <?php  
                    $products = getProductsByOrder($databaseConnection, $order["orderID"]);
                    $totalPrice = 0;
                    ?>
                    <tr>
                        <td scope="row"><?php print($order["customerID"]); ?></td>
                        <td scope="row"><?php print($order["CustomerName"]); ?></td>
                        <td scope="row"><?php print($order["orderID"]); ?></td>
                        <td scope="row"><?php foreach($products as $product){ print($product["stockitemname"]."<br>"); } ?></td>
                        <td scope="row"><?php foreach($products as $product){ print($product["quantity"]."<br>"); } ?></td>
                        <td scope="row"><?php foreach($products as $product){ print("€ ".$product["unitprice"]."<br>"); $totalPrice += ($product["unitprice"] * $product["quantity"]); } ?></td>
                        <td scope="row"><?php print("€ ".number_format(floatval($totalPrice), 2)); ?></td>
                        <td scope="row"><?php print($order["orderdate"]); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                <?php } ?>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
?>