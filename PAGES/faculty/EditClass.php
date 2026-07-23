<?php
include('../../PHP/Queries-faculty.php');

if (!isset($_GET['sectionid'])) {
    header('Location: dashboard.php');
    exit();
}

$sectionid = $_GET['sectionid'];
$facultyID = $_SESSION['facultyID'];

// Get class details
$query = "SELECT 
            s.sectionid,
            s.section_name,
            s.subjectid,
            s.room,
            s.day,
            s.start_time,
            s.end_time,
            sub.subject_name,
            sub.Subjectcode
          FROM section s
          INNER JOIN subjects sub ON s.subjectid = sub.subjectId
          WHERE s.sectionid = ? AND s.facultyID = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $sectionid, $facultyID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

$class = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Class</title>
    <link rel="stylesheet" href="../../CSS/faculty/Addsection.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <?php include('Layout/Sidebar.php'); ?>
        </aside>

        <main class="main-content">
            <h2>Edit Class</h2>

            <form method="post" action="../../PHP/UpdateClass.php" class="form-card">
                <input type="hidden" name="sectionid" value="<?php echo htmlspecialchars($class['sectionid']); ?>">
                
                <label for="section_name">Section Name</label>
                <input type="text" name="section_name" value="<?php echo htmlspecialchars($class['section_name']); ?>" required>

                <label for="subject">Subject</label>
                <input type="text" value="<?php echo htmlspecialchars($class['Subjectcode'] . ' - ' . $class['subject_name']); ?>" readonly>

                <label for="room">Room</label>
                <input type="text" name="room" value="<?php echo htmlspecialchars($class['room']); ?>" required>

                <label>Day</label>
                <div class="checkbox-group">
                    <?php
                    $days = explode(", ", $class['day']);
                    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    foreach ($allDays as $day) {
                        $checked = in_array($day, $days) ? 'checked' : '';
                        echo "<label><input type='checkbox' name='days[]' value='$day' $checked> " . substr($day, 0, 3) . "</label>";
                    }
                    ?>
                </div>

                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" value="<?php echo date('H:i', strtotime($class['start_time'])); ?>" required>

                <label for="end_time">End Time</label>
                <input type="time" name="end_time" value="<?php echo date('H:i', strtotime($class['end_time'])); ?>" required>

                <div class="button-group">
                    <button type="submit" class="btn-primary">Update Class</button>
                    <a href="dashboard.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>

    <style>
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-primary, .btn-secondary {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</body>
</html> 