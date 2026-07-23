<?php
include('Layout/indexlayout.php');
include('../../PHP/Connection.php');
session_start();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM faculty WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // If password is hashed
        if (password_verify($password, $storedPassword)) {
            $_SESSION['facultyID'] = $row['facultyID'];
            header("Location: dashboard.php");
            exit();
        }
        // If password is still plain text
        elseif ($password === $storedPassword) {
            $_SESSION['facultyID'] = $row['facultyID'];

            // Hash the plain text password now
            $newHashed = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE faculty SET password = ? WHERE facultyID = ?");
            $updateStmt->bind_param("si", $newHashed, $row['facultyID']);
            $updateStmt->execute();

            header("Location: dashboard.php");
            exit();
        }
        // Invalid password
        else {
            $error = "Invalid password.";
        }
    }
    else {
        $error = "Faculty not found.";
    }
}
?>
<title>Faculty</title>
 
<main>
    <div class="formcontainer">
        <a href="../../index.php" class="back-button">← Back</a>
        <div class="col1">
            <img src="../../CSS/pictures/faculty.jpg" alt="">
        </div>

        <div class="col2">
            <form action="" method="post">
                <h1>Faculty Module</h1>

                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="submit" value="Log In"><br>
            </form>
        </div>
    </div>
</main>
