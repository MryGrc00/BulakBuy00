const form = document.querySelector(".login form");
const continueBtn = form.querySelector(".button input");
const errorText = form.querySelector(".error-text");

form.onsubmit = (e) => {
    e.preventDefault();
};

continueBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/login.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.responseText.split("|");
                if (data[0] === "success") {
                    let role = data[1]; // Get the role from the response
                    if (role === "customer") {
                        location.href = "customer/customer_home.php";
                    } if (role === "seller") {
                        location.href = "vendor/vendor_home.php";
                    } else if (role === "arranger") {
                        location.href = "arranger/arranger_home.php";
                    }else {
                        // Handle unknown roles, if needed
                        console.error("Unknown role: " + role);
                    }
                } else {
                    // Display error message
                    errorText.style.display = "block";
                    if (data[0] === "Email not verified!") {
                        // If email is not verified, show error message with verification link
                        errorText.innerHTML = `${data[0]} <a href='resend_verification.php?email=${encodeURIComponent(data[1])}'>Resend verification email</a>`;
                    } else {
                        // For other error messages, just display the text
                        errorText.textContent = data[0];
                    }
                }
            }
        }
    };
    // Get form data and send it to the PHP script
    let formData = new FormData(form);
    xhr.send(formData);
};
