<?php
include ('../../PHP/Queries-registrar.php');
?>
<title>Faculty Management - Registrar Portal</title>

<div class="container-fluid">
    <div class="row">
        
    <?php
    include ('Layout/sidebar.php');
    ?>

    <div class="col-md-9 col-lg-10 main-content">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Faculty Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                    <i class="fas fa-plus"></i> Add Faculty
                </button>
            </div>

            <?php
            $totalFaculty = countTotalFaculty($conn);
            ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card student-card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Faculty</h5>
                            <h2 class="card-text"><?php echo $totalFaculty; ?></h2>
                            <p class="mb-0">All Departments</p>
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
                                    <th>Faculty ID</th>
                                    <th>Name</th>
                      
                                 
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $faculty_result = getAllFaculty($conn);

                            if (mysqli_num_rows($faculty_result) > 0) {
                                while ($faculty = mysqli_fetch_assoc($faculty_result)) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($faculty['facultyID']) . '</td>';
                                    echo '<td>' . htmlspecialchars($faculty['FirstName']) . ' ' . htmlspecialchars($faculty['LastName']) . '</td>';
                                    
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="EditFaculty.php?facultyID=' . urlencode($faculty['facultyID']) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a> ';
                                    echo '<a href="../../PHP/DeleteFaculty.php?facultyID=' . urlencode($faculty['facultyID']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this faculty member?\');"><i class="fas fa-trash"></i></a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center">No faculty members found.</td></tr>';
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
</div>
<!-- Add Faculty Modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Faculty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addFacultyForm" method="POST" action="../../PHP/AddFaculty.php">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="firstName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lastName" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middleName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                    </div>
                 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Faculty</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
