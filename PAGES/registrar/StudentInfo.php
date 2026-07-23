<?php
include ('../../PHP/Queries-registrar.php');

if (isset($_GET['studentNo'])) {
    $studentNo = $_GET['studentNo'];
    $query = "SELECT * FROM student WHERE studentNo = '$studentNo'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No student selected.";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include ('Layout/sidebar.php'); ?>

        <div class="col p-4">
            <h2>Student Information</h2>

            <p><strong>Student Number:</strong> <?php echo htmlspecialchars($student['studentId']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['firstName']) . ' ' . htmlspecialchars($student['middleName']) . ' ' . htmlspecialchars($student['lastName']); ?></p>
            <p><strong>Course:</strong> <?php echo htmlspecialchars($student['Course']); ?></p>
            <p><strong>Year Level:</strong> <?php echo htmlspecialchars($student['grade_level']); ?></p>

         
           <!-- Profile Picture -->
<p><strong>Profile Picture:</strong><br>
<?php
    if (!empty($student['picture']) && file_exists($student['picture'])) {
        $webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $student['picture']);
        echo '<img src="' . $webPath . '" alt="Profile Picture" width="150">';
    } else {
        echo '<img src="/enrollment/CSS/Pictures/default-profile.png" alt="Default Picture" width="150">';
    }
?>
</p>

            </p>

            <!-- Good Moral -->
            <p><strong>Good Moral:</strong><br>
<?php
    if (!empty($student['goodMoral']) && file_exists($student['goodMoral'])) {
        $webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $student['goodMoral']);
        echo '<img src="' . $webPath . '" alt="Good Moral" width="300">';
    } else {
        echo 'No Good Moral Uploaded.';
    }
?>
</p>

            </p>

            <!-- Grade Card -->
         
           <p><strong>Grade Card:</strong><br>
<?php
    if (!empty($student['gradeCard']) && file_exists($student['gradeCard'])) {
        $webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $student['gradeCard']);
        echo '<img src="' . $webPath . '" alt="Grade Card" width="300">';
    } else {
        echo 'No Grade Card Uploaded.';
    }
?>
</p>

            </p>

            <a href="Editinfo.php?studentNo=<?php echo urlencode($student['studentNo']); ?>" class="btn btn-primary">Edit Student</a>
            <a href="students.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
