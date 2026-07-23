<?php
include('../../PHP/Connection.php');
include('Layout/indexLayout.php');
session_start();

if (isset($_POST['submit'])) {
    $Registrar = $_POST['Username'];
    $password = $_POST['Password'];

    $stmt = $conn->prepare("SELECT * FROM registrar WHERE Username = ?");
    $stmt->bind_param("s", $Registrar);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // If password isn't hashed, hash it and update the database
        if (strpos($storedPassword, '$2y$') !== 0) {
            $hashedPassword = password_hash($storedPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE registrar SET password = ? WHERE registrarId = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $row['registrarId']);
            $updateStmt->execute();
            $storedPassword = $hashedPassword;
        }

        // Check hashed password
        if (password_verify($password, $storedPassword)) {
            $_SESSION['registrarId'] = $row['registrarId'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Registrar not found.";
    }
}
?>
<title>Registrar</title>
    <main>
        <a href="../../index.php" class="back-button">← Back</a>
        <div class="formcontainer">
            <div class="col1">
                <img src="../../CSS/pictures/registrar.jpg" alt="">
            </div>
            <div class="col2">
                <form action="" method="POST">
                    <h1>Registrar Module</h1>
                    <input type="text" name="Username" placeholder="Username" required> <br>
                    <input type="password" name="Password" placeholder="Password" required> <br>
                    <input type="submit" name="submit" value="Log In"> <br>
                </form>
            </div>
        </div>
    </main>
