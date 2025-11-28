document.addEventListener("DOMContentLoaded", function () {
    var menuToggle = document.querySelector(".profile-menu-toggle");
    var menuBox = document.querySelector(".profile-menu-box");

    if (menuToggle && menuBox) {
        menuToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            if (menuBox.classList.contains("open")) {
                menuBox.classList.remove("open");
            } else {
                menuBox.classList.add("open");
            }
        });
    }

    document.addEventListener("click", function (e) {
        if (menuBox && menuBox.classList.contains("open")) {
            if (!menuBox.contains(e.target)) {
                menuBox.classList.remove("open");
            }
        }
    });
});
