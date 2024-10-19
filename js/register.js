function validateForm() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value.trim();

    const namePattern = /^[A-Za-z\s]{3,}$/;  // Name: min 3 letters
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;  // Valid email
    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;  // Min 8 chars, 1 letter & 1 number

    // Check for blank inputs
    if (!name || !email || !password || !confirmPassword) {
        alert("All fields are required.");
        return false;
    }

    // Validate name
    if (!namePattern.test(name)) {
        alert("Name must be at least 3 letters long and contain only letters and spaces.");
        return false;
    }

    // Validate email
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate password
    if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters long and contain at least one letter and one number.");
        return false;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    // If all validations pass, submit the form
    submitForm();
    return false;  // Prevent default form submission
}

function submitForm() {
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            alert('Registration successful!');
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 0);
        } else {
            alert(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}