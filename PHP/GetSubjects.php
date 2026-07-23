<?php
include('Connection.php');

$section = isset($_POST['section']) ? $_POST['section'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';

if (empty($section) || empty($semester)) {
    echo "<div class='empty-state'>
            <i class='fas fa-calendar-alt'></i>
            <p>Select a section to view subjects and schedules</p>
          </div>";
    exit;
}

$sql = "
    SELECT 
        subj.subjectId,
        subj.Subjectcode,
        subj.subject_name, 
        subj.credits,
        s.day, 
        TIME_FORMAT(s.start_time, '%h:%i %p') AS start_time, 
        TIME_FORMAT(s.end_time, '%h:%i %p') AS end_time,
        s.room,
        CONCAT(f.FirstName, ' ', f.LastName) AS faculty_name,
        s.sectionid,
        (SELECT COUNT(*) FROM studentsection WHERE sectionid = s.sectionid) as enrolled_count,
        40 as max_capacity
    FROM section s 
    INNER JOIN subjects subj ON s.subjectid = subj.subjectId 
    INNER JOIN faculty f ON s.facultyID = f.facultyID
    WHERE s.section_name = ? AND subj.semester = ?
    ORDER BY FIELD(s.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), s.start_time
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<div class='error-message'>Error loading schedule: " . $conn->error . "</div>";
    exit;
}

$stmt->bind_param("si", $section, $semester);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='schedule-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Faculty</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Credits</th>
                    <th>Available Slots</th>
                </tr>
            </thead>
            <tbody>";
    $count = 1;
    $totalUnits = 0;
    while ($row = $result->fetch_assoc()) {
        $availableSlots = $row['max_capacity'] - $row['enrolled_count'];
        $slotClass = $availableSlots <= 5 ? 'low-slots' : ($availableSlots <= 10 ? 'medium-slots' : 'high-slots');
        
        echo "<tr>
                <td>{$count}</td>
                <td>" . htmlspecialchars($row['Subjectcode']) . "</td>
                <td>" . htmlspecialchars($row['subject_name']) . "</td>
                <td>" . htmlspecialchars($row['faculty_name']) . "</td>
                <td>" . htmlspecialchars($row['day']) . "</td>
                <td>{$row['start_time']} - {$row['end_time']}</td>
                <td>" . htmlspecialchars($row['room']) . "</td>
                <td>" . htmlspecialchars($row['credits']) . "</td>
                <td class='{$slotClass}'>" . $availableSlots . " slots</td>
              </tr>";
        $count++;
        $totalUnits += $row['credits'];
    }
    echo "</tbody>
          <tfoot>
            <tr>
                <td colspan='8' style='text-align: right;'><strong>Total Units:</strong></td>
                <td><strong>{$totalUnits}</strong></td>
            </tr>
          </tfoot>
        </table>";

    // Add legend for slot availability
    echo "<div class='slot-legend'>
            <span class='legend-item'><span class='legend-color high-slots'></span> Many slots available</span>
            <span class='legend-item'><span class='legend-color medium-slots'></span> Limited slots</span>
            <span class='legend-item'><span class='legend-color low-slots'></span> Few slots left</span>
          </div>";

    // Add CSS for slot indicators
    echo "<style>
            .high-slots { color: #2ecc71; }
            .medium-slots { color: #f39c12; }
            .low-slots { color: #e74c3c; }
            
            .slot-legend {
                margin-top: 15px;
                display: flex;
                gap: 20px;
                justify-content: flex-end;
            }
            
            .legend-item {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: 0.9rem;
            }
            
            .legend-color {
                width: 12px;
                height: 12px;
                border-radius: 2px;
                display: inline-block;
            }
            
            .legend-color.high-slots { background-color: #2ecc71; }
            .legend-color.medium-slots { background-color: #f39c12; }
            .legend-color.low-slots { background-color: #e74c3c; }
          </style>";
} else {
    echo "<div class='empty-state'>
            <i class='fas fa-calendar-alt'></i>
            <p>No subjects and schedules found for this section.</p>
          </div>";
}
?>
