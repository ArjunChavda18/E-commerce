<?php
session_start();
include __DIR__ . '/../includes/db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $pdo->quote($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username=$username AND password='$password'";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
        $_SESSION['admin'] = $username;
        header("Location: products.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f1efac, #e3ebb9);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container label {
            display: block;
            margin: 8px 0 5px;
            text-align: left;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            background: #4facfe;
            border: none;
            padding: 12px;
            color: #fff;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .login-container button:hover {
            background: #00c6ff;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
