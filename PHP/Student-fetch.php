<?php
include('Connection.php');
session_start();

$facultyID = $_SESSION['facultyID'];  // Make sure faculty is logged in and this is set

$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$program = isset($_POST['program']) ? $_POST['program'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';

$query = "
  SELECT DISTINCT s.studentNo, s.lastName, s.firstName, s.middleName, s.Course, s.grade_level, s.Email
  FROM student s
  INNER JOIN studentsection ss ON s.studentNo = ss.studentNo
  INNER JOIN section sec ON ss.sectionid = sec.sectionid
  INNER JOIN subjects subj ON sec.subjectid = subj.subjectId
  WHERE sec.facultyID = ?
";

$params = [$facultyID];
$types = "i"; // 'i' for integer (facultyID)

if (!empty($semester)) {
  $query .= " AND subj.semester = ?";
  $params[] = $semester;
  $types .= "i";
}

if (!empty($program)) {
  $query .= " AND s.Course = ?";
  $params[] = $program;
  $types .= "s";
}

if (!empty($year)) {
  $query .= " AND s.grade_level = ?";
  $params[] = $year;
  $types .= "i";
}

// Prepare & execute securely
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
  echo "<table border='1' cellpadding='10' cellspacing='0'>";
  echo "<tr>
          <th>Student No</th>
          <th>Full Name</th>
          <th>Program</th>
          <th>Year Level</th>
          <th>Email</th>
        </tr>";

  while ($row = mysqli_fetch_assoc($result)) {
    $fullName = $row['lastName'] . ', ' . $row['firstName'] . ' ' . $row['middleName'];
    echo "<tr>
            <td>{$row['studentNo']}</td>
            <td>{$fullName}</td>
            <td>{$row['Course']}</td>
            <td>{$row['grade_level']}</td>
            <td>{$row['Email']}</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "<p>No students found for the selected filters.</p>";
}
?>
