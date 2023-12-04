document.addEventListener("DOMContentLoaded", function() {
    const emailInput = document.getElementById("email");
    const form = document.querySelector("form");
    const errorText = document.querySelector(".error-text");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const email = emailInput.value.trim(); // Trim whitespace from the email input

        // Create a FormData object to send the email via AJAX
        const formData = new FormData();
        formData.append("email", email);

        // Send a POST request to your PHP script
        fetch("php/forgotpass.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            if (data.error) {
                errorText.textContent = data.error;
                errorText.classList.add("visible");
            } else if (data.success) {
                window.location.href = 'verify_password.php'; 
            } else {
                // Handle unexpected responses
                errorText.textContent = "An unexpected error occurred. Please try again.";
                errorText.classList.add("visible");
            }
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch.
            console.error("Error:", error);
            errorText.textContent = "An error occurred while processing your request.";
            errorText.classList.add("visible");
        });
    });
});
