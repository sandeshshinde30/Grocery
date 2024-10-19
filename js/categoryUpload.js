async function submitCategoryForm(event) {
    event.preventDefault();  // Prevent page reload

    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('categoryUpload.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            alert('Category uploaded successfully!');
            console.log('Image URL:', result.image_url);
        } else {
            console.error('Category upload failed:', result.error);
        }
    } catch (error) {
        console.error('Error uploading category:', error);
    }
}
