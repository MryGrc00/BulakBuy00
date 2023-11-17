document.addEventListener("DOMContentLoaded", function() {
    const emailInput = document.getElementById("email");
    const form = document.querySelector("form");
    const errorText = document.querySelector(".error-text");
    const successText = document.querySelector(".success-text");

    form.addEventListener("submit", function(event) {
        event.preventDefault();
        const email = emailInput.value.trim();

        if (!email) {
            displayMessage(errorText, "Please enter an email address.");
            return;
        }

        const formData = new FormData();
        formData.append("email", email);

        fetch("php/forgotpass.php", { 
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                displayMessage(errorText, data.error);
            } else {
                displayMessage(successText, data.success);
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            displayMessage(errorText, "An error occurred while processing your request.");
        });
    });

    function displayMessage(element, message) {
        if (element) {
            element.textContent = message;
            element.style.display = "block";
        }
    }
});
