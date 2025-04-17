<?php
session_start();
require_once "db.php";

$error = '';
$username = '';
$login_type = 'user'; // default role is user

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs from form
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $login_type = $_POST['login_type'] ?? 'user'; // Get selected role from dropdown

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        try {
            // Query to get the user from the database based on username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables based on login type (user or admin)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Assuming the column 'role' is used to store 'admin' or 'user'

                // Redirect to appropriate page based on role
                if ($_SESSION['role'] === 'admin') {
                    // Admin login - redirect to admin dashboard
                    header("Location: admin-dashboard.php");
                    exit();
                } else {
                    // User login - redirect to home page or user dashboard
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "Invalid username or password";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - AgroFertMart</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .login-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #2e7d32; text-align: center; }
        .error { color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 4px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background-color: #4CAF50; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #388E3C; }
        .signup-link { text-align: center; margin-top: 15px; }
        .signup-link a { color: #2e7d32; text-decoration: none; }
        .signup-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="login_type">Login as:</label>
                <select name="login_type" id="login_type">
                    <option value="user" <?= $login_type === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $login_type === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up</a>
        </div>
    </div>
</body>
</html>
