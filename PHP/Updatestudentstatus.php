<?php
include ('Connection.php');

// Check if studentNo is passed
if (!isset($_GET['studentNo'])) {
    echo "No student number provided.";
    exit;
}

$studentNo = $_GET['studentNo'];

// Fetch student data
$query = "SELECT * FROM student WHERE studentNo = '$studentNo'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
} else {
    echo "Student not found.";
    exit;
}

// Check if student is already active
if ($student['status'] == 'Active') {
    echo "<script>
        alert('This student is already active!');
        window.location.href = '../PAGES/registrar/students.php';
    </script>";
    exit;
}

// Update query - only update status
$updateQuery = "UPDATE student SET status='Active' WHERE studentNo='$studentNo'";

if (mysqli_query($conn, $updateQuery)) {
    echo "<script>
        alert('Student has been approved and can now enroll in courses.');
        window.location.href = '../PAGES/registrar/students.php';
    </script>";
    exit;
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
