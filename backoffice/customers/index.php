<?php
include("../includes/header.php");
include("../../Functions/auth/functions.php");
include("../../Functions/validators/functions.php");
include("../../Functions/email/functions.php");
include("../functions/customers/functions.php");
include("../../includes/formmodal.php");

//Messages
$customerDeleteSucessfully = null;
$customerDeleteUnsucessfully = null;

$customerBlacklistedSuccessfully = null;
$customerBlacklistedUnsuccessfully = null;

$user = getAuthenticatedUser();
$userName = $user["FullName"];

$userIsCustomerService = userHasRole($databaseConnection, $user, "klantenservice");
$userIsAdministration = userHasRole($databaseConnection, $user, "administratie");
$userIsAdministrator = userHasRole($databaseConnection, $user, "beheerder");

if(!$user) header("Location: ../login.php");
if(!$userIsCustomerService && !$userIsAdministration) header("Location: ../login.php");

if(isset($_POST["BlacklistCustomer"])){
    $customerID = htmlspecialchars($_POST["CustomerID"]);
    $blacklistStatus = htmlspecialchars($_POST["BlacklistStatus"]);
    $email = htmlspecialchars($_POST["EmailAddress"]);

    if(!validateInt($customerID) || !validateEmail($email)){
        $customerBlacklistedUnsuccessfully= true;
    }else{
        if(setBlacklistStatus($databaseConnection, $customerID, !$blacklistStatus)){
            $subject = "geblacklist";
            if(!$blacklistStatus){
                if(sendMail($subject, $email, "customers/customeraccountblacklisted")){
                    $customerBlacklistedSuccessfully = true;
                }
            }
            $customerBlacklistedSuccessfully = true;
        }
    }
}

if(isset($_POST["deleteCustomer"])){
    $customerID = htmlspecialchars($_POST["CustomerID"]);
    $email = htmlspecialchars($_POST["EmailAddress"]);
    if(!customerHasOrderHistory($databaseConnection, $customerID)){
        $customerDeleteUnsucessfully = true;
    }else{
        deleteCustomer($databaseConnection, $customerID);
        $subject = "Account verwijderd";
        if(sendMail($subject, $email, "customers/customeraccountdeleted")){
            $customerDeleteSucessfully = true;
        }
    }
}

$customers = [];
if(isset($_GET["search"])){
    $customerID = htmlspecialchars($_GET["searchQuery"]);
    if(validateInt($customerID)){
        $customers = getCustomerByID($databaseConnection, $customerID);
    }
}
?>

<div class="row">
    <div class="col-auto">
        <?php include("../includes/sidebar.php"); ?>
    </div>
    <div class="col">
        <div class="container">
            <div class="d-flex flex-column align-items-center">
            <h1 class="my-5">klanten zoeken</h1>
                <form action="" method="GET">
                    <div class="d-flex mt-2 mb-4">
                        <input type="search" name="searchQuery" placeholder="klatnummer">
                        <button class="btn btn-success mx-3" name="search">Zoeken</button>
                    </div>
                </form>
            </div>
            <?php if($customerDeleteSucessfully) {?>
                <div class="alert alert-success" role="alert">
                    Klant successvol verwijderd!
                </div>
            <?php } ?>
            
            <?php if($customerDeleteUnsucessfully) {?>
                <div class="alert alert-danger" role="alert">
                    Kon klant niet verwijderen omdat deze een bestelgeschiedenis heeft!
                </div>
            <?php } ?>
            
            
            <?php if($customerBlacklistedSuccessfully) {?>
                <div class="alert alert-success" role="alert">
                    Klant successvol <?php print($blacklistStatus ? "gewhitelist" : "geblacklist"); ?>!
                </div>
            <?php } ?>
            
            <?php if($customerBlacklistedUnsuccessfully) {?>
                <div class="alert alert-danger" role="alert">
                    Kon klant niet blacklisten!
                </div>
            <?php } ?>

            <?php if($customers) { ?>
            <table class="table text-white bg-dark">
                <thead>
                    <tr>
                    <th scope="col">klantnr</th>
                    <th scope="col">naam</th>
                    <th scope="col">adres</th>
                    <th scope="col">woonplaats</th>
                    <th scope="col">email</th>
                    <th scope="col">telefoonnummer</th>
                    <?php if($userIsAdministration) {?>
                    <th scope="col">Blacklist status</th>
                    <?php } ?>
                    <?php if($userIsAdministration) {?>
                    <th scope="col">Blacklisten</th>
                    <?php } ?>
                    <?php if($userIsCustomerService) {?>
                    <th scope="col">Bewereken</th>
                    <?php } ?>
                    <?php if($userIsAdministration) { ?>
                    <th scope="col">verwijderen</th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $customer) {  ?>
                        <?php $totalOrderPrice = 0; ?>
                        <tr>
                        <th scope="row"><?php print($customer["CustomerID"]); ?></th>
                        <td><?php print($customer["customername"]); ?></td>
                        <td><?php print($customer["deliveryaddressline2"]); ?></td>
                        <td><?php print($customer["cityname"]); ?></td>
                        <td><?php print($customer["EmailAddress"]); ?></td>
                        <td><?php print($customer["phonenumber"]); ?></td>
                        <?php if($userIsAdministration){ ?>
                        <td><?php print($customer["IsBlacklisted"] ? "geblacklist" : "gewhitelist"); ?></td>
                        <?php } ?>
                        <?php if($userIsAdministration){ ?>
                        <form action="" method="POST">
                            <input type="hidden" name="CustomerID" value="<?php print($customer["CustomerID"]);?>">
                            <input type="hidden" name="EmailAddress" value="<?php print($customer["EmailAddress"]);?>">
                            <input type="hidden" name="BlacklistStatus" value="<?php print($customer["IsBlacklisted"]);?>">
                            <td><button type="submit" name="BlacklistCustomer" class="btn btn-secondary"><?php print($customer["IsBlacklisted"] ? "whitelist" : "blacklist"); ?></button></td>
                        </form>
                        <?php } ?>
                        <?php if($userIsCustomerService){ ?>
                        <td><a href="./edit.php?ID=<?php print($customer["CustomerID"]); ?>" class="btn btn-info">Bewerken</a></td>
                        <?php } ?>
                        <?php if($userIsAdministration) { ?>
                        <td><button class="btn btn-danger" data-toggle="modal" data-target="#delete-<?php print($customer["CustomerID"]); ?>">Verwijderen</button></td>
                        <?php printConfirmModal("verwijderen klant", "Weet u zeker dat u deze klant wil verwijderen?", "delete-".$customer["CustomerID"], "", true, "Verwijderen", "sluiten", "deleteCustomer", ["CustomerID" => $customer["CustomerID"], "EmailAddress" => $customer["EmailAddress"]]); ?>
                        <?php } ?>
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