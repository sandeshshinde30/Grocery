document.getElementById('forgotPasswordForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

    const email = this.querySelector('input[type="email"]').value; // Get the email value

    try {
        const response = await fetch('forgotPassword.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message); // Show success message
        } else {
            alert(result.error); // Show error message
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    }
});
