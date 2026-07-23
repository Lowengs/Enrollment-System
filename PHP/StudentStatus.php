<?php
include('Connection.php');

if (isset($_POST['update_status'])) {
    $student_id = $_POST['student_id'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE students SET status='$new_status' WHERE student_id='$student_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: student-management.php");
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>
