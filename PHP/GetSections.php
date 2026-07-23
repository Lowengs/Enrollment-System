<?php
include('Connection.php');

$program = isset($_POST['program']) ? $_POST['program'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';

if (empty($program) || empty($semester)) {
    echo "<option value=''>-- Select Section --</option>";
    exit;
}

$sql = "
    SELECT DISTINCT s.section_name 
    FROM section s 
    INNER JOIN subjects subj ON s.subjectid = subj.subjectId 
    WHERE subj.course = ? AND subj.semester = ?
    ORDER BY s.section_name ASC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<option value=''>Query Preparation Error: " . $conn->error . "</option>";
    exit;
}

$stmt->bind_param("si", $program, $semester);
$stmt->execute();
$result = $stmt->get_result();

echo "<option value=''>-- Select Section --</option>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['section_name']) . "'>" . htmlspecialchars($row['section_name']) . "</option>";
    }
} else {
    echo "<option value=''>No sections found</option>";
}
?>
