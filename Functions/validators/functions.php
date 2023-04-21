<?php

function validateFullname($fullName){
    /*
        ^               // begin van de regel
    [a-zA-Z]{2,}    // naam met tenminste 2 characters
    \s              // zoekt naar een spatie tussen de voor en achternaam
    [a-zA-Z]{1,}    // minimaal één character
    \'?-?           // mogelijk een **'** of **-**
    [a-zA-Z]{2,}    // een naam met tenminste 2 characters
    \s?             // mogelijk nog een spatie
    ([a-zA-Z]{1,})? // mogelijk nog een naam
    */
    if(preg_match("/^[a-zA-Z]{2,}\s[a-zA-Z]{1,}[a-zA-Z]{2,}\s?([a-zA-Z]{1,})?&/", $fullName)){
        return true;
    }else{
        return false;
    }
}

function validateInt($int){
    return filter_var($int, FILTER_VALIDATE_INT);
}

function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password){
    if(preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password)){
        return true;
    }else{
        return false;
    }
}