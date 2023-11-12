const form = document.querySelector(".signup form");
const continueBtn = form.querySelector(".button input");
const errorText = form.querySelector(".error-text");

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
        const response = await fetch("php/signup.php", {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                if (data.message === "OTP sent successfully.") {
                    window.location.href = "verification.php";
                } else {
                    // Handle other success messages if needed
                }
            } else {
                errorText.style.display = "block";
                errorText.textContent = data.message;
            }
        } else {
            console.error("Server error:", response.status);
            // Handle server errors if needed
        }
    } catch (error) {
        console.error("Error:", error);
        // Handle other errors if needed
    }
});
