<?php

function ThrowHTTPForbiddenError(){
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    print("<h1>403 Forbidden</h1>");
    exit;
}