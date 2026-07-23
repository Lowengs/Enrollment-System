<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['studentNo'])) {
    die("Unauthorized. Please log in.");
}

$studentNo = $_SESSION['studentNo'];

if (!isset($_POST['selected_section_name']) || empty($_POST['subjectids'])) {
    die("No section selected or no subjects chosen.");
}

$sectionName = $_POST['selected_section_name'];
$subjectIds = $_POST['subjectids'];

$insertCount = 0;
$duplicates = [];

// Process each selected subject
foreach ($subjectIds as $subjectId) {
    // Get section ID based on subject ID and section name
    $findSection = "SELECT sectionid FROM section WHERE subjectid = ? AND section_name = ?";
    $findStmt = $conn->prepare($findSection);
    $findStmt->bind_param("is", $subjectId, $sectionName);
    $findStmt->execute();
    $findResult = $findStmt->get_result();

    if ($findResult->num_rows > 0) {
        while ($secRow = $findResult->fetch_assoc()) {
            $sectionid = $secRow['sectionid'];

            // Check if student already enrolled in this subject
            $checkEnroll = "
                SELECT ss.studentNo, s.section_name, sub.subject_name 
                FROM studentsection ss
                INNER JOIN section s ON ss.sectionid = s.sectionid
                INNER JOIN subjects sub ON s.subjectid = sub.subjectId
                WHERE ss.studentNo = ? AND s.subjectid = ?
            ";
            $checkStmt = $conn->prepare($checkEnroll);
            $checkStmt->bind_param("ii", $studentNo, $subjectId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // Not enrolled yet — insert
                $insertQuery = "INSERT INTO studentsection (studentNo, sectionid) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("ii", $studentNo, $sectionid);
                $insertStmt->execute();
                $insertCount++;
                // Build final alert message
                $message = $insertCount . " subject(s) successfully enrolled.";

                // After successful enrollment, decrement the slots for the section
                // $updateSlotsQuery = "UPDATE section SET slots = slots - 1 WHERE sectionid = ?";
                // $updateStmt = $conn->prepare($updateSlotsQuery);
                // $updateStmt->bind_param("i", $sectionid);
                // $updateStmt->execute();

            } else {
                // Already enrolled — collect info for alert
                $row = $checkResult->fetch_assoc();
                $duplicates[] = "Section: {$row['section_name']} (Subject: {$row['subject_name']})";
            }
        }
    }
}


if (!empty($duplicates)) {
    $message .= "\\nYou are already enrolled in:\\n- " . implode("\\n- ", $duplicates);
}

echo "<script>alert('$message'); window.location.href='../PAGES/student/schedule.php';</script>";
?>
