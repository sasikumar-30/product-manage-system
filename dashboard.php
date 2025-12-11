<?php
session_start();

// Prevent browser caching (fix back button after logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect if not logged in
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

// Database connection
require_once 'db_connect.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #fdf2f8;
}
.sidebar {
    width: 240px;
    height: 100vh;
    background: #fce7f3;
    padding: 20px;
    position: fixed;
    border-right: 1px solid #fbcfe8;
}
.sidebar h4 {
    font-weight: bold;
    color: #be185d;
}
.sidebar a {
    display: block;
    padding: 10px 12px;
    margin-top: 10px;
    border-radius: 10px;
    text-decoration: none;
    color: #be185d;
    font-weight: 500;
}
.sidebar a:hover {
    background: #fbcfe8;
}
.content {
    margin-left: 260px;
    padding: 25px;
}
.topbar {
    background: white;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0px 0px 10px #f9c5d5;
}
.table-card {
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0px 0px 8px #f9c5d5;
}
table tbody tr:hover {
    background: #fff0f7 !important;
}
.btn-pink {
    background: #ec4899;
    color: white;
}
.btn-pink:hover {
    background: #db2777;
}
/* Mobile */
@media (max-width: 768px) {
    .sidebar { display: none; }
    .content { margin-left: 0px; padding: 10px; }
    .topbar { text-align: center; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_add.php">Add Product</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <!-- SUCCESS MESSAGE -->
    <?php 
    if(isset($_SESSION['success_msg'])){
        echo '<div id="alertMsg" class="alert alert-success text-center">'.$_SESSION['success_msg'].'</div>';
        unset($_SESSION['success_msg']);
    }
    ?>

    <div class="topbar d-flex justify-content-between">
        <h4 class="m-0">Product Management</h4>
        <a href="product_add.php" class="btn btn-pink">+ Add Product</a>
    </div>
    
    <!-- Category Filter Only -->
     <div class="table-card mb-3">
    <form method="GET" id="filterForm" class="row g-3">

        <div class="col-md-4">
            <select name="category" id="categoryBox" class="form-select">
                <option value="">All Categories</option>
                <option value="Electronics">Electronics</option>
                <option value="Furniture">Furniture</option>
                <option value="jewelry">Jewelry</option>
                <option value="stationery">Stationery</option>
            </select>
        </div>

    </form>
</div>


<?php
if(isset($_GET['msg'])){
    if($_GET['msg'] == 'deleted'){
        echo '<div class="alert alert-success alert-msg">Product deleted successfully!</div>';
    } elseif($_GET['msg'] == 'updated'){
        echo '<div class="alert alert-success alert-msg">Product updated successfully!</div>';
    }
}
?>

    <!-- Product Table -->
    <div class="table-card">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Availabe Stock</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
        
        $search = "";
        $category = "";
        $query = "SELECT * FROM products WHERE 1=1";

        // Category filter
        if(isset($_GET['category']) && $_GET['category'] != ""){
            $category = $_GET['category'];
           $query .= " AND product_category = '$category'";
        }
        
        // Sorting
        $query .= " ORDER BY id DESC";
        
        // Run final query
        $result = $conn->query($query);
        
        if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        echo '<td>'.$row['product_name'].'</td>';
        echo '<td>₹'.$row['product_price'].'</td>';
        echo '<td>'.$row['product_category'].'</td>';
        echo '<td>'.$row['product_stock'].'</td>';
        echo '<td>
                <a class="btn btn-warning btn-sm" href="edit_product.php?id='.$row['id'].'">Edit</a>
                <a class="btn btn-danger btn-sm" href="delete.php?id='.$row['id'].'" 
                   onclick="return confirm(\'Are you sure you want to delete this product?\');">Delete</a>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6" class="text-center">No Products Found</td></tr>';
}
?>
        </tbody>
    </table>
</div>
</div>


<script>
    // Auto submit when category changes
    document.getElementById("categoryBox").addEventListener("change", function() {
        document.getElementById("filterForm").submit();
    });
</script>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert-msg').forEach(a => a.remove());
}, 2000);
</script>

<script>
// Auto-hide success message after 2 seconds
setTimeout(() => {
    let box = document.getElementById("alertMsg");
    if (box) {
        box.style.transition = "0.5s";
        box.style.opacity = "0";
        setTimeout(() => box.remove(), 500);
    }
}, 2000);
</script>

</body>
</html>
