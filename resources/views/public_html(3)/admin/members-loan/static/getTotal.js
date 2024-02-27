window.addEventListener('DOMContentLoaded', function() {
    // Make an AJAX request to get the count
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/getTotalCount.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Update the totalEntry span with the retrieved count
            document.getElementById('totalEntry').textContent = xhr.responseText;
        } else {
            // Handle errors
            document.getElementById('totalEntry').textContent = 'Error: ' + xhr.statusText;
        }
    };

    xhr.send();
});