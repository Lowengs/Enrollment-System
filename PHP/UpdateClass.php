<?php
include('Connection.php');
session_start();

if (!isset($_SESSION['facultyID'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sectionid = $_POST['sectionid'];
    $facultyID = $_SESSION['facultyID'];
    $sectionName = $_POST['section_name'];
    $room = $_POST['room'];
    $days = implode(", ", $_POST['days']);
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Verify that this section belongs to the logged-in faculty
    $checkQuery = "SELECT sectionid FROM section WHERE sectionid = ? AND facultyID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $sectionid, $facultyID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update the section
        $updateQuery = "UPDATE section SET 
                        section_name = ?,
                        room = ?,
                        day = ?,
                        start_time = ?,
                        end_time = ?
                        WHERE sectionid = ? AND facultyID = ?";
        
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssii", $sectionName, $room, $days, $startTime, $endTime, $sectionid, $facultyID);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Class updated successfully!');
                window.location.href = '../PAGES/faculty/dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Error updating class: " . $stmt->error . "');
                window.location.href = '../PAGES/faculty/dashboard.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Unauthorized: You can only update your own classes.');
            window.location.href = '../PAGES/faculty/dashboard.php';
        </script>";
    }
} else {
    header('Location: ../PAGES/faculty/dashboard.php');
    exit();
}
?> 