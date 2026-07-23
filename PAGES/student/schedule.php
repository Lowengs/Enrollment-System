<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');

$studentNo = $_SESSION['studentNo'];
$query = "
    SELECT 
        ss.sectionid,
        s.section_name,
        s.room, 
        sub.Subjectcode, 
        sub.subject_name, 
        sub.credits, 
        s.day,                 
        s.start_time,          
        s.end_time,            
        CONCAT(f.FirstName, ' ', f.LastName) AS faculty_name
    FROM studentsection ss 
    INNER JOIN section s ON ss.sectionid = s.sectionid 
    INNER JOIN subjects sub ON s.subjectid = sub.subjectId 
    LEFT JOIN faculty f ON s.facultyID = f.facultyID
    WHERE ss.studentNo = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentNo);
$stmt->execute();
$result = $stmt->get_result();

$schedules = [];
while ($row = $result->fetch_assoc()) {
    $schedules[] = $row;
}
?>

<title>My Schedule</title>
<link rel="stylesheet" href="../../CSS/student/Schedule.css?v=<?= time(); ?>">

<div class="schedule-container">
    <h2>My Enrolled Subjects</h2>

    <?php if (count($schedules) > 0): ?>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Faculty</th>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Credits</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Section</th>
                    <th>Conflict</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                foreach ($schedules as $index => $row):
                    $conflict = false;
                    foreach ($schedules as $checkIndex => $check) {
                        if ($index === $checkIndex) continue;
                        if ($row['day'] === $check['day']) {
                            $startA = strtotime($row['start_time']);
                            $endA   = strtotime($row['end_time']);
                            $startB = strtotime($check['start_time']);
                            $endB   = strtotime($check['end_time']);
                            if (($startA < $endB) && ($startB < $endA)) {
                                $conflict = true;
                                break;
                            }
                        }
                    }
                ?>
                    <tr class="<?php echo $conflict ? 'conflict' : ''; ?>">
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row['faculty_name']; ?></td>
                        <td><?php echo $row['Subjectcode']; ?></td>
                        <td><?php echo $row['subject_name']; ?></td>
                        <td><?php echo $row['credits']; ?></td>
                        <td><?php echo $row['day']; ?></td>
                        <td><?php echo date("h:i A", strtotime($row['start_time'])) . " - " . date("h:i A", strtotime($row['end_time'])); ?></td>
                        <td><?php echo $row['room']; ?></td>
                        <td><?php echo $row['section_name']; ?></td>
                        <td><?php echo $conflict ? "Yes" : "No"; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-subjects">
            <p>No enrolled subjects found.</p>
        </div>
    <?php endif; ?>
</div>

