document.getElementById('photo_profil').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const img = document.getElementById('profile-image');
        img.src = e.target.result;
        img.style.display = 'block'; // Show the image
    }

    if (file) {
        reader.readAsDataURL(file);
    }
});