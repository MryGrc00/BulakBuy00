document.addEventListener("DOMContentLoaded", function() {
    const emailInput = document.getElementById("email");
    const form = document.querySelector("form");
    const errorText = document.querySelector(".error-text");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const email = emailInput.value;

        // Create a FormData object to send the email via AJAX
        const formData = new FormData();
        formData.append("email", email);

        // Send a POST request to your PHP script
        fetch("php/forgotpass.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Handle the response from the server
            if (data === "Email does not exist. Please enter a valid email address.") {
                errorText.textContent = data;
                errorText.classList.add("visible"); // Show error message
            } else if (data === "Failed to send OTP.") {
                errorText.textContent = data;
                errorText.style.display = "block";
            } else if (data === "success") {
                // Redirect or perform other actions based on the response
                // For example, redirect to a verification page
                window.location.href = "verify_password.php";
            }
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch.
            console.error("Error:", error);
        });
    });
});
