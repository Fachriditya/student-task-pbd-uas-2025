var dropdownBtns = document.getElementsByClassName("dropdown-btn");

for (var i = 0; i < dropdownBtns.length; i++) {
    dropdownBtns[i].addEventListener("click", function(event) {
        event.preventDefault();
        
        this.classList.toggle("active");
        var content = this.nextElementSibling;

        if (content.style.display === "block") {
            content.style.display = "none";
        } else {
            content.style.display = "block";
        }
    });
}