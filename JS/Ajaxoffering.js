document.getElementById('program').addEventListener('change', fetchSections);
document.getElementById('semester').addEventListener('change', fetchSections);

function fetchSections() {
    const program = document.getElementById('program').value;
    const semester = document.getElementById('semester').value;

    if (!program || !semester) {
        document.getElementById('section').innerHTML = "<option value=''>- Select Section -</option>";
        return;
    }

    const data = new URLSearchParams();
    data.append('mode', 'getSectionsByProgramSemester');
    data.append('program', program);
    data.append('semester', semester);

    fetch('../PHP/GetOfferings.php', { method: 'POST', body: data })
        .then(response => response.text())
        .then(data => {
            document.getElementById('section').innerHTML = data;
        });
}


function loadOfferings() {
    const data = new URLSearchParams();
    data.append('mode', 'getOfferingsBySection');
    data.append('sectionid', document.getElementById('section').value);

    fetch('../PHP/GetOfferings.php', { method: 'POST', body: data })
        .then(response => response.text())
        .then(data => {
            document.getElementById('offeringsResult').innerHTML = data;
        });
}
