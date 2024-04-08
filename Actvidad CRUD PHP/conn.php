<?php

$db_host = "localhost";
$db_username = "adm";
$db_password = "P87E[-gtTvt2IdJ6";
$db_database = "tienda_coches";

$db = new mysqli($db_host, $db_username, $db_password, $db_database);
mysqli_query($db, "SET NAMES 'utf8'");

if($db->connect_errno > 0){
    die("No es posible conectarse a la base de datos [ ". $db->connect_error . " ]");
}