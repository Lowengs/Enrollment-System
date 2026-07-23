<?php

include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');
include('Layout/Enrollment.php');

$studentNo = $_SESSION['studentNo'];
$studentQuery = "SELECT grade_level, Course FROM student WHERE studentNo = '$studentNo'";
$studentResult = $conn->query($studentQuery);
$student = $studentResult->fetch_assoc();
$studentGradeLevel = $student['grade_level'];
$studentCourse = $student['Course'];

// Check if student is already enrolled in any section for their year and course
$enrolledCheck = $conn->prepare("
    SELECT COUNT(*) as cnt
    FROM studentsection ss
    INNER JOIN section s ON ss.sectionid = s.sectionid
    WHERE ss.studentNo = ? AND s.section_name LIKE CONCAT(?, ' ', ?, '-%')
");
$enrolledCheck->bind_param("isi", $studentNo, $studentCourse, $studentGradeLevel);
$enrolledCheck->execute();
$enrolledResult = $enrolledCheck->get_result();
$enrolledData = $enrolledResult->fetch_assoc();
if ($enrolledData['cnt'] > 0) {
    echo '<div class="enrollment-warning">You are already enrolled for this year. Enrollment is locked.</div>';
    exit;
}

$semestersQuery = "SELECT DISTINCT semester FROM subjects ORDER BY semester ASC";
$semesters = $conn->query($semestersQuery);
?>


<title>Enrollment</title>
<link rel="stylesheet" href="../../CSS/student/Enrollment.css?v=<?= time(); ?>">

<style>
    .section-select-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin: 20px 0;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .section-select-container label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .section-select-container select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .section-select-container select:hover {
        border-color: #007bff;
    }

    .section-select-container select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .section-select-container select:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .section-select-container select option {
        padding: 10px;
        font-size: 14px;
        background-color: #fff;
        color: #333;
    }

    .section-select-container select option:hover {
        background-color: #f0f0f0;
    }

    .section-select-container select option:checked {
        background-color: #007bff;
        color: #fff;
    }

    .enrollment-warning {
        color: #dc3545;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        text-align: center;
    }

    .subject-already-enrolled {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 10px;
        margin: 5px 0;
        border-radius: 4px;
    }

    .remaining-subjects {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
    }
</style>

<main class="main-content">
    <input type="hidden" id="studentGradeLevel" value="<?php echo $studentGradeLevel; ?>">
    <input type="hidden" id="studentCourse" value="<?php echo $studentCourse; ?>">

    <h2>Enroll in Subjects</h2>

    <div class="section-select-container">
        <label for="yearSelect">Select Year</label>
        <select id="yearSelect">
            <option value="">-- Select Year --</option>
            <option value="1" <?php if($studentGradeLevel != 1) echo 'disabled'; ?>>1st Year</option>
            <option value="2" <?php if($studentGradeLevel != 2) echo 'disabled'; ?>>2nd Year</option>
            <option value="3" <?php if($studentGradeLevel != 3) echo 'disabled'; ?>>3rd Year</option>
            <option value="4" <?php if($studentGradeLevel != 4) echo 'disabled'; ?>>4th Year</option>
        </select>

        <label for="sectionSelect">Select Section</label>
        <select id="sectionSelect" disabled>
            <option value="">-- Select Section --</option>
        </select>

        <label for="semesterSelect">Select Semester</label>
        <select id="semesterSelect">
            <option value="">-- Select Semester --</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>

    <div id="subjectContainer">
        <!-- Subjects will load here -->
    </div>
</main>

<script>
function updateSections() {
    const yearSelect = document.getElementById('yearSelect');
    const sectionSelect = document.getElementById('sectionSelect');
    const selectedYear = yearSelect.value;
    const studentCourse = document.getElementById('studentCourse').value;

    sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';

    if (selectedYear) {
        for (let i = 1; i <= 3; i++) {
            const sectionName = `${studentCourse} ${selectedYear}-${i}`;
            const option = document.createElement('option');
            option.value = sectionName;
            option.textContent = sectionName;
            sectionSelect.appendChild(option);
        }
        sectionSelect.disabled = false;
    } else {
        sectionSelect.disabled = true;
    }
}

function loadSubjects() {
    const sectionName = document.getElementById('sectionSelect').value;
    const semester = document.getElementById('semesterSelect').value;
    const studentGradeLevel = document.getElementById('studentGradeLevel').value;
    const studentCourse = document.getElementById('studentCourse').value;

    if (sectionName === '' || semester === '') {
        document.getElementById('subjectContainer').innerHTML = '<p style="color:red;">Select both section and semester.</p>';
        return;
    }

    document.getElementById('subjectContainer').innerHTML = '<p>Loading subjects...</p>';

    fetch('../../PHP/Enrollmentcontent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `section_name=${encodeURIComponent(sectionName)}&semester=${encodeURIComponent(semester)}&student_grade_level=${encodeURIComponent(studentGradeLevel)}&student_course=${encodeURIComponent(studentCourse)}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('subjectContainer').innerHTML = data;
    })
    .catch(error => {
        document.getElementById('subjectContainer').innerHTML = '<p style="color:red;">Error loading subjects. Please try again.</p>';
    });
}

document.getElementById('yearSelect').addEventListener('change', function() {
    document.getElementById('studentGradeLevel').value = this.value;
    updateSections();
    // Reset section and semester dropdowns and clear subject list
    document.getElementById('sectionSelect').selectedIndex = 0;
    document.getElementById('semesterSelect').selectedIndex = 0;
    document.getElementById('subjectContainer').innerHTML = '';
});
document.getElementById('sectionSelect').addEventListener('change', loadSubjects);
document.getElementById('semesterSelect').addEventListener('change', loadSubjects);
</script>
