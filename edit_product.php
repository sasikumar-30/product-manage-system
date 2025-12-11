<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

require_once 'db_connect.php'; // your DB connection

// Initialize variables
$name = $price = $category = $stock = "";
$nameErr = $priceErr = $categoryErr = $stockErr = "";

// sanitize function 
function sanitize($data){
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if ID is provided
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Fetch existing product data
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $name = $row['product_name'];
        $price = $row['product_price'];
        $category = $row['product_category'];
        $stock = $row['product_stock'];
    } else {
        echo "Product not found";
        exit;
    }
} else {
    echo "No product ID specified";
    exit;
}

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty($_POST["name"])){
        $nameErr = "Product name is required";
    } else { $name = sanitize($_POST["name"]); }

    if(empty($_POST["price"])){
        $priceErr = "Product price is required";
    } else { $price = sanitize($_POST["price"]); }

    if(empty($_POST["category"])){
        $categoryErr = "Please select a category";
    } else { $category = sanitize($_POST["category"]); }

    if(empty($_POST["stock"])){
        $stockErr = "Stock value required";
    } else { $stock = sanitize($_POST["stock"]); }

    if($nameErr=="" && $priceErr=="" && $categoryErr=="" && $stockErr==""){

        $stmt = $conn->prepare("UPDATE products SET product_name=?, product_price=?, product_category=?, product_stock=? WHERE id=?");
        $stmt->bind_param("sdssi", $name, $price, $category, $stock, $id);

        if($stmt->execute()){
            $_SESSION["success_msg"] = "Product Updated Successfully ✔";
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error updating product";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #ffd6e0, #fff0f5);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* Card Animation */
.card {
    width: 400px;
    padding: 30px;
    border-radius: 18px;
    background: rgba(255,255,255,0.85);
    box-shadow: 0 8px 32px rgba(214,51,132,0.25);
    opacity: 0;
    transform: translateY(-30px);
    animation: slideFade 0.8s ease forwards;
}

/* Input Animation */
.animated {
    opacity: 0;
    transform: translateY(15px);
    animation: fadeUp 0.7s ease forwards;
}
.animated:nth-child(1){ animation-delay: .2s; }
.animated:nth-child(2){ animation-delay: .3s; }
.animated:nth-child(3){ animation-delay: .4s; }
.animated:nth-child(4){ animation-delay: .5s; }
.animated:nth-child(5){ animation-delay: .6s; }

/* Keyframes */
@keyframes slideFade { to { opacity: 1; transform: translateY(0); } }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

.card h3 { text-align: center; color: #d63384; margin-bottom: 20px; font-weight: 700; }
.btn-pink { background: #d63384; color: white; border-radius: 10px; }
.btn-pink:hover { background: #b71c70; }
.form-control, .form-select { border-radius: 10px; }
</style>
</head>

<body>
<div class="card">
    <h3>Edit Product</h3>

    <form method="post">

        <div class="animated">
            <input type="text" class="form-control mb-2" name="name" placeholder="Product Name" value="<?php echo $name; ?>">
            <small class="text-danger"><?php echo $nameErr; ?></small>
        </div>

        <div class="animated">
            <input type="number" class="form-control mb-2" name="price" placeholder="Product Price" value="<?php echo $price; ?>">
            <small class="text-danger"><?php echo $priceErr; ?></small>
        </div>

        <div class="animated">
            <select class="form-select mb-2" name="category">
                <option value="">Select Category</option>
                <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>Electronics</option>
                <option value="Furniture" <?php if($category=="Furniture") echo "selected"; ?>>Furniture</option>
                <option value="jewelry" <?php if($category=="jewelry") echo "selected"; ?>>jewelry</option>
                <option value="stationery" <?php if($category=="stationery") echo "selected"; ?>>stationery</option>
            </select>
            <small class="text-danger"><?php echo $categoryErr; ?></small>
        </div>

        <div class="animated">
            <input type="number" class="form-control mb-2" name="stock" placeholder="Available Stock" value="<?php echo $stock; ?>">
            <small class="text-danger"><?php echo $stockErr; ?></small>
        </div>

        <div class="animated">
            <button class="btn btn-pink w-100 mt-2" type="submit">Update Product</button>
            <a href="dashboard.php" class="btn btn-pink w-100 mt-2">Back To Dashboard</a>
        </div>

    </form>
</div>
</body>
</html>
