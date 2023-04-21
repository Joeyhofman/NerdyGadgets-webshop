<?php 
if(session_id() == "") session_start();

function calculateAmountOfProductInCart(){
    $cart = $_SESSION["shoppingcart"] ?? [];
    $amountOfProducts = array_reduce($cart, function($amount, $product){
        $amount += $product["Amount"];
        return $amount;
    });


    return $amountOfProducts ?? 0;
}
