<?php
if(session_id() == "") session_start();

function validateCSRFToken(string $token): bool{
    if (!$token || $token !== $_SESSION['CSRFToken']) {
        return false;
    }else{
        return true;
    }
}

function createCSRFToken(): string{
    $token = md5(uniqid(mt_rand(), true));
    $_SESSION['CSRFToken'] = $token;
    return $token;
}

function getAuthenticatedUser(): array|null {
    return $_SESSION["AuthUser"] ?? null;
}

function setAuthenticatedUser($user): void{
    $_SESSION["AuthUser"] = $user;
}

function logout(): bool {
    try{
        unset($_SESSION["AuthUser"]);
        return true;
    }catch(Exception $e){
        return false;
    }
}

function authenticate(string $email, string $password, mysqli $dbConnection): array|null {
    try{
            $sql = "SELECT * FROM people WHERE emailaddress = ? AND IsPermittedToLogon = 1 and isEmployee = 1;";

            $Statement = mysqli_prepare($dbConnection, $sql);
            mysqli_stmt_bind_param($Statement, "s", $email);
            mysqli_stmt_execute($Statement);
            $emailCheckResult = mysqli_stmt_get_result($Statement);
            $emailCheckResult = mysqli_fetch_assoc($emailCheckResult);
             if($emailCheckResult){
                 $passwordInDatabase = $emailCheckResult["HashedPassword"];
                 if(password_verify($password, $passwordInDatabase)){
                     return $emailCheckResult;
                 }else{
                     return null;
                 }
             }else{
                return null;
             }
            }catch(Exception $e){
                return null;
         }
}

function userHasRole($dbConnection, $user, $role){
    $userID = $user["PersonID"] ?? null;

    $sql = "SELECT name FROM people
    JOIN roles USING(roleID) WHERE personID = ?";

    $Statement = mysqli_prepare($dbConnection, $sql);
    mysqli_stmt_bind_param($Statement, "i", $userID);
    mysqli_stmt_execute($Statement);
    $roleCheckResult = mysqli_stmt_get_result($Statement);
    $roleCheckResult = mysqli_fetch_assoc($roleCheckResult);
    if($roleCheckResult["name"] == $role){
        return true;
    }else{
        return false;
    }
}