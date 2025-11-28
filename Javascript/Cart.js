document.addEventListener("DOMContentLoaded", function () {
    var deleteBtn = document.querySelector(".delete-btn");
    var payBtn = document.querySelector(".pay-btn");
    var deleteModal = document.getElementById("deleteModal");
    var payModal = document.getElementById("payModal");
    var deleteOk = document.querySelector(".modal-delete-ok");
    var payOk = document.querySelector(".modal-pay-ok");
    var menuToggle = document.querySelector(".cart-menu-toggle");
    var menuBox = document.querySelector(".cart-menu-box");


    if (deleteOk && deleteModal) {
        deleteOk.addEventListener("click", function () {
            deleteModal.style.display = "none";
        });
    }

    if (payOk && payModal) {
        payOk.addEventListener("click", function () {
            payModal.style.display = "none";
        });
    }

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

document.querySelectorAll(".delete-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
        var deleteModal = document.getElementById("deleteModal");
        if (deleteModal) {
            deleteModal.style.display = "flex";
        }
    });
});

document.querySelectorAll(".pay-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
        var payModal = document.getElementById("payModal");
        if (payModal) {
            payModal.style.display = "flex";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".cart-item").forEach(function (item) {
        var qtyButtons = item.querySelectorAll(".qty-btn");
        var qtyLabel = item.querySelector(".qty-label");

        if (!qtyButtons.length || !qtyLabel) return;

        var minusBtn = qtyButtons[0];
        var plusBtn = qtyButtons[1];

        var currentQty = parseInt(qtyLabel.textContent.replace(/[^\d]/g, ""), 10);
        if (isNaN(currentQty) || currentQty < 1) {
            currentQty = 1;
        }

        function updateQtyLabel() {
            qtyLabel.textContent = "Q: " + currentQty;
        }

        updateQtyLabel();

        minusBtn.addEventListener("click", function () {
            if (currentQty > 1) {
                currentQty--;
                updateQtyLabel();
            }
        });

        plusBtn.addEventListener("click", function () {
            currentQty++;
            updateQtyLabel();
        });
    });
});
