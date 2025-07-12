<?php
session_start();
require 'db.php'; // contains your DB connection logic

$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php"); // or admin_panel.php
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ReWear - Login</title>
  <link rel="stylesheet" href="auth.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<style>
    body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(120deg, #fdf6e3 60%, #f7e9c4 100%);
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.auth-container {
  background: #fffaf0;
  padding: 36px 38px 30px 38px;
  border-radius: 16px;
  box-shadow: 0 6px 24px rgba(180, 140, 80, 0.10);
  width: 100%;
  max-width: 370px;
  text-align: center;
}

h2 {
  color: #7c5c2b;
  margin-bottom: 28px;
  font-size: 2rem;
  letter-spacing: 1px;
}

.form-group {
  margin-bottom: 20px;
  text-align: left;
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 7px;
  color: #7c5c2b;
  letter-spacing: 0.5px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 11px 40px 11px 13px;
  border-radius: 7px;
  border: 1.5px solid #e7d7b6;
  font-size: 15px;
  background: #fffef9;
  color: #5a4a2b;
  transition: border 0.2s;
  box-sizing: border-box;
}

input:focus {
  border-color: #d8b07e;
  outline: none;
}

.password-wrapper {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
  color: #bca77a;
  padding: 0;
}

.toggle-password:focus {
  outline: none;
}

.auth-btn {
  width: 100%;
  padding: 13px;
  background: linear-gradient(90deg, #e7d7b6 60%, #d8b07e 100%);
  border: none;
  border-radius: 7px;
  color: #7c5c2b;
  font-size: 17px;
  font-weight: bold;
  cursor: pointer;
  margin-top: 10px;
  margin-bottom: 8px;
  transition: background 0.2s;
}

.auth-btn:hover {
  background: linear-gradient(90deg, #f7e9c4 60%, #e7d7b6 100%);
}

.auth-link {
  font-size: 14px;
  color: #a08a5c;
  margin-top: 8px;
}

.auth-link a {
  color: #bca77a;
  text-decoration: none;
  font-weight: 600;
}

.auth-link a:hover {
  text-decoration: underline;
}

.error-text {
  color: #c0392b;
  font-size: 12px;
  margin-top: 4px;
  min-height: 16px;
} 
</style>
<body>
  <div class="auth-container">
    <h2>Sign In</h2>

    <!-- Show error if any -->
    <?php if (!empty($error)): ?>
      <div style="color: red; margin-bottom: 10px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="login-email">Email</label>
        <input type="email" id="login-email" name="email" placeholder="Email Address" required />
      </div>
      <div class="form-group password-group">
        <label for="login-password">Password</label>
        <div class="password-wrapper">
          <input type="password" id="login-password" name="password" placeholder="Enter Password" required />
          <button type="button" class="toggle-password" onclick="togglePassword('login-password', 'login-eye-password')">
            <i class="fas fa-eye" id="login-eye-password"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="auth-btn">Login</button>
      <p class="auth-link">Don't have an account? <a href="register.php">Register</a></p>
    </form>
  </div>
  <script>
  function togglePassword(fieldId, eyeId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(eyeId);
    const isVisible = field.type === "text";
    field.type = isVisible ? "password" : "text";
    icon.classList.toggle("fa-eye", isVisible);
    icon.classList.toggle("fa-eye-slash", !isVisible);
  }
</script>

</body>
</html>

