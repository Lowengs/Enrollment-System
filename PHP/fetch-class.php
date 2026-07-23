<?php
include('Connection.php');

$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$course = isset($_POST['course']) ? $_POST['course'] : '';

$query = "SELECT 
            s.subjectId,
            CONCAT(f.FirstName, ' ', f.LastName) AS FacultyName,
            s.Subjectcode,
            s.subject_name,
            s.grade_level,
            sec.section_name,
            CONCAT(sec.day, ' ', sec.start_time, ' - ', sec.end_time) AS TimeDate,
            s.credits,
            s.course,
            s.semester
          FROM subjects s
          INNER JOIN section sec ON s.subjectId = sec.subjectid
          INNER JOIN faculty f ON sec.facultyID = f.facultyID
          WHERE 1";


// Filter by semester if selected
if (!empty($semester)) {
  $query .= " AND s.semester = '$semester'";
}

// Filter by course if selected
if (!empty($course)) {
  $query .= " AND s.course = '$course'";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>
          <td>{$row['subjectId']}</td>
          <td>{$row['section_name']}</td>
          <td>{$row['FacultyName']}</td>
          <td>{$row['Subjectcode']}</td>
          <td>{$row['subject_name']}</td>
          <td>{$row['grade_level']}</td>
          <td>{$row['TimeDate']}</td>
          <td>{$row['credits']}</td>
          <td>{$row['semester']}</td>
        </tr>";
}

} else {
  echo "<tr><td colspan='8' style='text-align:center;'>No classes found.</td></tr>";
}
?>
