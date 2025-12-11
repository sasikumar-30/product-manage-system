<?php

$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'sasikumar30';
$db_name = 'product_db';

$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);

if($conn->connect_error){
    die("connection failed:". $conn->connect_error);
}

?>