<?php
session_start();
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $database = new Database();
        $db = $database->getConnection();

        // Query to fetch user by email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password and handle login
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRS | Login</title>
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="wrapper sign">
        <form class="login-box" method="POST">
            <div class="login-header">
                <span>Login</span>
            </div>

            <div class="input_box">
                <input type="email" name="email" id="user" class="input-field" placeholder="Email" required />
                <i class="fa fa-user icon"></i>
            </div>

            <div class="input_box">
                <input type="password" name="password" id="password" class="input-field" placeholder="Password" required />
                <i class="fa fa-lock icon"></i>
            </div>

            <div class="input-box">
                <input type="submit" value="Login" class="input-submit" />
            </div>

            <div class="register">
                <span>Don't have an account? <a href="signup.php">Register</a></span>
            </div>
        </form>
    </div>
</body>

</html>
