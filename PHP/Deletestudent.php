<?php
include('Connection.php');

if (isset($_GET['studentNo'])) {
    $studentNo = $_GET['studentNo'];

    // First delete related records from studentsection table
    $deleteSectionQuery = "DELETE FROM studentsection WHERE studentNo = '$studentNo'";
    mysqli_query($conn, $deleteSectionQuery);

    // Then delete the student record
    $deleteQuery = "DELETE FROM student WHERE studentNo = '$studentNo'";

    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: ./../PAGES/registrar/students.php");
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
