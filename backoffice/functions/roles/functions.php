<?php

function getEmployees(mysqli $dbconnection): array{
    $sql = "SELECT personID, FullName, roles.name as role
    FROM people
    LEFT JOIN roles USING(roleID)
    WHERE isEmployee = 1 ORDER BY personID DESC;";
    $statment = mysqli_prepare($dbconnection, $sql);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function getEmployeeByID(mysqli $dbconnection, int $personID): array{
    $sql = "SELECT personID, FullName, roles.name as role
    FROM people
    LEFT JOIN roles USING(roleID)
    WHERE isEmployee = 1 AND personID = ?";

    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "i", $personID);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function getAllRoles(mysqli $dbconnection){
    $sql = "SELECT roleID, name FROM roles;";

    $statment = mysqli_prepare($dbconnection, $sql);
    $result = mysqli_stmt_execute($statment);
    $result = mysqli_stmt_get_result($statment);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function updateRole(mysqli $dbconnection, int $personID,int $roleID): bool {
    $sql = "UPDATE people SET roleID = ? WHERE PersonID = ?";

    $statment = mysqli_prepare($dbconnection, $sql);
    mysqli_stmt_bind_param($statment, "ii", $roleID, $personID);
    $result = mysqli_stmt_execute($statment);
    return $result;
}