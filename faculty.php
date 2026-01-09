<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// Assign faculty to level-course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_faculty'])) {
    $faculty_id = $_POST['faculty_id'];
    $level_section_id = $_POST['level_section_id'];

    $sql = "INSERT INTO faculty_assignments (faculty_id, level_section_id) VALUES ('$faculty_id', '$level_section_id')";
    if (!$conn->query($sql)) {
        die("Error assigning faculty: " . $conn->error);
    }
}

// Fetch faculty data
$faculty_query = "SELECT * FROM faculty";
$faculty_result = $conn->query($faculty_query);
if (!$faculty_result) {
    die("Error fetching faculty data: " . $conn->error);
}

// Fetch levels and courses
$level_section_query = "SELECT * FROM levels_courses";
$level_section_result = $conn->query($level_section_query);
if (!$level_section_result) {
    die("Error fetching levels and courses: " . $conn->error);
}

// Add new faculty
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faculty'])) {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];

    $sql = "INSERT INTO faculty (last_name, first_name, middle_name, gender, contact_number, email, address, date_of_birth) 
            VALUES ('$last_name', '$first_name', '$middle_name', '$gender', '$contact_number', '$email', '$address', '$date_of_birth')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>New faculty added successfully!</div>";
        header("refresh:2;url=faculty_list.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$sql = "SELECT * FROM faculty";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching faculty data: " . $conn->error);
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Page</title>
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
                    <li class="nav-item"><a class="nav-link active" href="faculty.php">Faculty</a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php">Student List</a></li>
                    <li class="nav-item"><a class="nav-link" href="system_settings.php">System Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4 text-center">Faculty Management</h1>

        <!-- Faculty Assignments -->
        <div class="container mt-5">
            <h2 class="text-center">Faculty Assignments</h2>
            <?php
            include('db.php');
            $assignment_query = "SELECT fa.id, f.first_name, f.last_name, lc.levels, lc.section
                                FROM faculty_assignments fa
                                JOIN faculty f ON fa.faculty_id = f.id
                                JOIN levels_courses lc ON fa.level_section_id = lc.id";
            $assignment_result = $conn->query($assignment_query);
            ?>
            <?php if ($assignment_result && $assignment_result->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Name</th>
                            <th>Assigned Level & Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $assignment_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['levels'] . " - " . $row['section']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-info text-center">No faculty assignments found.</p>
            <?php endif; ?>
        </div>



    <div class="card mt-5">
        <div class="card-header">Assign Faculty to Level & Section</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="faculty_id" class="form-label">Select Faculty</label>
                        <select name="faculty_id" id="faculty_id" class="form-select" required>
                            <option value="">-- Select Faculty --</option>
                            <?php while ($faculty = $faculty_result->fetch_assoc()): ?>
                                <option value="<?= $faculty['id'] ?>">
                                    <?= htmlspecialchars($faculty['first_name'] . " " . $faculty['last_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="level_section_id" class="form-label">Select Level & Section</label>
                        <select name="level_section_id" id="level_section_id" class="form-select" required>
                            <option value="">-- Select Level & Section --</option>
                            <?php while ($level = $level_section_result->fetch_assoc()): ?>
                                <option value="<?= $level['id'] ?>">
                                    <?= htmlspecialchars($level['levels'] . " - " . $level['section']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" name="assign_faculty" class="btn btn-primary">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <!-- Add New Faculty -->
        <div class="card">
            <div class="card-header">Add New Faculty</div>
            <div class="card-body">
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" name="add_faculty" class="btn btn-success">Add Faculty</button>
                    </div>
                </form>
            </div>
        </div>

        

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        
    </div>
    
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">&copy; <?php echo date("Y"); ?> TIFTCI Admin Portal. All rights reserved.</p>
            <small>Designed with ❤️ by MARY ROSE SAGRADO</small>
        </div>
    </footer>
</body>
</html>

