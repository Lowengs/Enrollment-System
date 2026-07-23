<?php
include('Connection.php');
session_start();

if (!isset($_SESSION['facultyID'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['sectionid'])) {
    $sectionid = $_GET['sectionid'];
    $facultyID = $_SESSION['facultyID'];

    // First verify that this section belongs to the logged-in faculty
    $checkQuery = "SELECT sectionid FROM section WHERE sectionid = ? AND facultyID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $sectionid, $facultyID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // First delete related records from studentsection table
        $deleteEnrollments = "DELETE FROM studentsection WHERE sectionid = ?";
        $stmt = $conn->prepare($deleteEnrollments);
        $stmt->bind_param("i", $sectionid);
        $stmt->execute();

        // Then delete the section
        $deleteSection = "DELETE FROM section WHERE sectionid = ?";
        $stmt = $conn->prepare($deleteSection);
        $stmt->bind_param("i", $sectionid);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Class deleted successfully!');
                window.location.href = '../PAGES/faculty/dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Error deleting class: " . $stmt->error . "');
                window.location.href = '../PAGES/faculty/dashboard.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Unauthorized: You can only delete your own classes.');
            window.location.href = '../PAGES/faculty/dashboard.php';
        </script>";
    }
} else {
    echo "<script>
        alert('No class selected for deletion.');
        window.location.href = '../PAGES/faculty/dashboard.php';
    </script>";
}
?> 