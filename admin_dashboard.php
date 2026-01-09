<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch student count
$student_query = "SELECT COUNT(*) as total_students FROM students";
$student_result = $conn->query($student_query);
if (!$student_result) {
    die("Student query failed: " . $conn->error);  // Improved error handling
}
$total_students = $student_result->fetch_assoc()['total_students'];

// Fetch faculty count (changed to faculty table)
$faculty_query = "SELECT COUNT(*) as total_faculty FROM faculty";  // Updated table name
$faculty_result = $conn->query($faculty_query);
if (!$faculty_result) {
    die("Faculty query failed: " . $conn->error);  // Improved error handling
}
$total_faculty = $faculty_result->fetch_assoc()['total_faculty'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="admin_dashboard.php">TIFTCI Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="academic_year.php">Academic Year</a></li>
                    <li class="nav-item"><a class="nav-link" href="level_section.php">Level & Section</a></li>
                    <li class="nav-item"><a class="nav-link" href="faculty.php">Faculty</a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php">Student List</a></li>
                    <li class="nav-item"><a class="nav-link" href="system_settings.php">System Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-5">
        <div class="text-center mb-4">
            <h1 class="display-6 fw-semibold">Welcome to the Admin Dashboard</h1>
            <p class="text-muted">Overview of student and faculty data</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Enrolled Students</h5>
                        <h2 class="card-text"><?php echo $total_students; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Faculty</h5>
                        <h2 class="card-text"><?php echo $total_faculty; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-1">&copy; <?php echo date("Y"); ?> TIFTCI Admin Portal. All rights reserved.</p>
            <small class="text-secondary">Designed with ❤️ by MARY ROSE SAGRADO</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
