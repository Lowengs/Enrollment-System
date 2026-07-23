<?php 
include('../../PHP/Connection.php')
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href=" ../../CSS/header.css">
    <link rel="stylesheet" href="../../CSS/registration/registration.css">


    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
<img src="../../CSS/pictures/philsca-removebg-preview.png" alt="">
        <p >REPUBLIC OF THE PHILIPPINES <br>
           <span> PHILIPPINE STATE COLLEGE OF AERONAUTICS</span> <br>
            PICCIO GARDEN, VILLAMOR PASAY CITY </p>
            <img src="../../CSS/pictures/ics.png" alt="">
    </header>

    <main>
        <a href="../../index.php" class="back-button">← Back</a>
        <div class="formcontainer">
           
            <h1>Student Registration Form</h1>
            
            <form action="../../PHP/Submit.php" method="POST" onsubmit="return validateForm()"  enctype="multipart/form-data"  class="student-form">

    
                <div class="top-section">
                    <div>
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required placeholder="Enter your last name" class="input-field">
                    </div>
            
                    <div>
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required placeholder="Enter your first name" class="input-field">
                    </div>
                    
                    <div>
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" required placeholder="Enter your middle name" class="input-field">
                    </div>
                </div>
            
                <div class="second-section">
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email" class="input-field">
                    </div>
            
                    <div>
                        <label for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact" required placeholder="Enter your contact number" class="input-field">
                    </div>
            
                    <div>
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" required placeholder="Enter your address" class="input-field">
                    </div>
                </div>
            
                <div class="third-section">
                    <div>
                        <label for="bod">Date of Birth</label>
                        <input type="date" id="bod" name="dob" required class="input-field">
                    </div>
            
                    <div>
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality" required placeholder="Enter your nationality" class="input-field">
                    </div>
            
                    <div>
                        <label for="course">Select Course</label>
                        <select name="course" id="course" required class="input-field">
                            <option value=""disabled selected>Course</option>
                            <option value="BSAIT">Bachelor of Science in Aviation Information Technology</option>
                            <option value="BSAIS">Bachelor of Science in Aviation Information System</option>
                        </select>
                    </div>
            
                    <div>
                        <label for="year_level">Select Year Level</label>
                        <select name="year_level" id="year_level" required class="input-field">
                            <option value="" disabled selected>Year Level</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
            
                    <div class="image-upload">
    <label for="pictureUpload" class="form-label">Upload a 1x1 Picture</label>
    <p>Ensure that your Picture is <b>Clear</b></p>
    <input type="file" name="picture" id="pictureUpload" accept="image/*" class="file-input" required>
</div>

<div class="image-upload">
    <label for="moralUpload" class="form-label">Upload Good Moral</label>
    <p>Ensure that your Picture is <b>Clear</b></p>
    <input type="file" name="moral" id="moralUpload" accept="image/*" class="file-input" required>
</div>

<div class="image-upload">
    <label for="cardUpload" class="form-label">Upload Grade Card</label>
    <p>Ensure that your Picture is <b>Clear</b></p>
    <input type="file" name="card" id="cardUpload" accept="image/*" class="file-input" required>
</div>

                </div>
            
                <div class="submit-section">
                    <button type="submit" class="submit-btn">Register</button>
                </div>
            
            </form>
            

        </div>
    </main>
</body>

<Script src="../../JS/validate.js"></Script>
</html>