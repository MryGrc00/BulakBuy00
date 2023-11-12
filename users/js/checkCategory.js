function checkCategory(select) {
    const otherCategoryDiv = document.getElementById("other-category");

    if (select.value === "Other") {
        otherCategoryDiv.style.display = "block";
        document.getElementById("other_category").setAttribute("required", "required");
    } else {
        otherCategoryDiv.style.display = "none";
        document.getElementById("other_category").removeAttribute("required");
    }
}
