document.addEventListener("DOMContentLoaded", function () {
    var imageInputs = document.querySelectorAll(".image-input");
    var imagePreview = document.getElementById("imagePreview");

    function displayImages() {
        imagePreview.innerHTML = "";
        imageInputs.forEach(function (input) {
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files[i];
                if (file.type.startsWith("image/")) {
                    var imageUrl = URL.createObjectURL(file);
                    var imageContainer = document.createElement("div");
                    imageContainer.classList.add("image-container");

                    var imageElement = document.createElement("img");
                    imageElement.classList.add("preview-image");
                    imageElement.src = imageUrl;

                    imageContainer.appendChild(imageElement);
                    imagePreview.appendChild(imageContainer);
                }
            }
        });
    }

    // Add event listeners to all file inputs
    imageInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            displayImages();
        });
    });
});