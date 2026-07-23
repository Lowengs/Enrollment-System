<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');
?>

<title>Dashboard</title>

<main>
    <p class="welcome">Welcome, <b>
        <?php 
        if ($row) {
            echo $row['firstName'] . " " . $row['middleName'] . " " . $row['lastName'];
        } else {
            echo "Guest";
        }
        ?>
    </b></p>

    <div class="content">
        <div class="col-one">
            <div class="student-information">
                <div class="info-container">
                    <div class="info-section">
                        <h1>Student Information</h1>
                        <div class="info-content">
                            <?php 
                            if ($row) {
                                echo "<p><strong>Student ID:</strong> " . $row['studentId'] . "</p>";
                                echo "<p><strong>Name:</strong> " . $row['firstName'] . " " . $row['middleName'] . " " . $row['lastName'] . "</p>";
                                echo "<p><strong>Status:</strong> <span class='status-badge enrolled'>" . ($row['status'] ?? 'N/A') . "</span></p>";
                                echo "<p><strong>Course:</strong> " . $row['Course'] . "</p>";
                                echo "<p><strong>Year Level:</strong> " . $row['grade_level'] . "</p>";
                                echo "<p><strong>Date of Birth:</strong> " . $row['DOB'] . "</p>";
                                echo "<p><strong>Email:</strong> " . $row['Email'] . "</p>";
                                echo "<p><strong>Address:</strong> " . nl2br($row['Address']) . "</p>";
                                echo "<p><strong>Nationality:</strong> " . $row['Nationality'] . "</p>";
                                echo "<p><strong>Contact Number:</strong> " . $row['contactNo'] . "</p>";
                            } else {
                                echo "<p>No student information found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="profile-section">
                        <div class="profile-frame">
                           <?php
                // Check if the picture path exists
                if ($picturePath && file_exists($picturePath)) {
                    echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $picturePath) . '" style="width: 200px; height: 200px; ; background-color: #ccc;"">';
                } else {
                    echo '<img src="default-profile.png" alt="Profile Picture" width="200">'; // Default image if no picture is uploaded
                }
                ?>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        <div class="col-two">
            <div class="balance">
                <h1>Enrollment Status</h1>
                <div class="enrollment-status">
                    <div class="status-section">
                        <h3>Current Status</h3>
                        <div class="status-details">
                            <div class="detail-item">
                                <span class="label">Status:</span>
                                <?php
                                // Check if student is enrolled in any subjects
                                $enrollmentCheck = $conn->prepare("SELECT COUNT(*) as enrolled_count FROM studentsection WHERE studentNo = ?");
                                $enrollmentCheck->bind_param("i", $studentNo);
                                $enrollmentCheck->execute();
                                $enrollmentResult = $enrollmentCheck->get_result();
                                $enrollmentData = $enrollmentResult->fetch_assoc();
                                
                                if ($enrollmentData['enrolled_count'] > 0) {
                                    echo '<span class="status-badge enrolled">' . ($row['status'] ?? 'N/A') . '</span>';
                                } else {
                                    echo '<span class="status-badge not-enrolled">Not Yet Enrolled</span>';
                                }
                                ?>
                            </div>
                            <div class="detail-item">
                                <span class="label">Semester:</span>
                                <span class="value">Second Semester 2023-2024</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Academic Year:</span>
                                <span class="value">2023-2024</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Enrollment Date:</span>
                                <span class="value">January 15, 2024</span>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="progress">
                <h1>Academic Calendar</h1>
                <div class="calendar">
                    <div class="calendar-header">
                        <h3><?php echo date('F Y'); ?></h3>
                    </div>
                    <div class="calendar-grid">
                        <div class="weekdays">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="days">
                            <?php
                            $firstDay = date('w', strtotime(date('Y-m-01')));
                            $lastDay = date('t');
                            $currentDay = date('j');
                            
                            // Add empty cells for days before the first day of the month
                            for ($i = 0; $i < $firstDay; $i++) {
                                echo '<div class="day empty"></div>';
                            }
                            
                            // Add days of the month
                            for ($day = 1; $day <= $lastDay; $day++) {
                                $isToday = ($day == $currentDay) ? 'today' : '';
                                echo "<div class='day $isToday'>$day</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

<style>
    .status-badge {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.8rem;
        display: inline-block;
    }

    .status-badge.enrolled {
        background-color: #4CAF50;
        color: white;
    }

    .status-badge.not-enrolled {
        background-color: #f44336;
        color: white;
    }

    .detail-item {
        margin-bottom: 8px;
    }

    .label {
        font-weight: 500;
        margin-right: 6px;
        font-size: 0.9rem;
    }
</style>