const filterForm = document.getElementById('filterForm');
  const tableBody = document.getElementById('tableBody');

  function fetchClasses() {
    const semester = document.getElementById('semester').value;
    const course = document.getElementById('course').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../PHP/fetch-class.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      if (xhr.status === 200) {
        tableBody.innerHTML = xhr.responseText;
      } else {
        tableBody.innerHTML = '<tr><td colspan="11">Error loading data</td></tr>';
      }
    };
    xhr.send(`semester=${semester}&course=${course}`);
  }

  // Submit handler
  filterForm.addEventListener('submit', function (e) {
    e.preventDefault();
    fetchClasses();
  });

  // Load data on page load
  window.addEventListener('DOMContentLoaded', fetchClasses);