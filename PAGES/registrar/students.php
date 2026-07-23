<?php
include ('../../PHP/Queries-registrar.php');
?>
    <title>Student Management - Registrar Portal</title>


<div class="container-fluid">
    <div class="row">
        
    <?php
include ('Layout/sidebar.php');
?>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Student Management</h2>
                   
                </div>

              
                <?php
$totalStudents = countTotalStudents($conn);
$activeStudents = countActiveStudents($conn);
$newEnrollments = countNewEnrollments($conn);
?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card student-card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <h2 class="card-text"><?php echo $totalStudents; ?></h2>
                <p class="mb-0">All Programs</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card student-card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Active Students</h5>
                <h2 class="card-text"><?php echo $activeStudents; ?></h2>
                <p class="mb-0">Currently Enrolled</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card student-card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">New Enrollments</h5>
                <h2 class="card-text"><?php echo $newEnrollments; ?></h2>
                <p class="mb-0">This Semester</p>
            </div>
        </div>
    </div>
</div>


               
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <tbody>
    <?php
    $students_result = getAllStudents($conn);

    if (mysqli_num_rows($students_result) > 0) {
        while ($student = mysqli_fetch_assoc($students_result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($student['studentId']) . '</td>';
            echo '<td>' . htmlspecialchars($student['firstName']) . ' ' . htmlspecialchars($student['lastName']) . '</td>';
            echo '<td>' . htmlspecialchars($student['Course']) . '</td>';
            echo '<td>' . htmlspecialchars($student['grade_level']) . '</td>';
            echo '<td>';
            echo '<span class="badge ';
            if ($student['status'] == 'Active') echo 'bg-success';
            elseif ($student['status'] == 'Pending') echo 'bg-warning';
            else echo 'bg-secondary';
            echo '">' . htmlspecialchars($student['status']) . '</span>';
            echo '</td>';
            echo '<td>';
            // View button
            echo '<a href="Studentinfo.php?studentNo=' . urlencode($student['studentNo']) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a> ';
            // Edit button
            echo '<a href="../../PHP/Updatestudentstatus.php?studentNo=' . urlencode($student['studentNo']) . '" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a> ';
            // Delete button with confirmation
            echo '<a href="../../PHP/Deletestudent.php?studentNo=' . urlencode($student['studentNo']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this student?\');"><i class="fas fa-trash"></i></a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">No students found.</td></tr>';
    }
    ?>
</tbody>

      
                                </tbody>
                            </table>
                        </div>

                        
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <select class="form-select" required>
                                <option value="">Select Course</option>
                                <option>Computer Science</option>
                                <option>Engineering</option>
                                <option>Business</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year Level</label>
                            <select class="form-select" required>
                                <option value="">Select Year</option>
                                <option>1st Year</option>
                                <option>2nd Year</option>
                                <option>3rd Year</option>
                                <option>4th Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="">Select Status</option>
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Student</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
