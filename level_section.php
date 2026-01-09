<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// Sanitize function
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Success/Error Message
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $level = sanitizeInput($_POST['level']);
    $section = sanitizeInput($_POST['section']);
    
    if (!$level || !$section) {
        $message = 'Both Level and Section are required!';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO levels_courses (levels, section) VALUES (?, ?)");
        $stmt->bind_param("ss", $level, $section);
        if ($stmt->execute()) {
            $message = 'Level and Section added successfully!';
            $messageType = 'success';
            header("Location: level_section.php");
            exit();
        } else {
            $message = 'Error: ' . $stmt->error;
            $messageType = 'danger';
        }
        $stmt->close();
    }
}

// EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = sanitizeInput($_POST['id']);
    $level = sanitizeInput($_POST['level']);
    $section = sanitizeInput($_POST['section']);
    
    if (!$level || !$section) {
        $message = 'Both Level and Section are required!';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("UPDATE levels_courses SET level = ?, section = ? WHERE id = ?");
        $stmt->bind_param("ssi", $level, $section, $id);
        if ($stmt->execute()) {
            $message = 'Level and Section updated successfully!';
            $messageType = 'success';
            header("Location: level_section.php");
            exit();
        } else {
            $message = 'Error: ' . $stmt->error;
            $messageType = 'danger';
        }
        $stmt->close();
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM levels_courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = 'Level and Section deleted successfully!';
        $messageType = 'success';
        header("Location: level_section.php");
        exit();
    } else {
        $message = 'Error: ' . $stmt->error;
        $messageType = 'danger';
    }
    $stmt->close();
}

// FETCH
$result = $conn->query("SELECT * FROM levels_courses ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level & Section (Card View)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">TIFTCI Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="academic_year.php">Academic Year</a></li>
                    <li class="nav-item"><a class="nav-link active" href="level_section.php">Level & Section</a></li>
                    <li class="nav-item"><a class="nav-link" href="faculty.php">Faculty</a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php">Student List</a></li>
                    <li class="nav-item"><a class="nav-link" href="system_settings.php">System Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="text-center mb-4">Level & Section</h2>

        <!-- Success/Error Message -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ADD FORM -->
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="level" class="form-control" placeholder="Grade Level" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="section" class="form-control" placeholder="Section" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add" class="btn btn-success w-100">Add</button>
            </div>
        </form>

        <!-- CARDS -->
        <div class="row">
            <?php while ($column = $result->fetch_assoc()): ?>
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($column['levels']) ?> - <?= htmlspecialchars($column['section']) ?></h5>
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $column['id'] ?>">Edit</button>
                                <a href="?delete=<?= $column['id'] ?>" onclick="return confirm('Delete this record?')" class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT MODAL -->
                <div class="modal fade" id="editModal<?= $column['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <input type="hidden" name="id" value="<?= $column['id'] ?>">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Level & Section</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Grade Level</label>
                                    <input type="text" name="level" class="form-control" value="<?= htmlspecialchars($column['level']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Section</label>
                                    <input type="text" name="section" class="form-control" value="<?= htmlspecialchars($column['section']) ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">&copy; <?php echo date("Y"); ?> TIFTCI Admin Portal. All rights reserved.</p>
            <small>Designed with ❤️ by MARY ROSE SAGRADO</small>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
