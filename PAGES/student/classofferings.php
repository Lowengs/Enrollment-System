<title>Class Offering</title>
<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');
?>
<link rel="stylesheet" href="../../CSS/student/Offering.css?v=<?= time(); ?>">

<div class="offering-header">
    <h2>Class Offerings</h2>
    <p class="subtitle">Select your program and semester to view available sections</p>
</div>

<div class="filters-section">
    <div class="filter-group">
        <label for="program">Program</label>
        <select id="program" class="elegant-select">
            <option value="">Select Program</option>
            <option value="BSAIT">BSAIT</option>
            <option value="BSAIS">BSAIS</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="semester">Semester</label>
        <select id="semester" class="elegant-select">
            <option value="">Select Semester</option>
            <option value="1">First Semester</option>
            <option value="2">Second Semester</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="sectionSelect">Section</label>
        <select id="sectionSelect" class="elegant-select">
            <option value="">Select Section</option>
        </select>
    </div>
</div>

<div class="content-section">
    <div class="schedule-header">
        <h3>Subjects and Schedules</h3>
    </div>
    <div id="subjectScheduleContainer" class="schedule-content">
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <p>Select a section to view subjects and schedules</p>
        </div>
    </div>
</div>

<script>
document.getElementById('program').addEventListener('change', loadSections);
document.getElementById('semester').addEventListener('change', loadSections);
document.getElementById('sectionSelect').addEventListener('change', loadSubjectsAndSchedules);

function loadSections() {
    const program = document.getElementById('program').value;
    const semester = document.getElementById('semester').value;

    if (!program || !semester) {
        document.getElementById('sectionSelect').innerHTML = '<option value="">-- Select Section --</option>';
        document.getElementById('subjectScheduleContainer').innerHTML = '<p>Select a section to view subjects and schedules.</p>';
        return;
    }

    fetch('../../PHP/GetSections.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'program=' + encodeURIComponent(program) + '&semester=' + encodeURIComponent(semester)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('sectionSelect').innerHTML = data;
        document.getElementById('subjectScheduleContainer').innerHTML = '<p>Select a section to view subjects and schedules.</p>';
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
}

function loadSubjectsAndSchedules() {
    const section = document.getElementById('sectionSelect').value;
    const semester = document.getElementById('semester').value;

    if (!section || !semester) {
        document.getElementById('subjectScheduleContainer').innerHTML = '<p>Select a section and semester to view subjects and schedules.</p>';
        return;
    }

    fetch('../../PHP/GetSubjects.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'section=' + encodeURIComponent(section) + '&semester=' + encodeURIComponent(semester)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('subjectScheduleContainer').innerHTML = data;
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
}

</script>
