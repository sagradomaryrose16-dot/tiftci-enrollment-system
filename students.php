<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];
    $date_of_birth = $_POST['date_of_birth'];
    $religion = $_POST['religion'];
    $contact_number = $_POST['contact_number'];
    $parents_contact = $_POST['parents_contact'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $grade_level = $_POST['grade_level'];

    $sql = "INSERT INTO students (
                last_name, first_name, middle_name, gender, civil_status, date_of_birth,
                religion, contact_number, parents_contact, address, course, grade_level
            ) VALUES (
                '$last_name', '$first_name', '$middle_name', '$gender', '$civil_status', '$date_of_birth',
                '$religion', '$contact_number', '$parents_contact', '$address', '$course', '$grade_level'
            )";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>Student enrolled successfully!</div>";
        header("refresh:2;url=students.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
    }
}

$sql = "SELECT * FROM students";
$student_result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">TIFTCI Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="academic_year.php">Academic Year</a></li>
                    <li class="nav-item"><a class="nav-link" href="level_section.php">Level & Section</a></li>
                    <li class="nav-item"><a class="nav-link" href="faculty.php">Faculty</a></li>
                    <li class="nav-item"><a class="nav-link active" href="students.php">Student List</a></li>
                    <li class="nav-item"><a class="nav-link" href="system_settings.php">System Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="text-center mb-4">Student Enrollment</h1>

        <!-- Student List -->
        <div class="card mb-5">
            <div class="card-header">Student List</div>
            <div class="card-body">
                <?php if ($student_result && $student_result->num_rows > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Gender</th>
                                <th>Grade Level</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; while ($student = $student_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td><?= htmlspecialchars($student['gender']) ?></td>
                                    <td><?= htmlspecialchars($student['grade_level']) ?></td>
                                    <td><?= htmlspecialchars($student['course']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="alert alert-info text-center">No students enrolled yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Enroll Student Form -->
        <div class="card">
            <div class="card-header">Enroll New Student</div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-4"><label class="form-label">Last Name</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">First Name</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Middle Name</label><input type="text" name="middle_name" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select</option><option>Male</option><option>Female</option><option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-select" required>
                            <option value="">Select</option><option>Single</option><option>Married</option><option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Religion</label><input type="text" name="religion" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Parents' Contact</label><input type="text" name="parents_contact" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Course</label>
                        <select name="course" class="form-select" required>
                            <option value="">Select</option>
                            <option>Information Technology</option>
                            <option>Tourism Management</option>
                            <option>Entrepreneurship</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Grade Level</label>
                        <select name="grade_level" class="form-select" required>
                            <option value="">Select</option>
                            <option>First Year</option>
                            <option>Second Year</option>
                            <option>Third Year</option>
                            <option>Fourth Year</option>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="add_student" class="btn btn-success">Enroll Student</button>
                    </div>
                </form>
            </div>
        </div>
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
