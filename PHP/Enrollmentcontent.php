<?php
include('Connection.php');
session_start();

// Debug logging
error_log("POST data received: " . print_r($_POST, true));

if (isset($_POST['section_name'], $_POST['semester'], $_POST['student_grade_level'], $_POST['student_course'])) {
    $sectionName = $_POST['section_name'];
    $semester = $_POST['semester'];
    $studentGradeLevel = $_POST['student_grade_level'];
    $studentCourse = $_POST['student_course'];
    $studentNo = $_SESSION['studentNo'];

    // Debug logging
    error_log("Section Name: " . $sectionName);
    error_log("Semester: " . $semester);
    error_log("Student Grade Level: " . $studentGradeLevel);
    error_log("Student Course: " . $studentCourse);
    error_log("Student No: " . $studentNo);

    // First check if the student has any enrolled subjects in this section
    $checkEnrolledQuery = "SELECT COUNT(*) as enrolled_count 
                          FROM studentsection ss 
                          INNER JOIN section s ON ss.sectionid = s.sectionid 
                          WHERE ss.studentNo = ? AND s.section_name = ?";
    $checkStmt = $conn->prepare($checkEnrolledQuery);
    $checkStmt->bind_param("is", $studentNo, $sectionName);
    $checkStmt->execute();
    $enrolledResult = $checkStmt->get_result();
    $enrolledData = $enrolledResult->fetch_assoc();

    error_log("Enrolled Count in Section: " . $enrolledData['enrolled_count']);

    // If student has no enrolled subjects in this section, show all subjects for their year level
    // Otherwise, show only remaining subjects from their year level
    if ($enrolledData['enrolled_count'] == 0) {
        $query = "SELECT DISTINCT s.* FROM subjects s 
                  INNER JOIN section sec ON s.subjectId = sec.subjectid
                  WHERE s.semester = ? 
                  AND s.course = ?
                  AND s.grade_level = ?
                  AND sec.section_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isis", $semester, $studentCourse, $studentGradeLevel, $sectionName);
    } else {
        $query = "SELECT DISTINCT s.* FROM subjects s 
                  INNER JOIN section sec ON s.subjectId = sec.subjectid
                  WHERE s.semester = ? 
                  AND s.course = ?
                  AND s.grade_level = ?
                  AND sec.section_name = ?
                  AND s.subjectId NOT IN (
                      SELECT sub.subjectId 
                      FROM studentsection ss 
                      INNER JOIN section sec ON ss.sectionid = sec.sectionid 
                      INNER JOIN subjects sub ON sec.subjectid = sub.subjectId 
                      WHERE ss.studentNo = ?
                  )";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isisi", $semester, $studentCourse, $studentGradeLevel, $sectionName, $studentNo);
    }

    if (!$stmt) {
        die("Database error. Please try again later.");
    }
    
    $stmt->execute();
    $subjects = $stmt->get_result();

    error_log("Number of subjects found: " . $subjects->num_rows);

    // Fetch all professors
    $facultyQuery = "SELECT * FROM faculty";
    $facultyResult = $conn->query($facultyQuery);
    $professors = [];
    while ($row = $facultyResult->fetch_assoc()) {
        $professors[] = $row['FirstName'] . ' ' . $row['MiddleName'] . ' ' . $row['LastName'];
    }

    // Example time slots
    $timeSlots = [
        "8:00-9:00AM", "9:00-10:00AM", "10:00-11:00AM", "11:00-12:00PM",
        "1:00-2:00PM", "2:00-3:00PM", "3:00-4:00PM", "4:00-5:00PM"
    ];

    $totalUnits = 0;

    $studentQuery = "SELECT grade_level FROM student WHERE studentNo = ?";
    $stmtStudent = $conn->prepare($studentQuery);
    $stmtStudent->bind_param("i", $studentNo);
    $stmtStudent->execute();
    $resultStudent = $stmtStudent->get_result();
    $student = $resultStudent->fetch_assoc();
    if ($studentGradeLevel != $student['grade_level']) {
        die('<div class="enrollment-warning">You are not allowed to enroll in another year level.</div>');
    }

    if ($subjects->num_rows > 0) {
        echo "<form method='POST' action='../../PHP/Enroll.php'>
              <input type='hidden' name='selected_section_name' value='" . htmlspecialchars($sectionName) . "'>

              <div class='schedule-container'>
              <h2>Subjects for Section: " . htmlspecialchars($sectionName) . " (Semester " . htmlspecialchars($semester) . ")</h2>

              <table class='schedule-table'>
                  <thead>
                      <tr>
                          <th>Select</th>
                          <th>#</th>
                          <th>Subject Code</th>
                          <th>Description</th>
                          <th>Faculty</th>
                          <th>Time</th>
                          <th>Credits</th>
                      </tr>
                  </thead>
                  <tbody>";

        $count = 1;
        while ($sub = $subjects->fetch_assoc()) {
            $totalUnits += $sub['credits'];
            $subjectId = $sub['subjectId'];
            $professor = $professors[array_rand($professors)];
            $time = $timeSlots[array_rand($timeSlots)];

            echo "<tr>
                      <td><input type='checkbox' name='subjectids[]' value='{$subjectId}' checked></td>
                      <td>{$count}</td>
                      <td>" . htmlspecialchars($sub['Subjectcode']) . "</td>
                      <td>" . htmlspecialchars($sub['subject_name']) . "</td>
                      <td>" . htmlspecialchars($professor) . "</td>
                      <td>" . htmlspecialchars($time) . "</td>
                      <td>" . htmlspecialchars($sub['credits']) . "</td>
                  </tr>";
            $count++;
        }

        echo "</tbody>
              <tfoot>
                  <tr>
                      <td colspan='6' style='text-align:right; font-weight:bold;'>TOTAL UNITS:</td>
                      <td>{$totalUnits}</td>
                  </tr>
              </tfoot>
              </table>

              <button type='submit'>Confirm Enrollment</button>
              </div>
              </form>";
    } else {
        echo "<div class='no-subjects'>
                <p>No subjects available for this section and semester.</p>
              </div>";
    }
} else {
    echo "<p style='color:red;'>Please select all required fields.</p>";
}
?>
