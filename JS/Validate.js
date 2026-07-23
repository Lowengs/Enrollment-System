
function validateForm() {
    const lastname = document.getElementById("lastname").value.trim();
    const firstname = document.getElementById("firstname").value.trim();
    const middlename = document.getElementById("middlename").value.trim();
    const email = document.getElementById("email").value.trim();
    const contact = document.getElementById("contact").value.trim();
    const address = document.getElementById("address").value.trim();
    const dob = document.getElementById("bod").value;
    const nationality = document.getElementById("nationality").value.trim();
    const course = document.getElementById("course").value;
    const pictureFile = document.getElementById("pictureUpload").files[0];
    const moralFile = document.getElementById("moralUpload").files[0];
    const cardFile = document.getElementById("cardUpload").files[0];




    // Check required fields
    if (!lastname || !firstname || !middlename || !email || !contact || !address || !dob || !nationality || !course ) {
        alert("Please fill out all required fields.");
        return false;
    }
    if (!pictureFile || !moralFile || !cardFile) {
    alert("Please upload all required documents.");
    return false;
    }
    const namePattern = /^[A-Za-z\s]+$/;
    if (!namePattern.test(lastname)) {
        alert("Last name should contain letters and spaces only.");
        return false;
    }
    if (!namePattern.test(firstname)) {
        alert("First name should contain letters and spaces only.");
        return false;
    }
    if (!namePattern.test(middlename)) {
        alert("Middle name should contain letters and spaces only.");
        return false;
    }

    // Validate email format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate contact number (only digits, 7 to 15 characters)
    const contactPattern = /^[0-9]{11}$/;
    if (!contactPattern.test(contact)) {
        alert("Please enter a valid contact number (11 digits).");
        return false;
    }

    // Validate date of birth (not empty and not a future date)
    const today = new Date().toISOString().split("T")[0];
    if (dob >= today) {
        alert("Date of Birth cannot be today or in the future.");
        return false;
    }



    // All good!
    return true;
}