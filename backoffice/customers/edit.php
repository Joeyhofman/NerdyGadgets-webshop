<?php
include("../includes/header.php");
include("../../Functions/auth/functions.php");
include("../functions/customers/functions.php");
include("../../Functions/email/functions.php");
include("../../Functions/httperrors/functions.php");

//success/failure message 
$customerUpdatedSuccessfully = false;
$customerUpdatedUnsuccessfully = false;

//require field messages
$FullNameRequiredError = false;
$AddressRequiredError = false;
$cityRequiredError = false;
$EmailAddressRequiredError = false;
$PhonenumberRequiredError = false;


//user authentication
$user = getAuthenticatedUser();
$userIsCustomerService = userHasRole($databaseConnection, $user, "klantenservice");
$userIsAdministration = userHasRole($databaseConnection, $user, "administratie");
$userIsAdministrator = userHasRole($databaseConnection, $user, "beheerder");


if(!$user || !$userIsCustomerService) header("Location: ../login.php");

$customerID = $_GET["ID"] ?? 0;

if(isset($_POST["save"])){
    $token = htmlspecialchars($_POST["CSRFToken"] ?? "");

    if(!validateCSRFToken($token)){
        ThrowHTTPForbiddenError();
    }else{
        if(!$userIsCustomerService) return;
        $fullName = htmlspecialchars($_POST["FullName"]);
        $address = htmlspecialchars($_POST["Address"]);
        $city = htmlspecialchars($_POST["City"]);
        $emailAddress = htmlspecialchars($_POST["EmailAddress"]);
        $phoneNumber = htmlspecialchars($_POST["PhoneNumber"]);

        if(!$fullName){
            $FullNameRequiredError = true;
        }elseif(!$address) {
            $AddressRequiredError = true;
        }elseif(!$city){
            $cityRequiredError = true;
        }elseif(!$emailAddress){
            $EmailAddressRequiredError = true;
        }elseif(!$phoneNumber){
            $PhonenumberRequiredError = true;
        }else{
            if(updateCustomer($databaseConnection, $customerID, $fullName, $address, $city, $emailAddress, $phoneNumber)){
                $subject = "Wijziging klantegegevens";
                $emailTemplateData = [
                    "naam" => $fullName,
                    "adres" => $address,
                    "woonplaats" => $city,
                    "emailadres" => $emailAddress,
                    "telefoonnummer" => $phoneNumber
                ];
                if(sendMail($subject, $emailAddress, "customers/customerdetailschanged", $emailTemplateData)){
                    $customerUpdatedSuccessfully = true;
                }else{
                    $customerUpdatedUnsuccessfully = true; 
                }
            }else{
                $customerUpdatedUnsuccessfully = true; 
            }
        }
    }
}

$customer = getCustomerByID($databaseConnection, $customerID)[0];
?>

<div class="row">
    <div class="col-auto">
        <?php include("../includes/sidebar.php"); ?>
    </div>
    <div class="col">
        <div class="container">
            <h1 class="my-5 text-center">Bewerken klant</h1>
            <?php if($customerUpdatedSuccessfully){ ?>
                <div class="alert alert-success" role="alert">
                    <span>Klant successvol bijgewerkt!</span>
                </div>    
            <?php } ?>
            
            <?php if($customerUpdatedUnsuccessfully){ ?>
                <div class="alert alert-danger" role="alert">
                    <span>Kon klant niet bijwerken!</span>
                </div>    
            <?php } ?>
                <form action="" method="POST">
                    <input type="hidden" name="CSRFToken" value="<?php print(createCSRFToken()); ?>">
                    <input type="hidden" name="CustomerID" value="<?php print($_GET["ID"]); ?>">
                
                    <div class="form-group row">
                        <label for="FullName" class="col-md-4 col-form-label text-md-right">Volledige naam:</label>

                        <div class="col-5">
                            <input id="FullName" type="text" class="form-control" name="FullName" value="<?php print($customer["customername"]); ?>" required autocomplete="email" autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="Address" class="col-md-4 col-form-label text-md-right">Adres:</label>

                        <div class="col-5">
                            <input id="Address" type="text" class="form-control" name="Address" value="<?php print($customer["deliveryaddressline2"]); ?>" required autocomplete="adres">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="City" class="col-md-4 col-form-label text-md-right">Woonplaats:</label>

                        <div class="col-5">
                            <input id="City" type="text" class="form-control" name="City" value="<?php print($customer["cityname"]); ?>" required autocomplete="woonplaats">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="EmailAddress" class="col-md-4 col-form-label text-md-right">Emailadres:</label>

                        <div class="col-5">
                            <input id="EmailAddress" type="text" class="form-control" name="EmailAddress" value="<?php print($customer["EmailAddress"]); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="PhoneNumber" class="col-md-4 col-form-label text-md-right">Telefoonnummer:</label>

                        <div class="col-5">
                            <input id="PhoneNumber" type="text" class="form-control" name="PhoneNumber" value="<?php print($customer["phonenumber"]); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group row justify-content-center mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5" name="save">Opslaan</button>
                    </div>
                </form>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
?>