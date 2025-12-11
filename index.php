
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #ffd6e0, #fff0f5); /* rose milk gradient */
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Poppins', sans-serif;
}

.card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px 30px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    color: #333;
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    0% {opacity:0; transform: translateY(-20px);}
    100% {opacity:1; transform: translateY(0);}
}

.card h2 {
    margin-bottom: 30px;
    font-weight: 700;
    text-align: center;
    color: #d63384; /* rose accent */
}

.form-control {
    border-radius: 10px;
    padding: 12px 15px;
    background: rgba(255,255,255,0.6);
    border: 1px solid #f5c6d0;
    color: #333;
}

.form-control:focus {
    background: rgba(255,255,255,0.8);
    color: #333;
    box-shadow: 0 0 0 0.2rem rgba(214,51,132,0.25);
}

.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f78fb3, #d63384); /* rose button gradient */
    border: none;
    font-weight: bold;
    color: white;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(214,51,132,0.3);
}

.text-center a {
    color: #d63384;
    text-decoration: underline;
    transition: all 0.3s ease;
}

.text-center a:hover {
    color: #f78fb3;
}
.text-danger {
    font-size: 0.9rem;
    margin-top: 5px;
    display: block;
}
</style>
</head>
<body>

<?php
session_start();

// initialize variables
$username = $password = "";
$usernameErr = $passwordErr = "";
$msg = "";

// sanitize function
function sanitaize($data){
    return trim(stripslashes($data));
}

// form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // USERNAME validation 
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = sanitaize($_POST["username"]);
    }

    // PASSWORD validation 
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = sanitaize($_POST["password"]);
    }

    // **IMPORTANT FIX**
    // Only run login logic when NO validation errors
    
    $admin_user = "admin";
    $admin_pass_hash = '$2y$10$zhrYLmYqFm4s.LNKfB60z.HUyNaVe52BsQW.GCNpOgROlxvevLlKi';
   if ($username === $admin_user && password_verify($password, $admin_pass_hash)) {
    $_SESSION["admin"] = $username;
    $_SESSION['success_msg'] = "Admin Panel Accessed";
    header("Location: dashboard.php");
    exit();
} else {
    $msg = "<p class='text-danger'>Invalid username or password</p>";
}
}
?>


<div class="card">
    <h2>Admin Login</h2>
    <?php echo $msg; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>">
            <span class="text-danger"><?php echo $usernameErr?></span>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" class="form-control" placeholder="Password" name="password" id="passwordField" value="<?php echo $password; ?>">
            <span class="text-danger"><?php echo $passwordErr; ?></span>
            <!-- Eye icon for toggle -->
             <span id="togglePassword" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#d63384;">
                🙈
            </span>
</div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Show/hide password
const toggle = document.getElementById('togglePassword');
const passwordField = document.getElementById('passwordField');

toggle.addEventListener('click', () => {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    toggle.textContent = type === 'password' ? '🙈' : '👁️';
});
</script>
</body>
</html>