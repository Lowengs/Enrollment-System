<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['studentNo'])) {
    die("Unauthorized access.");
}

$studentNo = $_SESSION['studentNo'];

// If coming from the selection form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['selected_section_name']) || empty($_POST['subjectids'])) {
        die("No section or subjects selected.");
    }

    $sectionName = $_POST['selected_section_name'];
    $subjectIds = $_POST['subjectids'];

    // Fetch subject details
    $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
    $types = str_repeat('i', count($subjectIds));

    $query = "SELECT Subjectcode, subject_name, credits FROM subjects WHERE subjectId IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$subjectIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    header("Location: ../PAGE/student/Enrollment.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Enrollment</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007BFF; color: #fff; }
        button { padding: 10px 20px; margin-right: 10px; }
    </style>
</head>
<body>

<h2>Confirm Your Enrollment</h2>

<p><strong>Section Name:</strong> <?php echo htmlspecialchars($sectionName); ?></p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Credits</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subjects as $index => $sub): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($sub['Subjectcode']); ?></td>
            <td><?php echo htmlspecialchars($sub['subject_name']); ?></td>
            <td><?php echo $sub['credits']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form method="POST" action="Enroll.php">
    <input type="hidden" name="selected_section_name" value="<?php echo htmlspecialchars($sectionName); ?>">
    <?php foreach ($subjectIds as $id): ?>
        <input type="hidden" name="subjectids[]" value="<?php echo $id; ?>">
    <?php endforeach; ?>
    <button type="submit">✅ Confirm Enrollment</button>
    <a href="../PAGE/student/Enrollment.php"><button type="button">❌ Cancel</button></a>
</form>

</body>
</html>
