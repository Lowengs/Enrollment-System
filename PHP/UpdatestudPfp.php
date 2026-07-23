<?php
include('Connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId   = $_POST['studentId'];
    $firstName   = $_POST['firstName'];
    $middleName  = $_POST['middleName'];
    $lastName    = $_POST['lastName'];
    $email       = $_POST['Email'];
    $DOB         = $_POST['DOB'];
    $contactNo   = $_POST['contactNo'];
    $address     = $_POST['Address'];
    $nationality = $_POST['Nationality'];

    // Function for uploading files
    function uploadFile($inputName, $uploadDir, $oldFile = '') {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            $filename = time() . '_' . basename($_FILES[$inputName]['name']);
            $targetFilePath = $uploadDir . $filename;
            move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath);

            // Optionally remove old file if it exists
            if (!empty($oldFile) && file_exists($oldFile)) {
                unlink($oldFile);
            }
            return $targetFilePath;
        }
        return $oldFile;
    }

    // Fetch current file paths
    $query = "SELECT picture, goodMoral, gradeCard FROM student WHERE studentId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $stmt->bind_result($currentPic, $currentMoral, $currentCard);
    $stmt->fetch();
    $stmt->close();

    // Process file uploads
    $picturePath = uploadFile('profilePic', '../../uploads/profile/', $currentPic);
    $goodMoral   = uploadFile('goodMoral', '../../uploads/goodMoral/', $currentMoral);
    $gradeCard   = uploadFile('gradeCard', '../../uploads/gradeCard/', $currentCard);

    // Update query (removed Course, status, grade_level)
    $updateQuery = "UPDATE student SET 
        firstName=?, middleName=?, lastName=?, Email=?, 
        DOB=?, contactNo=?, Address=?, Nationality=?, 
        picture=?, goodMoral=?, gradeCard=?
        WHERE studentId=?";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssssssssi", $firstName, $middleName, $lastName, $email, 
        $DOB, $contactNo, $address, $nationality, 
        $picturePath, $goodMoral, $gradeCard, $studentId);

    if ($stmt->execute()) {
        echo "<script>alert('Student profile updated successfully.'); window.location.href='../PAGES/student/profile.php?studentId=$studentId';</script>";
    } else {
        echo "<script>alert('Failed to update student profile.'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid access.'); window.location.href='../PAGES/student/profile.php';</script>";
}
?>
