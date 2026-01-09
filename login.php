<?php 
include('db.php');
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        
        if (!$stmt) {
            $message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin'] = $row['username'];
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $message = "Invalid username or password.";
                }
            } else {
                $message = "Invalid username or password.";
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('uploads/Graphic Design.png') no-repeat center center fixed;
            background-size:100% 100%;
        }

        .login-container {
            width: 300px;
            height: 400px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #000;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .form-wrapper {
            padding-left: 200px;
        }

        @media (max-width: 576px) {
            .form-wrapper {
                padding-left: 0;
                display: flex;
                justify-content: center;
            }

            .login-container {
                width: 90%;
                height: auto;
            }
        }
    </style>
</head>
<body class="vh-100 d-flex align-items-center">
    <div class="form-wrapper">
        <div class="login-container">
            <h2 class="text-center mb-4">Admin Login</h2>
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger text-center"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
