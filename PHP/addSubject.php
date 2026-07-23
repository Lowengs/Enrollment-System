<?php
include ('Connection.php');

$Course = $_POST['course'];
$subjectName = $_POST['subject-name'];
$abbr = $_POST['abbreviation'];
$credit = $_POST['credits'];
$gradeLevel = $_POST['grade'];
$sem = $_POST['semester'];

$sql = "INSERT INTO subjects (course, subject_name, Subjectcode, credits, grade_level, semester)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiii", $Course, $subjectName, $abbr, $credit, $gradeLevel, $sem);

if ($stmt->execute()) {
    echo "<script>
        alert('Added Subject!');
        window.location.href = '../PAGES/faculty/addsubjects.php';
    </script>";
    exit;
}
else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

?>