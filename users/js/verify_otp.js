const otpForm = document.getElementById("otp-form");
const errorMessage = document.querySelector(".error-message");
const inputs = document.querySelectorAll("input");
const verifyButton = document.getElementById("verify-button");
const emailVerifiedModal = document.getElementById('emailverified');
const closeEmail = document.getElementById('closeEmail');

otpForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const enteredOTP = Array.from(inputs).map(input => input.value).join('');

    // Send the entered OTP to the backend for verification
    fetch('php/verify_otp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `verificationCode=${enteredOTP}`,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.json();
    })
    .then(data => {
        if (data && typeof data === 'object' && 'success' in data && 'message' in data) {
            if (data.success) {
                // OTP is correct and email status is 'Verified', show the email verified modal
                console.log("OTP is correct and email status is Verified. Showing email verified modal...");
                showEmailVerifiedModal();
            } else {
                // OTP is incorrect or email status is not 'Verified', display error message to the user
                errorMessage.textContent = data.message;
                errorMessage.classList.add("visible"); // Show error message
            }
        } else {
            // Invalid JSON response from the server
            throw new Error("Invalid JSON response from the server");
        }
    })
    .catch(error => {
        // Handle network or other errors
        errorMessage.textContent = "Error occurred. Please try again later.";
        errorMessage.hidden = false;
        console.error('Error:', error);
    });
});

function showEmailVerifiedModal() {
    emailVerifiedModal.style.display = 'block';
}

close.onclick = function() {
    emailVerifiedModal.style.display = 'none';
};

window.onclick = function(event) {
    if (event.target === emailVerifiedModal) {
        emailVerifiedModal.style.display = 'none';
    }
};

inputs.forEach((input, index) => {
    input.addEventListener("input", (e) => {
        const currentInput = input,
            nextInput = input.nextElementSibling,
            prevInput = input.previousElementSibling;

        if (currentInput.value.length > 1) {
            currentInput.value = currentInput.value[1]; // Keep only the last digit
            return;
        }

        if (nextInput && nextInput.hasAttribute("disabled") && currentInput.value !== "") {
            nextInput.removeAttribute("disabled");
            nextInput.focus();
        }

        if (e.key === "Backspace") {
            inputs.forEach((input, index2) => {
                if (index <= index2 && prevInput) {
                    input.setAttribute("disabled", true);
                    input.value = "";
                    prevInput.focus();
                }
            });
        }

        if (!inputs[5].disabled && inputs[5].value !== "") {
            verifyButton.classList.add("active");
            return;
        }

        verifyButton.classList.remove("active");
    });
});
