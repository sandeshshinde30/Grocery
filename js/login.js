function submitLogin(event) {
    event.preventDefault(); // Prevent default form submission

    const form = document.getElementById('loginForm');
    const formData = new FormData(form);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Check the response and redirect accordingly
        if (data.trim() === 'success') {
            alert('Login successful! Redirecting to Home...');
            window.location.href = 'index.html'; // Redirect to index.html for regular users
        } 
        else if (data.trim() === 'admin') {
            alert('Login successful as Admin! Redirecting to Admin Dashboard...');
            window.location.href = 'Admin.html'; // Redirect to Admin.html for admin
        } else {
            alert(data); // Display the error message if needed
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
