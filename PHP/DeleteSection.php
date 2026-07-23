<?php
include('Connection.php');
session_start();

// Check if user is logged in as registrar
if (!isset($_SESSION['registrarId'])) {
    echo "<script>
        alert('Please log in as registrar to delete sections.');
        window.location.href = '../index.php';
    </script>";
    exit();
}

if (isset($_GET['section_name'])) {
    $section_name = $_GET['section_name'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // First check if there are any enrolled students in any section with this name
        $checkEnrollments = "SELECT COUNT(*) as enrolled_count 
                           FROM studentsection ss 
                           INNER JOIN section s ON ss.sectionid = s.sectionid 
                           WHERE s.section_name = ?";
        $stmt = $conn->prepare($checkEnrollments);
        $stmt->bind_param("s", $section_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['enrolled_count'] > 0) {
            // If there are enrolled students, we should not delete the sections
            $conn->rollback();
            echo "<script>
                alert('Cannot delete section: There are enrolled students in this section.');
                window.location.href = '../PAGES/registrar/courses.php';
            </script>";
            exit();
        }

        // Get section details for logging
        $getSectionDetails = "SELECT DISTINCT s.section_name, sub.Subjectcode, sub.subject_name 
                            FROM section s 
                            INNER JOIN subjects sub ON s.subjectid = sub.subjectId 
                            WHERE s.section_name = ?";
        $stmt = $conn->prepare($getSectionDetails);
        $stmt->bind_param("s", $section_name);
        $stmt->execute();
        $sectionDetails = $stmt->get_result()->fetch_assoc();

        // Delete all sections with this name
        $deleteSection = "DELETE FROM section WHERE section_name = ?";
        $stmt = $conn->prepare($deleteSection);
        $stmt->bind_param("s", $section_name);
        
        if ($stmt->execute()) {
            // If everything is successful, commit the transaction
            $conn->commit();
            
            $message = "Section deleted successfully!";
            if ($sectionDetails) {
                $message .= "\\nSection: " . $sectionDetails['section_name'] . 
                           "\\nSubject: " . $sectionDetails['Subjectcode'] . " - " . $sectionDetails['subject_name'];
            }
            
            echo "<script>
                alert('$message');
                window.location.href = '../PAGES/registrar/courses.php';
            </script>";
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        $conn->rollback();
        echo "<script>
            alert('Error deleting section: " . $e->getMessage() . "');
            window.location.href = '../PAGES/registrar/courses.php';
        </script>";
    }
} else {
    echo "<script>
        alert('No section selected for deletion.');
        window.location.href = '../PAGES/registrar/courses.php';
    </script>";
}

// Close the connection
$conn->close();
?> 