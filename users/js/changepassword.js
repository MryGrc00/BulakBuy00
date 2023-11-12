const form = document.querySelector("form");
const errorText = document.querySelector(".error-text");

form.onsubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    try {
        if (response.ok) {
            const data = await response.text();
            handleResponse(data);
        } else {
            throw new Error("Network response was not ok.");
        }
    } catch (error) {
        console.error("Error:", error);
    }
};

function handleResponse(response) {
    errorText.style.display = "block";
    errorText.innerHTML = response;
}
