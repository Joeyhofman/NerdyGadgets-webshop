<?php
include("../includes/header.php");
include("../../Functions/auth/functions.php");
include("../functions/roles/functions.php");
include("../../includes/formmodal.php");

//Messages
$EmployeeRoleUpdatedSuccessfully = null;
$EmployeeRoleUpdatedUnsuccessfully = null;

$navigationIndex = "Klanten overzicht";

$user = getAuthenticatedUser();
$userName = $user["FullName"];

$userIsCustomerService = userHasRole($databaseConnection, $user, "klantenservice");
$userIsAdministration = userHasRole($databaseConnection, $user, "administratie");
$userIsAdministrator = userHasRole($databaseConnection, $user, "beheerder");

if(!$user) header("Location: ../login.php");
if(!$userIsAdministrator) header("Location: ../login.php");

$roles = getAllRoles($databaseConnection);

if(isset($_POST["UpdateRole"])){
    $roleID = htmlspecialchars($_POST["Role"]);
    $personID = htmlspecialchars($_POST["Employee"]);

    if(validateInt($roleID) && validateInt($personID)){
        if(updateRole($databaseConnection, $personID, $roleID)){
            $EmployeeRoleUpdatedSuccessfully = true;
        }else{
            $EmployeeRoleUpdatedUnsuccessfully = true;
        }
    }
}

if(isset($_GET["search"])){
    $personID = htmlspecialchars($_GET["SearchQuery"]);
    if(validateInt($personID)){
        $employees = getEmployeeByID($databaseConnection, $personID);
    }else{
        $employees =  getEmployees($databaseConnection);
    }
}else{
    $employees =  getEmployees($databaseConnection);
}
?>

<div class="row">
    <div class="col-auto">
        <?php include("../includes/sidebar.php"); ?>
    </div>
    <div class="col">
        <div class="container">
            <div class="d-flex flex-column align-items-center">
            <h1 class="my-5">Rollen toewijzen</h1>
                <form action="" method="GET">
                    <div class="d-flex mt-2 mb-4">
                        <input type="search" name="SearchQuery" placeholder="medewerkernummer">
                        <button class="btn btn-success mx-3" name="search">Zoeken</button>
                    </div>
                </form>
            </div>
            
            <?php if($EmployeeRoleUpdatedSuccessfully) {?>
                <div class="alert alert-success" role="alert">
                    Medewerker successvol bijgewerkt!
                </div>
            <?php } ?>
            <table class="table text-white bg-dark">
                <thead>
                    <tr>
                    <th scope="col">Medewerkernr</th>
                    <th scope="col">naam</th>
                    <th scope="col">rol</th>
                    <?php if($userIsAdministrator) {?>
                    <th scope="col">Bijwerken</th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($employees as $employee) {  ?>
                        <form action="" method="POST">
                        <input type="hidden" name="Employee" value="<?php print($employee["personID"]); ?>">
                        <tr>
                        <th scope="row"><?php print($employee["personID"]); ?></th>
                        <td><?php print($employee["FullName"]); ?></td>
                        <td>
                            <select name="Role" id="role">
                                <option value="">Geen rol</option>
                                <?php foreach($roles as $role){ ?>
                                    <option value="<?php print($role["roleID"]); ?>" <?php if($employee["role"] == $role["name"]) print("selected"); ?>><?php print($role["name"]); ?></option>    
                                <?php } ?>
                            </select>
                        </td>
                        <td><button type="submit" name="UpdateRole" class="btn btn-success">Bijwerken</button></td>
                        </form>
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
?>