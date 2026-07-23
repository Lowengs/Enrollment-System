<?php
include ('Layout/Layout.php');
include ('../../PHP/Queries-faculty.php');

if (isset($_POST['submit'])) {
    $sectionName = $_POST['section_name'];
    $subjectId = $_POST['subjectid'];
    $room = $_POST['room'];
    $facultyId = $_POST['facultyid'];
    $days = implode(", ", $_POST['days']);  // combine selected days
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO section (section_name, room, facultyID, subjectid, day, start_time, end_time)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiisss", $sectionName, $room, $facultyId, $subjectId, $days, $startTime, $endTime);

    if ($stmt->execute()) {
        echo "<script>alert('Section added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// Fetch subjects for dropdown
$subjects = $conn->query("SELECT subjectId, subject_name FROM subjects");

// Fetch faculty for dropdown
$faculties = $conn->query("SELECT facultyID, CONCAT(FirstName, ' ', LastName) as fullname FROM faculty");
?>

<link rel="stylesheet" href="../../CSS/faculty/Addsection.css?v=<?= time(); ?>">

<body>
<div class="container">
  <aside class="sidebar">
    <?php include('Layout/Sidebar.php'); ?>
  </aside>

  <main class="main-content">
    <h2>Add New Section</h2>

    <?php if (!empty($message)) : ?>
      <div class="alert <?= $alertType === 'success' ? 'alert-success' : 'alert-error' ?>" style="display: block; margin-bottom: 20px; padding: 10px; border-radius: 4px;">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form-card">

      <label for="course">Course</label>
      <select name="course" id="course" required onchange="updateSections()">
        <option value="">Select Course</option>
        <option value="BSAIT">BSAIT</option>
        <option value="BSAIS">BSAIS</option>
      </select>

      <label for="section_name">Section Name</label>
      <select name="section_name" id="section_name" required>
        <option value="">Select Section</option>
      </select>

      <label for="subjectid">Subject</label>
      <select name="subjectid" required>
        <option value="">-- Select Subject --</option>
        <?php while ($subject = $subjects->fetch_assoc()): ?>
          <option value="<?= $subject['subjectId'] ?>"><?= $subject['subject_name'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="room">Room</label>
      <input type="text" name="room" required>

      <label for="facultyid">Faculty</label>
      <select name="facultyid" required>
        <option value="">-- Select Faculty --</option>
        <?php while ($faculty = $faculties->fetch_assoc()): ?>
          <option value="<?= $faculty['facultyID'] ?>"><?= $faculty['fullname'] ?></option>
        <?php endwhile; ?>
      </select>

      <label>Day</label>
      <div class="checkbox-group">
        <label><input type="checkbox" name="days[]" value="Monday"> Mon</label>
        <label><input type="checkbox" name="days[]" value="Tuesday"> Tue</label>
        <label><input type="checkbox" name="days[]" value="Wednesday"> Wed</label>
        <label><input type="checkbox" name="days[]" value="Thursday"> Thu</label>
        <label><input type="checkbox" name="days[]" value="Friday"> Fri</label>
      </div>

      <label for="start_time">Start Time</label>
      <input type="time" name="start_time" required>

      <label for="end_time">End Time</label>
      <input type="time" name="end_time" required>

      <input type="submit" name="submit" value="Add Section">

    </form>
  </main>
</div>

<script>
function updateSections() {
    const course = document.getElementById('course').value;
    const sectionSelect = document.getElementById('section_name');
    sectionSelect.innerHTML = '<option value="">Select Section</option>';

    if (course) {
        for (let year = 1; year <= 4; year++) {
            for (let section = 1; section <= 3; section++) {
                const sectionName = `${course} ${year}-${section}`;
                const option = document.createElement('option');
                option.value = sectionName;
                option.textContent = sectionName;
                sectionSelect.appendChild(option);
            }
        }
    }
}
</script>
</body>
