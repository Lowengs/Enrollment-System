<?php

include('Connection.php');

// Fetch all students
function getAllStudents($conn) {
    $sql = "SELECT * FROM student";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Count total students
function countTotalStudents($conn) {
    $sql = "SELECT COUNT(*) AS total FROM student";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Count active students
function countActiveStudents($conn) {
    $sql = "SELECT COUNT(*) AS active FROM student WHERE status = 'Active'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['active'];
}

// Count new enrollments (pending)
function countNewEnrollments($conn) {
    $sql = "SELECT COUNT(*) AS new_enrollments FROM student WHERE status = 'Pending'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['new_enrollments'];
}

// Count enrolled students (via studentsection)
function countEnrolledStudents($conn) {
    $sql = "SELECT COUNT(DISTINCT studentNo) AS enrolled FROM studentsection";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['enrolled'];
}

// Count pending enrollments
function countPendingEnrollments($conn) {
    $sql = "SELECT COUNT(*) AS new_enrollments FROM student WHERE status = 'Pending'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['new_enrollments'];
}

// Count total courses
function countTotalCourses($conn) {
    $sql = "SELECT COUNT(*) AS total FROM subjects";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Get student statuses
function getStudentStatuses($conn) {
    $sql = "SELECT 
                s.studentId,
                CONCAT(s.firstName, ' ', s.lastName) as fullName,
                s.course as year,
                s.status,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 
                        FROM studentsection ss 
                        WHERE ss.studentNo = s.studentNo
                    ) THEN 'Enrolled'
                    WHEN s.status = 'Active' THEN 'Active'
                    WHEN s.status = 'Pending' THEN 'Pending'
                    ELSE 'Not Enrolled'
                END as enrollment_status
            FROM student s
            ORDER BY s.studentId ASC";
            
    $result = mysqli_query($conn, $sql);
    return $result;
}

// =======================
// FACULTY MANAGEMENT
// =======================

// Fetch all faculty
function getAllFaculty($conn) {
    $sql = "SELECT * FROM faculty";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Count total faculty
function countTotalFaculty($conn) {
    $sql = "SELECT COUNT(*) AS total FROM faculty";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}


function getAllSectionsWithEnrolledCount($conn) {
    $query = "SELECT 
                GROUP_CONCAT(DISTINCT sec.sectionid) as sectionids,
                sec.section_name,
                GROUP_CONCAT(DISTINCT sec.room) as rooms,
                GROUP_CONCAT(DISTINCT CONCAT(f.FirstName, ' ', f.LastName)) AS FacultyNames,
                GROUP_CONCAT(DISTINCT CONCAT(sec.day, ' ', TIME_FORMAT(sec.start_time, '%h:%i %p'), ' - ', TIME_FORMAT(sec.end_time, '%h:%i %p'))) as schedules,
                (
                    SELECT COUNT(DISTINCT ss.studentNo)
                    FROM studentsection ss
                    INNER JOIN section s ON ss.sectionid = s.sectionid
                    WHERE s.section_name = sec.section_name
                ) as enrolled_students
              FROM section sec
              INNER JOIN faculty f ON sec.facultyID = f.facultyID
              GROUP BY sec.section_name
              ORDER BY sec.section_name ASC";

    return mysqli_query($conn, $query);
}



?>
