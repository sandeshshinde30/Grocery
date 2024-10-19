async function submitReviewForm(event) {
    event.preventDefault();  // Prevent page reload

    const form = document.getElementById('reviewForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('reviewUpload.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            alert('Review uploaded successfully!');
            console.log('Image URL:', result.image_url);
        } else {
            console.error('Review upload failed:', result.error);
            alert('Review upload failed: ' + result.error);
        }
    } catch (error) {
        console.error('Error uploading review:', error);
        alert('Error uploading review: ' + error);
    }
}
