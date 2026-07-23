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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $course = $_POST['Course'];
    $gradeLevel = $_POST['grade_level'];

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/enrollment/CSS/Pictures/uploads/";

    // Profile Picture
    if (!empty($_FILES['picture']['name'])) {
        $pictureName = time() . "_" . basename($_FILES['picture']['name']);
        $picturePath = $target_dir . $pictureName;
        move_uploaded_file($_FILES['picture']['tmp_name'], $picturePath);
        $webPicturePath = "/enrollment/CSS/Pictures/uploads/" . $pictureName;
    } else {
        $webPicturePath = $student['picture'];
    }

    // Good Moral
    if (!empty($_FILES['goodMoral']['name'])) {
        $goodMoralName = time() . "_" . basename($_FILES['goodMoral']['name']);
        $goodMoralPath = $target_dir . $goodMoralName;
        move_uploaded_file($_FILES['goodMoral']['tmp_name'], $goodMoralPath);
        $webGoodMoralPath = "/enrollment/CSS/Pictures/uploads/" . $goodMoralName;
    } else {
        $webGoodMoralPath = $student['goodMoral'];
    }

    // Grade Card
    if (!empty($_FILES['gradeCard']['name'])) {
        $gradeCardName = time() . "_" . basename($_FILES['gradeCard']['name']);
        $gradeCardPath = $target_dir . $gradeCardName;
        move_uploaded_file($_FILES['gradeCard']['tmp_name'], $gradeCardPath);
        $webGradeCardPath = "/enrollment/CSS/Pictures/uploads/" . $gradeCardName;
    } else {
        $webGradeCardPath = $student['gradeCard'];
    }

    // Update Query
    $updateQuery = "
        UPDATE student SET
            firstName = '$firstName',
            middleName = '$middleName',
            lastName = '$lastName',
            Course = '$course',
            grade_level = '$gradeLevel',
            picture = '$webPicturePath',
            goodMoral = '$webGoodMoralPath',
            gradeCard = '$webGradeCardPath'
        WHERE studentNo = '$studentNo'
    ";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: studentinfo.php?studentNo=" . urlencode($studentNo));
        exit;
    } else {
        echo "Error updating student: " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include ('Layout/sidebar.php'); ?>

        <div class="col p-4">
            <h2>Edit Student Information</h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>First Name</label>
                    <input type="text" name="firstName" class="form-control" value="<?php echo htmlspecialchars($student['firstName']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Middle Name</label>
                    <input type="text" name="middleName" class="form-control" value="<?php echo htmlspecialchars($student['middleName']); ?>">
                </div>
                <div class="mb-3">
                    <label>Last Name</label>
                    <input type="text" name="lastName" class="form-control" value="<?php echo htmlspecialchars($student['lastName']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Course</label>
                    <input type="text" name="Course" class="form-control" value="<?php echo htmlspecialchars($student['Course']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Year Level</label>
                    <input type="text" name="grade_level" class="form-control" value="<?php echo htmlspecialchars($student['grade_level']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Profile Picture (optional)</label>
                    <input type="file" name="picture" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Good Moral (optional)</label>
                    <input type="file" name="goodMoral" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Grade Card (optional)</label>
                    <input type="file" name="gradeCard" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="studentinfo.php?studentNo=<?php echo urlencode($student['studentNo']); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
