<?php
include('Layout/Layout.php');
include('../../PHP/Connection.php');

session_start();

if (isset($_POST['submit'])) {
    $studentNumber = $_POST['student_number'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE studentId = ?");
    $stmt->bind_param("s", $studentNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        $status = $row['status'];

        // Check if student is approved
        if ($status !== 'Active') {
            $error = "Your registration is still pending approval from the registrar.";
        } else {
            if (password_verify($password, $storedPassword)) {
                $_SESSION['studentNo'] = $row['studentNo']; 
                header("Location: dashboard.php");
                exit();
            } 
            elseif ($password === $storedPassword) {
                $_SESSION['studentNo'] = $row['studentNo']; 
                $newHashed = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE student SET password = ? WHERE studentId = ?");
                $updateStmt->bind_param("ss", $newHashed, $studentNumber);
                $updateStmt->execute();

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        }
    } else {
        $error = "Student not found.";
    }
}
?>

<main>
    <a href="../../index.php" class="back-button">← Back</a>
    <div class="formcontainer">
        <div class="col1">
            <img src="../../CSS/pictures/student.png" alt="">
        </div>

        <div class="col2">
            <form action="" method="post">
                <h1>Student Module</h1>

                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

                <input type="text" name="student_number" placeholder="Student Number" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="submit" value="Log In"><br>
               
            </form>
        </div>
    </div>
</main>
