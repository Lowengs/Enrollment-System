<?php

include ('Connection.php');

session_start();
if (!isset($_SESSION['facultyID'])) {
  header('Location: index.php');
  exit();
}

$sql = "SELECT * FROM student";
$result = $conn->query($sql);

// Count total classes for logged-in faculty
function countFacultyClasses($conn) {
    $facultyID = $_SESSION['facultyID'];
    $sql = "SELECT COUNT(DISTINCT sectionid) as total FROM section WHERE facultyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facultyID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Count total subjects for logged-in faculty
function countFacultySubjects($conn) {
    $facultyID = $_SESSION['facultyID'];
    $sql = "SELECT COUNT(DISTINCT s.subjectid) as total 
            FROM section s 
            WHERE s.facultyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facultyID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Count total students for logged-in faculty
function countFacultyStudents($conn) {
    $facultyID = $_SESSION['facultyID'];
    $sql = "SELECT COUNT(DISTINCT ss.studentNo) as total 
            FROM studentsection ss 
            INNER JOIN section s ON ss.sectionid = s.sectionid 
            WHERE s.facultyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facultyID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Get logged-in faculty name
function getFacultyName($conn) {
    $facultyID = $_SESSION['facultyID'];
    $sql = "SELECT CONCAT(FirstName, ' ', LastName) as fullname FROM faculty WHERE facultyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facultyID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['fullname'];
}

// Get faculty's classes with details
function getFacultyClasses($conn) {
    $facultyID = $_SESSION['facultyID'];
    $sql = "SELECT 
                s.sectionid,
                s.section_name,
                sub.subject_name,
                sub.Subjectcode,
                s.day,
                TIME_FORMAT(s.start_time, '%h:%i %p') as start_time,
                TIME_FORMAT(s.end_time, '%h:%i %p') as end_time,
                s.room,
                COUNT(ss.studentNo) as enrolled_students
            FROM section s
            INNER JOIN subjects sub ON s.subjectid = sub.subjectId
            LEFT JOIN studentsection ss ON s.sectionid = ss.sectionid
            WHERE s.facultyID = ?
            GROUP BY s.sectionid, s.section_name, sub.subject_name, sub.Subjectcode, s.day, s.start_time, s.end_time, s.room
            ORDER BY s.day, s.start_time";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facultyID);
    $stmt->execute();
    return $stmt->get_result();
}
?>