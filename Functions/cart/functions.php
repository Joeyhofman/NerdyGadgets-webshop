<?php

## hier de functies die te maken hebben met met het winkelmandje
if(session_id() == "") session_start();

function getCart(){
    if(isset($_SESSION['shoppingcart'])){
        $cart = $_SESSION['shoppingcart'];
    } else{
        $cart = array();
    }
    return $cart;
}

function saveCart($cart){
    $_SESSION["shoppingcart"] = $cart;
}

function incrementProductInCart($stockItemID){
    $cart = getCart();
    $cart[$stockItemID]["Amount"] += 1;
    saveCart($cart);
}

function decrementProductInCart($stockItemID){
    $cart = getCart();
    if($cart[$stockItemID]["Amount"] <= 1) return;
    $cart[$stockItemID]["Amount"] -= 1;
    saveCart($cart);
}

function calculateTotalPriceForProductInCart($stockItemID){
    $cart = getCart();
    $totalPrice = ($cart[$stockItemID]["StockItemPrice"] * $cart[$stockItemID]["Amount"]);
    return number_format($totalPrice, 2);
}

function calculateTotalPrice(){
    $cart = getCart();
    $totalPrice = array_reduce($cart, function($price, $product){
        $price += ($product["StockItemPrice"] * $product["Amount"]);
        return $price;
    });
    return number_format($totalPrice ?? 0.00, 2);
}

function addProductToCart($product){
    $cart = getCart();
    $stockItemID = $product["StockItemID"];
    $stockItemName = $product["StockItemName"];
    $stockItemPrice = $product["StockItemPrice"];
    $imagePath = $product["ImagePath"];
    $backupPath = $product["BackupImagePath"];

    if(array_key_exists($stockItemID, $cart)){
        $cart[$stockItemID]["Amount"] += 1;
    }else{
        $cart[$stockItemID]["StockItemName"] = $stockItemName; 
        $cart[$stockItemID]["StockItemPrice"] = $stockItemPrice; 
        $cart[$stockItemID]["ImagePath"] = $imagePath; 
        $cart[$stockItemID]["BackupImagePath"] = $backupPath; 
        $cart[$stockItemID]["Amount"] = 1;
    }

    saveCart($cart);
}


function removeItemFromCart($StockItemID){
    $cart = getCart();
    unset($cart[$StockItemID]);
    saveCart($cart);
}

// maak de winkelmand van de gebruiker leeg
function emptyCart() {
    $cart = array();
    saveCart($cart);
}