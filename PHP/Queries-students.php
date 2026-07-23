<?php
include ('Connection.php');
session_start();

if (!isset($_SESSION['studentNo'])) {
    header('Location: index.php');
    exit();
}

$studentNo = $_SESSION['studentNo'];

// Fetch student details using studentNo (primary key)
$stmt = $conn->prepare("SELECT * FROM student WHERE studentNo = ?");
$stmt->bind_param("i", $studentNo);
$stmt->execute();
$result = $stmt->get_result();

$row = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "⚠️ No student found for studentNo: $studentNo<br>";
}

// Optional: Picture path if you need it for a profile image
$picturePath = $row ? $row['picture'] : '';

// Expose $studentCourse and $studentGradeLevel for later use
$studentCourse       = $row ? $row['Course'] : '';
$studentGradeLevel   = $row ? $row['grade_level'] : '';

// Fetch subjects for this student's course and grade level
$subjects = $conn->query("SELECT * FROM subjects WHERE course='$studentCourse' AND grade_level='$studentGradeLevel'");

function getEnrolledCountBySectionName($conn, $sectionName) {
    $query = "SELECT 
                COUNT(studsec.studentNo) AS enrolled_students
              FROM section sec
              LEFT JOIN studentsection studsec ON sec.sectionid = studsec.sectionid
              WHERE sec.section_name = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $sectionName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['enrolled_students'];
}
?>
