<?php
include('Connection.php');

// Settings
$course = 'BSAIS';
$years = [1, 2, 3, 4];
$sections = [1, 2, 3];
$semesters = [1, 2];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$timeSlots = [
    ['08:00:00', '09:00:00'],
    ['09:00:00', '10:00:00'],
    ['10:00:00', '11:00:00'],
    ['11:00:00', '12:00:00'],
    ['13:00:00', '14:00:00'],
    ['14:00:00', '15:00:00'],
    ['15:00:00', '16:00:00'],
    ['16:00:00', '17:00:00'],
];
$rooms = ['Room 101', 'Room 102', 'Room 103', 'Room 104', 'Room 105'];

// Get all faculty IDs
$facultyResult = $conn->query("SELECT facultyID FROM faculty");
$facultyIDs = [];
while ($row = $facultyResult->fetch_assoc()) {
    $facultyIDs[] = $row['facultyID'];
}

if (empty($facultyIDs)) {
    die('No faculty found.');
}

$inserted = 0;

foreach ($years as $year) {
    foreach ($semesters as $semester) {
        // Get all subjects for this year and semester
        $subjectResult = $conn->query("SELECT subjectId FROM subjects WHERE grade_level = $year AND semester = $semester AND course = '$course'");
        $subjectIDs = [];
        while ($row = $subjectResult->fetch_assoc()) {
            $subjectIDs[] = $row['subjectId'];
        }
        foreach ($sections as $sectionNum) {
            $sectionName = "$course $year-$sectionNum";
            $slotIndex = 0;
            $dayIndex = 0;
            foreach ($subjectIDs as $subjectId) {
                $facultyID = $facultyIDs[array_rand($facultyIDs)];
                // Assign unique day and time slot for each subject in this section
                $day = $days[$dayIndex % count($days)];
                $slot = $timeSlots[$slotIndex % count($timeSlots)];
                $room = $rooms[array_rand($rooms)];
                $startTime = $slot[0];
                $endTime = $slot[1];
                // Check if already exists
                $check = $conn->prepare("SELECT sectionid FROM section WHERE section_name = ? AND subjectid = ?");
                $check->bind_param("si", $sectionName, $subjectId);
                $check->execute();
                $check->store_result();
                if ($check->num_rows == 0) {
                    $stmt = $conn->prepare("INSERT INTO section (section_name, room, facultyID, subjectid, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssiisss", $sectionName, $room, $facultyID, $subjectId, $day, $startTime, $endTime);
                    if ($stmt->execute()) {
                        $inserted++;
                    }
                }
                $slotIndex++;
                if ($slotIndex % count($timeSlots) == 0) {
                    $dayIndex++;
                }
            }
        }
    }
}
echo "<h2>Done! $inserted section records inserted.</h2>";
?> 