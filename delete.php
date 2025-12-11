<?php

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

include 'db_connect.php'; // your DB connection

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        header("Location: dashboard.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "No ID found.";
}
?>
