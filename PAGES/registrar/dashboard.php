<?php
include('../../PHP/Queries-registrar.php');
?>
<title>Admin Dashboard</title>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include('Layout/sidebar.php'); ?>
        <div class="col-md-9 col-lg-10 main-content">
            <div class="p-4">
                <h2 class="mb-4">Admin Dashboard</h2>

                <?php
                // Fetch dashboard stats via query functions
                $totalStudents = countTotalStudents($conn);
                $enrolledStudents = countEnrolledStudents($conn);
                $pendingStudents = countPendingEnrollments($conn);
                $totalCourses = countTotalCourses($conn);
                ?>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Students</h5>
                                <h2 class="card-text"><?php echo $totalStudents; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Enrolled Students</h5>
                                <h2 class="card-text"><?php echo $enrolledStudents; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Pending Enrollments</h5>
                                <h2 class="card-text"><?php echo $pendingStudents; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Courses</h5>
                                <h2 class="card-text"><?php echo $totalCourses; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Status List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Students & Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Full Name</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Enrollment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $studentsResult = getStudentStatuses($conn);
                                if (mysqli_num_rows($studentsResult) > 0) {
                                    while($row = mysqli_fetch_assoc($studentsResult)) {
                                        echo "<tr>";
                                        echo "<td>{$row['studentId']}</td>";
                                        echo "<td>{$row['fullName']}</td>";
                                        echo "<td>{$row['year']}</td>";
                                        echo "<td><span class='badge " . 
                                            ($row['status'] == 'Active' ? 'bg-success' : 
                                            ($row['status'] == 'Pending' ? 'bg-warning' : 'bg-secondary')) . 
                                            "'>{$row['status']}</span></td>";
                                        echo "<td><span class='badge " . 
                                            ($row['enrollment_status'] == 'Enrolled' ? 'bg-success' : 
                                            ($row['enrollment_status'] == 'Active' ? 'bg-primary' : 
                                            ($row['enrollment_status'] == 'Pending' ? 'bg-warning' : 'bg-secondary'))) . 
                                            "'>{$row['enrollment_status']}</span></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No students found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
