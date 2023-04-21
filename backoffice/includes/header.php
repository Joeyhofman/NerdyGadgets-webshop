<?php
if(session_id() == "") session_start();
include "../../database.php";
$databaseConnection = connectToDatabase();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>NerdyGadgets - Backoffice</title>

    <!-- Javascript -->
    <script src="../../Public/JS/fontawesome.js"></script>
    <script src="../../Public/JS/jquery.min.js"></script>
    <script src="../../Public/JS/bootstrap.min.js"></script>
    <script src="../../Public/JS/popper.min.js"></script>
    <script src="../../Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="../../Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="../../Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../Public/CSS/typekit.css">
</head>
<body>
<div class="Background">
    <div class="col-12">
        <div id="SubContent">


