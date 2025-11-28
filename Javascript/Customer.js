document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("addModal");

    if (!modal) {
        return;
    }

    var okButton = document.getElementById("addModalOk");
    var addButtons = document.querySelectorAll(".shop-add-btn");

    addButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            modal.style.display = "flex";
        });
    });

    if (okButton) {
        okButton.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    modal.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    var menuToggle = document.querySelector(".customer-menu-toggle");
    var menuBox = document.querySelector(".customer-logo-box");

    if (menuToggle && menuBox) {
        menuToggle.addEventListener("click", function (e) {
            e.stopPropagation();

            if (menuBox.classList.contains("open")) {
                menuBox.classList.remove("open");
            } else {
                menuBox.classList.add("open");
            }
        });

        document.addEventListener("click", function (e) {
            if (menuBox.classList.contains("open") && !menuBox.contains(e.target)) {
                menuBox.classList.remove("open");
            }
        });
    }
});
