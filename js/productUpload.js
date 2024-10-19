async function submitProductForm(event) {
    event.preventDefault();  // Prevent page reload

    const form = document.getElementById('productForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('productUpload.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            console.log('Product uploaded successfully!');
            console.log('Image URL:', result.image_url);
            alert('Product added successfully!');  // Show alert on success
        } else {
            console.error('Product upload failed:', result.error);
            alert('Product upload failed: ' + result.error);  // Show alert on error
        }
    } catch (error) {
        console.error('Error uploading product:', error);
        alert('Error uploading product: ' + error.message);  // Show alert on catch
    }
}
