function Validatesub()
{  const form = document.querySelector("form");
  form.addEventListener("submit", function(e) {
    const course = document.querySelector('select[name="course"]').value;
    const subjectName = document.querySelector('#subject-name').value.trim();
    const abbr = document.querySelector('#abbreviation').value.trim();
    const day = document.querySelector('select[name="day"]').value;
    const startTime = document.querySelector('input[name="start_time"]').value;
    const endTime = document.querySelector('input[name="end_time"]').value;
    const credits = document.querySelector('#credits').value.trim();
    const grade = document.querySelector('select[name="grade"]').value;
    const semester = document.querySelector('select[name="semester"]').value;


    if (course === "#" || course === "") {
        alert("Please select a course.");
        e.preventDefault();
        return;
      }
      
    if (course === "#" || !subjectName || !abbr || !day || !startTime || !endTime || !credits || !grade || !semester) {
      alert("Please fill in all fields before submitting.");
      e.preventDefault();
      return;
    }

    // Time validation
    if (startTime >= endTime) {
      alert("Start time must be earlier than end time.");
      e.preventDefault();
      return;
    }

    // Credits validation
    if (isNaN(credits) || credits <= 0) {
      alert("Please enter a valid positive number for credits.");
      e.preventDefault();
      return;
    }
  });
}