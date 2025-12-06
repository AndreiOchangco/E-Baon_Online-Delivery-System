document.addEventListener("DOMContentLoaded", function () {
    var sidebarItems = document.querySelectorAll(".customer-sidebar-item");
    var searchInput = document.getElementById("customer-search-input");
    var productCards = document.querySelectorAll(".customer-product-card");
    var productsGrid = document.querySelector(".customer-products-grid");
    var cartItemsContainer = document.getElementById("customer-cart-items");
    var subtotalEl = document.getElementById("customer-cart-subtotal");
    var feeEl = document.getElementById("customer-cart-fee");
    var totalEl = document.getElementById("customer-cart-total");
    var checkoutBtn = document.getElementById("customer-cart-checkout-btn");
    var addressToggleBtn = document.getElementById("customer-cart-address-toggle");
    var addressForm = document.getElementById("customer-address-form");
    var addressFromInput = document.getElementById("customer-address-from");
    var addressToInput = document.getElementById("customer-address-to");
    var orderModal = document.getElementById("customer-order-modal");
    var orderModalOkBtn = document.getElementById("customer-modal-ok-btn");
    var modalTitleEl = orderModal ? orderModal.querySelector(".customer-modal-title") : null;
    var cart = {};

    var addressDisplayText = addressToggleBtn
        ? addressToggleBtn.querySelector(".customer-cart-address-text")
        : null;

    var fromRegionSelect = document.getElementById("customer-from-region");
    var fromProvinceSelect = document.getElementById("customer-from-province");
    var fromCitySelect = document.getElementById("customer-from-city");
    var fromBarangaySelect = document.getElementById("customer-from-barangay");

    var toRegionSelect = document.getElementById("customer-to-region");
    var toProvinceSelect = document.getElementById("customer-to-province");
    var toCitySelect = document.getElementById("customer-to-city");
    var toBarangaySelect = document.getElementById("customer-to-barangay");

    sidebarItems.forEach(function (btn) {
        btn.addEventListener("click", function () {
            sidebarItems.forEach(function (b) {
                b.classList.remove("customer-sidebar-item-active");
            });
            btn.classList.add("customer-sidebar-item-active");
            var action = btn.getAttribute("data-action");

            if (action === "home") {
                window.location.href = "Customer_Homepage.php";
                return;
            }

            if (action === "menu") {
                window.location.href = "Menu_Homepage.php";
                return;
            }

            if (action === "profile") {
                window.location.href = "Profile_Homepage.php";
                return;
            }

            if (action === "cart") {
                var cartPanel = document.getElementById("customer-cart");
                if (cartPanel) {
                    cartPanel.scrollIntoView({ behavior: "smooth" });
                }
                return;
            }

            if (action === "logout") {
                window.location.href = "../../Main/Logout.php";
            }
        });
    });

    if (searchInput && productCards.length > 0) {
        searchInput.addEventListener("input", function () {
            var query = searchInput.value.toLowerCase().trim();
            productCards.forEach(function (card) {
                var name = (card.getAttribute("data-name") || "").toLowerCase();
                card.style.display = name.indexOf(query) !== -1 ? "" : "none";
            });
        });
    }

    if (addressToggleBtn && addressForm) {
        addressToggleBtn.addEventListener("click", function () {
            if (addressForm.style.display === "flex") {
                addressForm.style.display = "none";
            } else {
                addressForm.style.display = "flex";
            }
        });
    }

    if (productsGrid) {
        productsGrid.addEventListener("click", function (e) {
            var favoriteBtn = e.target.closest(".customer-product-favorite");
            if (favoriteBtn) {
                favoriteBtn.classList.toggle("active");
                return;
            }

            var addBtn = e.target.closest(".customer-add-btn");
            if (!addBtn) {
                return;
            }
            var card = addBtn.closest(".customer-product-card");
            if (!card) {
                return;
            }
            var id = card.getAttribute("data-id");
            var name = card.getAttribute("data-name") || "";
            var price = parseFloat(card.getAttribute("data-price") || "0");
            var image = card.getAttribute("data-image") || "";
            if (!id) {
                return;
            }
            if (!cart[id]) {
                cart[id] = {
                    id: id,
                    name: name,
                    price: price,
                    image: image,
                    quantity: 1
                };
            } else {
                cart[id].quantity += 1;
            }
            renderCart();
        });
    }

    if (cartItemsContainer) {
        cartItemsContainer.addEventListener("click", function (e) {
            var removeBtn = e.target.closest(".customer-cart-remove-btn");
            var qtyIncrease = e.target.closest(".customer-cart-qty-btn-increase");
            var qtyDecrease = e.target.closest(".customer-cart-qty-btn-decrease");

            if (removeBtn) {
                var row = removeBtn.closest(".customer-cart-item");
                if (row) {
                    var id = row.getAttribute("data-id");
                    if (id && cart[id]) {
                        delete cart[id];
                        renderCart();
                    }
                }
                return;
            }

            if (!qtyIncrease && !qtyDecrease) {
                return;
            }

            var row2 = e.target.closest(".customer-cart-item");
            if (!row2) {
                return;
            }
            var id2 = row2.getAttribute("data-id");
            if (!id2 || !cart[id2]) {
                return;
            }

            if (qtyIncrease) {
                cart[id2].quantity += 1;
            } else if (qtyDecrease) {
                cart[id2].quantity -= 1;
                if (cart[id2].quantity <= 0) {
                    delete cart[id2];
                }
            }
            renderCart();
        });
    }

    function renderCart() {
        if (!cartItemsContainer) {
            return;
        }
        var html = "";
        var subtotal = 0;

        Object.keys(cart).forEach(function (id) {
            var item = cart[id];
            var line = item.price * item.quantity;
            subtotal += line;

            html += '<div class="customer-cart-item" data-id="' + item.id + '">';
            html += '  <div class="customer-cart-item-image-wrap">';
            html += '    <img src="' + item.image + '" class="customer-cart-item-image" alt="' + item.name.replace(/"/g, "&quot;") + '">';
            html += '  </div>';
            html += '  <div class="customer-cart-item-main">';
            html += '    <div class="customer-cart-item-name">' + item.name + "</div>";
            html += '    <div class="customer-cart-item-note">Extra cheese</div>';
            html += '    <div class="customer-cart-item-bottom">';
            html += '      <div class="customer-cart-item-left">';
            html += '        <button type="button" class="customer-cart-remove-btn">✕</button>';
            html += '        <div class="customer-cart-qty-box">';
            html += '          <button type="button" class="customer-cart-qty-btn customer-cart-qty-btn-decrease">-</button>';
            html += '          <span class="customer-cart-qty-value">' + item.quantity + "</span>";
            html += '          <button type="button" class="customer-cart-qty-btn customer-cart-qty-btn-increase">+</button>';
            html += "        </div>";
            html += "      </div>";
            html += '      <div class="customer-cart-item-price">₱' + line.toFixed(2) + "</div>";
            html += "    </div>";
            html += "  </div>";
            html += "</div>";
        });

        cartItemsContainer.innerHTML = html;
        updateTotals(subtotal);
    }

    function updateTotals(subtotal) {
        var fee = subtotal > 0 ? 9 : 0;
        var total = subtotal + fee;
        if (subtotalEl) subtotalEl.textContent = "₱" + subtotal.toFixed(2);
        if (feeEl) feeEl.textContent = "₱" + fee.toFixed(2);
        if (totalEl) totalEl.textContent = "₱" + total.toFixed(2);
    }

    function openOrderModal(message) {
        if (modalTitleEl && typeof message === "string") {
            modalTitleEl.textContent = message;
        }
        if (orderModal) {
            orderModal.style.display = "flex";
        }
    }

    function closeOrderModal() {
        if (orderModal) {
            orderModal.style.display = "none";
        }
    }

    if (orderModalOkBtn) {
        orderModalOkBtn.addEventListener("click", function () {
            closeOrderModal();
        });
    }

    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", function () {
            if (!addressFromInput || !addressToInput) {
                openOrderModal("Address inputs are missing.");
                return;
            }

            var fromVal = addressFromInput.value.trim();
            var toVal = addressToInput.value.trim();

            if (fromVal === "" || toVal === "") {
                if (addressForm && addressForm.style.display !== "flex") {
                    addressForm.style.display = "flex";
                }
                openOrderModal("Please fill in both address fields before placing your order.");
                return;
            }

            var cartKeys = Object.keys(cart);
            if (cartKeys.length === 0) {
                openOrderModal("Your cart is empty.");
                return;
            }

            var cartPayload = cartKeys.map(function (id) {
                var item = cart[id];
                return {
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity
                };
            });

            var payload = {
                cart: cartPayload,
                from_address: fromVal,
                to_address: toVal
            };

            fetch("../../Body/Customer/create_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            })
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    if (!data || !data.success) {
                        openOrderModal(data && data.message ? data.message : "Failed to place order.");
                        return;
                    }

                    cart = {};
                    renderCart();
                    openOrderModal("Your Order Has Been Placed!");
                })
                .catch(function (err) {
                    console.error(err);
                    openOrderModal("Error placing order.");
                });
        });
    }

    var PSGC_BASE = "https://psgc.gitlab.io/api";

    function fetchJson(url) {
        return fetch(url)
            .then(function (res) {
                if (!res.ok) throw new Error("HTTP " + res.status);
                return res.json();
            })
            .catch(function (err) {
                console.error("PSGC API error:", err);
                return [];
            });
    }

    function populateSelect(select, items, valueKey, labelKey, placeholder) {
        if (!select) return;

        select.innerHTML = "";
        var placeholderOption = document.createElement("option");
        placeholderOption.value = "";
        placeholderOption.textContent = placeholder || "Select";
        select.appendChild(placeholderOption);

        items.forEach(function (item) {
            var opt = document.createElement("option");
            opt.value = item[valueKey];
            opt.textContent = item[labelKey];
            select.appendChild(opt);
        });

        select.disabled = items.length === 0;
    }

    function buildFullAddress(regionSel, provinceSel, citySel, barangaySel) {
        var parts = [];

        if (barangaySel && barangaySel.value && barangaySel.selectedIndex > -1) {
            parts.push(barangaySel.options[barangaySel.selectedIndex].text);
        }
        if (citySel && citySel.value && citySel.selectedIndex > -1) {
            parts.push(citySel.options[citySel.selectedIndex].text);
        }
        if (provinceSel && provinceSel.value && provinceSel.selectedIndex > -1) {
            parts.push(provinceSel.options[provinceSel.selectedIndex].text);
        }
        if (regionSel && regionSel.value && regionSel.selectedIndex > -1) {
            parts.push(regionSel.options[regionSel.selectedIndex].text);
        }

        return parts.join(", ");
    }

    function initAddressCombos() {
        if (!fromRegionSelect && !toRegionSelect) return;

        fetchJson(PSGC_BASE + "/regions/")
            .then(function (regions) {
                regions.sort(function (a, b) {
                    return a.name.localeCompare(b.name);
                });
                populateSelect(fromRegionSelect, regions, "code", "name", "Select region");
                populateSelect(toRegionSelect, regions, "code", "name", "Select region");
            });

        function attachHandlers(regionSel, provinceSel, citySel, barangaySel, hiddenInput) {
            if (!regionSel || !provinceSel || !citySel || !barangaySel || !hiddenInput) {
                return;
            }

            function maybeUpdateDeliveryLabel() {
                if (hiddenInput === addressToInput && addressDisplayText) {
                    var full = hiddenInput.value.trim();
                    addressDisplayText.textContent = full || "Select delivery address";
                }
            }

            regionSel.addEventListener("change", function () {
                var code = regionSel.value;

                populateSelect(provinceSel, [], "code", "name", "Select province");
                populateSelect(citySel, [], "code", "name", "Select city / municipality");
                populateSelect(barangaySel, [], "code", "name", "Select barangay");

                if (!code) {
                    hiddenInput.value = "";
                    maybeUpdateDeliveryLabel();
                    return;
                }

                fetchJson(PSGC_BASE + "/regions/" + code + "/provinces/")
                    .then(function (provinces) {
                        provinces.sort(function (a, b) {
                            return a.name.localeCompare(b.name);
                        });
                        populateSelect(provinceSel, provinces, "code", "name", "Select province");
                    });

                hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                maybeUpdateDeliveryLabel();
            });

            provinceSel.addEventListener("change", function () {
                var code = provinceSel.value;

                populateSelect(citySel, [], "code", "name", "Select city / municipality");
                populateSelect(barangaySel, [], "code", "name", "Select barangay");

                if (!code) {
                    hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                    maybeUpdateDeliveryLabel();
                    return;
                }

                fetchJson(PSGC_BASE + "/provinces/" + code + "/cities-municipalities/")
                    .then(function (cities) {
                        cities.sort(function (a, b) {
                            return a.name.localeCompare(b.name);
                        });
                        populateSelect(citySel, cities, "code", "name", "Select city / municipality");
                    });

                hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                maybeUpdateDeliveryLabel();
            });

            citySel.addEventListener("change", function () {
                var code = citySel.value;

                populateSelect(barangaySel, [], "code", "name", "Select barangay");

                if (!code) {
                    hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                    maybeUpdateDeliveryLabel();
                    return;
                }

                fetchJson(PSGC_BASE + "/cities-municipalities/" + code + "/barangays/")
                    .then(function (barangays) {
                        barangays.sort(function (a, b) {
                            return a.name.localeCompare(b.name);
                        });
                        populateSelect(barangaySel, barangays, "code", "name", "Select barangay");
                    });

                hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                maybeUpdateDeliveryLabel();
            });

            barangaySel.addEventListener("change", function () {
                hiddenInput.value = buildFullAddress(regionSel, provinceSel, citySel, barangaySel);
                maybeUpdateDeliveryLabel();
            });
        }

        attachHandlers(
            fromRegionSelect,
            fromProvinceSelect,
            fromCitySelect,
            fromBarangaySelect,
            addressFromInput
        );

        attachHandlers(
            toRegionSelect,
            toProvinceSelect,
            toCitySelect,
            toBarangaySelect,
            addressToInput
        );
    }

    var promoSlider = document.getElementById("customer-promo-slider");
    var promoTrack = promoSlider ? promoSlider.querySelector(".customer-promo-track") : null;
    var promoSlides = promoSlider ? promoSlider.querySelectorAll(".customer-promo-slide") : [];
    var promoDots = promoSlider ? promoSlider.querySelectorAll(".customer-promo-dot") : [];
    var currentSlide = 0;
    var slideCount = promoSlides.length;
    var autoSlideInterval = null;
    var touchStartX = 0;
    var touchEndX = 0;

    function goToSlide(index) {
        if (!promoTrack || slideCount === 0) {
            return;
        }
        if (index < 0) index = slideCount - 1;
        if (index >= slideCount) index = 0;

        currentSlide = index;
        var offset = -100 * currentSlide;
        promoTrack.style.transform = "translateX(" + offset + "%)";
        promoDots.forEach(function (dot, i) {
            if (i === currentSlide) {
                dot.classList.add("customer-promo-dot-active");
            } else {
                dot.classList.remove("customer-promo-dot-active");
            }
        });
    }

    function startAutoSlide() {
        if (autoSlideInterval || slideCount === 0) return;
        autoSlideInterval = setInterval(function () {
            goToSlide(currentSlide + 1);
        }, 3000);
    }

    function stopAutoSlide() {
        if (!autoSlideInterval) return;
        clearInterval(autoSlideInterval);
        autoSlideInterval = null;
    }

    if (promoDots.length > 0) {
        promoDots.forEach(function (dot) {
            dot.addEventListener("click", function () {
                var index = parseInt(dot.getAttribute("data-index") || "0", 10);
                stopAutoSlide();
                goToSlide(index);
                startAutoSlide();
            });
        });
    }

    if (promoSlider) {
        promoSlider.addEventListener("mouseenter", stopAutoSlide);
        promoSlider.addEventListener("mouseleave", startAutoSlide);
        promoSlider.addEventListener("touchstart", function (e) {
            if (!e.touches || e.touches.length === 0) return;
            touchStartX = e.touches[0].clientX;
            touchEndX = touchStartX;
            stopAutoSlide();
        });
        promoSlider.addEventListener("touchmove", function (e) {
            if (!e.touches || e.touches.length === 0) return;
            touchEndX = e.touches[0].clientX;
        });
        promoSlider.addEventListener("touchend", function () {
            var diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) goToSlide(currentSlide + 1);
                else goToSlide(currentSlide - 1);
            }
            startAutoSlide();
        });
    }

    goToSlide(0);
    startAutoSlide();

    initAddressCombos();
});
