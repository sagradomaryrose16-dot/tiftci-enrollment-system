<?php
include('db.php');
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $school_name = $_POST['school_name'];
    $school_address = $_POST['school_address'];
    $logo = $_FILES['logo']['name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($logo);
    move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

    $sql = "UPDATE system_settings SET school_name='$school_name', school_address='$school_address', logo='$target_file' WHERE id=1";
    $conn->query($sql);
}

$settings_query = "SELECT * FROM system_settings WHERE id = 1";
$settings_result = $conn->query($settings_query);
$settings_data = $settings_result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">

</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">System Settings</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">School Name:</label>
                        <input type="text" name="school_name" class="form-control" value="<?php echo $settings_data['school_name']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">School Address:</label>
                        <input type="text" name="school_address" class="form-control" value="<?php echo $settings_data['school_address']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">School Logo:</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <?php if (!empty($settings_data['logo'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo $settings_data['logo']; ?>" alt="Current Logo" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="update_settings" class="btn btn-success">Update Settings</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary ms-2">Back to Dashboard</a>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                </form>
            </div>
        </div>

    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">&copy; <?php echo date("Y"); ?> TIFTCI Admin Portal. All rights reserved.</p>
            <small>Designed with ❤️ by MARY ROSE SAGRADO</small>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
