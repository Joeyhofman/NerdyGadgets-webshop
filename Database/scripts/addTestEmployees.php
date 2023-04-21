<?php
include(__DIR__."/../database.php");

$dbConnection = connectToDatabase();

function registerEmployee(mysqli $dbConnection, string $fullName, string $preferedName,string $searchName, bool $isPermittedToLogin,string $logonName, bool $isExternalLoginprovider, string $password, bool $isSystemUser, bool $isEmployee, bool $isSalesPerson, string $email, int $lastEditiedBy, $validFrom, $validTo, $roleID){
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    try{
        $sql = "INSERT INTO people
    (FullName,
    PreferredName,
    SearchName,
    isPermittedToLogon,
    LogonName,
    isExternalLogonProvider,
    HashedPassword,
    IsSystemUser,
    isEmployee,
    IsSalesPerson,
    UserPreferences,
    PhoneNumber,
    FaxNumber,
    EmailAddress,
    lastEditedBy,
    validFrom,
    ValidTo,
    roleID)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $Statement = mysqli_prepare($dbConnection, $sql);
    $userpreferences = "{}";
    $phoneNumber = "+31611111111";
    $faxNumber = "0412312323";
    $lastEditiedBy = 1;

    mysqli_stmt_bind_param($Statement, "sssbsbsbbbssssissi", $fullName, $preferedName, $searchName, $isPermittedToLogin, $logonName, $isExternalLoginprovider, $hashedPassword, $isSystemUser, $isEmployee, $isSalesPerson, $userpreferences, $phoneNumber, $faxNumber, $email, $lastEditiedBy, $validFrom, $validTo, $roleID);
    $execution = mysqli_stmt_execute($Statement);
    if($execution){
        print("user created");
    }else{
        print("user not created");
    }
    }catch(Exception $e){
        print($e->getMessage());
    }
}

registerEmployee($dbConnection, "beheerder beheerder", "beheerder", "beheerder", 1, "beheerder", false, "Beheerder123", false, 1, false, "beheerder@beheerder.com", 1, "2000-10-10", "200-10-11", 1);
registerEmployee($dbConnection, "administratie administratie", "administratie", "administratie", 1, "administratie", false, "Administratie123", false, 1, false, "administratie@administratie.com", 1, "2000-10-10", "200-10-11", 2);
registerEmployee($dbConnection, "klantenservice klantenservice", "klantenservice", "klantenservice", 1, "klantenservice", false, "Klantenservice123", false, 1, false, "klantenservice@klantenservice.com", 1, "2000-10-10", "200-10-11", 3);
