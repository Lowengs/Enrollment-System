<?php
include ('Connection.php');

// Get form values
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$nationality = $_POST['nationality'];
$course = $_POST['course'];
$year_level = $_POST['year_level'];

// Handle uploaded images
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/enrollment/CSS/Pictures/uploads/";

$pictureName = isset($_FILES["picture"]) ? basename($_FILES["picture"]["name"]) : "";
$moralName = isset($_FILES["moral"]) ? basename($_FILES["moral"]["name"]) : "";
$cardName = isset($_FILES["card"]) ? basename($_FILES["card"]["name"]) : "";

$picturePath = $target_dir . $pictureName;
$moralPath = $target_dir . $moralName;
$cardPath = $target_dir . $cardName;

// Function to check if file type is valid (jpg or png)
function isValidImage($fileName) {
    $allowedExtensions = ['jpg', 'jpeg', 'png']; // Allowed file types
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    return in_array($fileExtension, $allowedExtensions);
}

// Validate and move uploaded files
if (!empty($pictureName) && isValidImage($pictureName)) {
    move_uploaded_file($_FILES["picture"]["tmp_name"], $picturePath);
} else {
    echo "Invalid picture file. Only JPG and PNG files are allowed.";
    exit();
}

if (!empty($moralName) && isValidImage($moralName)) {
    move_uploaded_file($_FILES["moral"]["tmp_name"], $moralPath);
} else {
    echo "Invalid good moral file. Only JPG and PNG files are allowed.";
    exit();
}

if (!empty($cardName) && isValidImage($cardName)) {
    move_uploaded_file($_FILES["card"]["tmp_name"], $cardPath);
} else {
    echo "Invalid grade card file. Only JPG and PNG files are allowed.";
    exit();
}

// Generate next unique studentId starting from 1230001
$getLastIdQuery = "SELECT MAX(CAST(SUBSTRING(studentId, 4) AS UNSIGNED)) as lastId 
                   FROM student 
                   WHERE studentId LIKE '123%'";
$lastIdResult = mysqli_query($conn, $getLastIdQuery);
$lastIdRow = mysqli_fetch_assoc($lastIdResult);
$lastId = $lastIdRow['lastId'] ?? 0;

$newStudentIdNumber = $lastId ? (1232400 + $lastId + 1) : 1230001;

// Format password using DOB
$password = date('mdY', strtotime($dob));

// Insert into database
$sql = "INSERT INTO student (lastname, firstname, middlename, email, contactNo, address, dob, nationality, course, picture, goodMoral, gradeCard, status, studentId, password, grade_level)
        VALUES ('$lastname', '$firstname', '$middlename', '$email', '$contact', '$address', '$dob', '$nationality', '$course', '$picturePath', '$moralPath', '$cardPath', 'Pending', '$newStudentIdNumber', '$password', '$year_level')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Registration successful! Your Student Number is: " . $newStudentIdNumber . "\\nYour temporary password is your date of birth (MMDDYYYY)\\nPlease wait for the registrar to approve your enrollment.');
        window.location.href = '../index.php';
    </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
