<?php
include 'db.php';

$year_error = '';
$success_message = '';

// Add academic year
if (isset($_POST['add_year'])) {
    $year = trim($_POST['year']);

    if (empty($year)) {
        $year_error = "Academic year is required.";
    } elseif (!preg_match("/^\d{4}--\d{4}$/", $year)) {
        $year_error = "Format must be YYYY--YYYY.";
    } else {
        $check_query = "SELECT * FROM academic_year WHERE year = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $year_error = "Academic year already exists.";
        } else {
            $insert_query = "INSERT INTO academic_year (year) VALUES (?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("s", $year);
            if ($stmt->execute()) {
                $success_message = "Academic year added successfully!";
                header("Location: academic_year.php");
                exit();
            } else {
                $year_error = "Error adding academic year.";
            }
        }
    }
}

// Delete academic year
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM academic_year WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: academic_year.php");
    exit();
}

// Edit academic year
if (isset($_POST['edit_year'])) {
    $edit_id = $_POST['edit_id'];
    $edit_year = trim($_POST['edit_year_val']);

    if (empty($edit_year)) {
        $year_error = "Academic year is required.";
    } elseif (!preg_match("/^\d{4}--\d{4}$/", $edit_year)) {
        $year_error = "Format must be YYYY--YYYY.";
    } else {
        $check_query = "SELECT * FROM academic_year WHERE year = ? AND id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $edit_year, $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $year_error = "Another academic year with the same value already exists.";
        } else {
            $update_query = "UPDATE academic_year SET year = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $edit_year, $edit_id);
            if ($stmt->execute()) {
                $success_message = "Academic year updated successfully!";
                header("Location: academic_year.php");
                exit();
            } else {
                $year_error = "Error updating academic year.";
            }
        }
    }
}

$query = "SELECT * FROM academic_year ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Year</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">TIFTCI Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="academic_year.php">Academic Year</a></li>
                <li class="nav-item"><a class="nav-link" href="level_section.php">Level & Section</a></li>
                <li class="nav-item"><a class="nav-link" href="faculty.php">Faculty</a></li>
                <li class="nav-item"><a class="nav-link" href="students.php">Student List</a></li>
                <li class="nav-item"><a class="nav-link" href="system_settings.php">System Settings</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Academic Year Management</h2>

    <?php if ($year_error): ?>
        <div class="alert alert-danger"><?= $year_error ?></div>
    <?php elseif ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- Add Academic Year -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Academic Year (YYYY--YYYY):</label>
            <input type="text" class="form-control" name="year" required pattern="\d{4}--\d{4}">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button type="submit" class="btn btn-primary" name="add_year">Add</button>
        </div>
    </form>

    <!-- Academic Year List -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Academic Year</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                    <input type="text" name="edit_year_val" value="<?= htmlspecialchars($row['year']) ?>" class="form-control me-2" required pattern="\d{4}--\d{4}">
                                    <button type="submit" class="btn btn-success" name="edit_year">Update</button>
                                </form>
                            <?php else: ?>
                                <?= htmlspecialchars($row['year']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= $row['updated_at'] ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this year?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <div class="container">
        <p class="mb-1">&copy; <?= date("Y") ?> TIFTCI Admin Portal. All rights reserved.</p>
        <small class="text-secondary">Designed with ❤️ by MARY ROSE SAGRADO</small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
