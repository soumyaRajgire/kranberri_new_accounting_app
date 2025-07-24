// Get references to the form elements
const imageInputs = document.querySelectorAll('.imageInput');
const imagePreviews = document.querySelectorAll('.imagePreview');

// Listen for changes in all file inputs
imageInputs.forEach((imageInput, index) => {
    imageInput.addEventListener('change', function() {
        // Check if a file is selected
        if (imageInput.files && imageInput.files[0]) {
            // Create a FileReader object
            const reader = new FileReader();

            // When the FileReader has loaded the image, display it in the corresponding preview element
            reader.onload = function(event) {
                imagePreviews[index].src = event.target.result;
                imagePreviews[index].style.display = 'block'; // Show the image element
            };

            // Read the selected file as a data URL (base64 encoded string)
            reader.readAsDataURL(imageInput.files[0]);
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    alert("test");
    const selectDropdown = document.getElementById('selectDropdown');
    const tableContainer = document.getElementById('tableContainer');
    
    selectDropdown.addEventListener('change', function() {
        const selectedValue = selectDropdown.value;

        // Make an AJAX request to get the related table data using Fetch API
        fetch(`get_table_data.php?selected=${selectedValue}`)
            .then(response => response.text())
            .then(data => {
                console.log(data);
                tableContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});

