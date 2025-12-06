document.addEventListener("DOMContentLoaded", function () {
    var ordersData = window.DELIVERY_ORDERS_DATA || [];
    var ordersById = {};
    ordersData.forEach(function (o) {
        ordersById[o.id] = o;
    });

    var listContainer = document.getElementById("delivery-order-list");
    var detailCard = document.getElementById("delivery-detail-card");
    var detailEmpty = document.getElementById("delivery-detail-empty");

    var detailOrderId = document.getElementById("detail-order-id");
    var detailOrderDate = document.getElementById("detail-order-date");
    var detailAddress = document.getElementById("detail-address");
    var detailPaymentStatus = document.getElementById("detail-payment-status");
    var detailOrderStatusText = document.getElementById("detail-order-status-text");
    var detailItems = document.getElementById("detail-items");
    var detailTotal = document.getElementById("detail-total");

    var statusButtons = document.querySelectorAll(".delivery-status-action-btn");
    var tabs = document.querySelectorAll(".delivery-tab");
    var orderRows = document.querySelectorAll(".delivery-order-row");

    var activeOrderId = null;
    var currentFilter = "all";

    var sidebarItems = document.querySelectorAll(".customer-sidebar-item");
    var ordersGrid = document.getElementById("menu-orders-grid");
    var searchInput = document.getElementById("menu-search-input");
    var mapEl = document.getElementById("menu-map");
    var mapStatusEl = document.getElementById("menu-map-status");
    var addressValueEl = document.getElementById("menu-address-value");
    var routeMap = null;
    var routeLayerGroup = null;

    var defaultFrom = mapEl ? (mapEl.getAttribute("data-from") || "") : "";
    var defaultTo = mapEl ? (mapEl.getAttribute("data-to") || "") : "";
    var defaultAddressText = addressValueEl ? addressValueEl.textContent : "";

    var orderCards = document.querySelectorAll(".menu-order-card");
    var selectedOrderId = null;

    var menuOrdersData = window.MENU_ORDERS_DATA || [];
    var menuOrdersById = {};
    menuOrdersData.forEach(function (o) {
        if (!o || typeof o.id === "undefined") return;
        menuOrdersById[String(o.id)] = o;
    });
    var defaultActiveOrderId =
        typeof window.MENU_ACTIVE_ORDER_ID !== "undefined" &&
        window.MENU_ACTIVE_ORDER_ID !== null
            ? String(window.MENU_ACTIVE_ORDER_ID)
            : null;
    var sideListContainer = document.querySelector(".menu-order-side-list");

    function showMapLoading() {
        if (!mapStatusEl) return;
        mapStatusEl.textContent = "Loading...";
        mapStatusEl.style.display = "inline-block";
        mapStatusEl.style.color = "#9ca3af";
    }

    function showMapError() {
        if (!mapStatusEl) return;
        mapStatusEl.textContent = "No Network";
        mapStatusEl.style.display = "inline-block";
        mapStatusEl.style.color = "#dc2626";
    }

    function hideMapStatus() {
        if (!mapStatusEl) return;
        mapStatusEl.style.display = "none";
    }

    function statusText(status) {
        if (status === "completed") return "Completed";
        if (status === "delivering") return "Delivering to you";
        return "Order being prepared";
    }

    function getStatusClass(status) {
        if (status === "completed") return "menu-order-status-completed";
        if (status === "delivering") return "menu-order-status-delivering";
        return "menu-order-status-preparing";
    }

    function updateSideOrder(orderId) {
        if (!sideListContainer) return;
        var order = menuOrdersById[String(orderId)];
        if (!order) return;

        sideListContainer.innerHTML = "";

        var titleDiv = document.createElement("div");
        titleDiv.className = "menu-order-side-title";
        titleDiv.textContent = "Order Menu";
        sideListContainer.appendChild(titleDiv);

        (order.items || []).forEach(function (item) {
            var row = document.createElement("div");
            row.className = "menu-order-side-item";

            var left = document.createElement("div");
            left.className = "menu-order-side-left";

            var thumb = document.createElement("div");
            thumb.className = "menu-order-side-thumb";
            if (item.image_path) {
                thumb.style.backgroundImage = "url('" + item.image_path + "')";
            }

            var textBox = document.createElement("div");
            textBox.className = "menu-order-side-text";

            var nameEl = document.createElement("div");
            nameEl.className = "menu-order-side-name";
            nameEl.textContent = item.product_name || "";

            var qtyEl = document.createElement("div");
            qtyEl.className = "menu-order-side-qty";
            qtyEl.textContent = "x" + (item.quantity || 0);

            textBox.appendChild(nameEl);
            textBox.appendChild(qtyEl);

            left.appendChild(thumb);
            left.appendChild(textBox);

            var priceEl = document.createElement("div");
            priceEl.className = "menu-order-side-price";
            var price = Number(item.price || 0);
            priceEl.textContent = "+₱" + price.toFixed(2);

            row.appendChild(left);
            row.appendChild(priceEl);

            sideListContainer.appendChild(row);
        });

        var totalRow = document.createElement("div");
        totalRow.className = "menu-order-total-row";
        totalRow.style.marginTop = "10px";

        var totalLabel = document.createElement("span");
        totalLabel.textContent = "Total";

        var totalAmount = document.createElement("span");
        totalAmount.className = "menu-order-total-amount";
        var totalVal = typeof order.total === "number" ? order.total : 0;
        totalAmount.textContent = "₱" + totalVal.toFixed(2);

        totalRow.appendChild(totalLabel);
        totalRow.appendChild(totalAmount);
        sideListContainer.appendChild(totalRow);

        var btn = document.createElement("button");
        btn.type = "button";
        btn.className = "menu-order-status-btn " + getStatusClass(order.status);
        btn.style.marginTop = "10px";
        btn.textContent = statusText(order.status);
        sideListContainer.appendChild(btn);
    }

    function renderDetail(order) {
        if (!detailCard || !detailEmpty || !detailOrderId || !detailOrderDate || !detailAddress || !detailPaymentStatus || !detailOrderStatusText || !detailItems || !detailTotal) {
            return;
        }

        if (!order) {
            detailCard.classList.remove("delivery-detail-card-active");
            detailEmpty.style.display = "block";
            return;
        }

        detailEmpty.style.display = "none";
        detailCard.classList.add("delivery-detail-card-active");

        detailOrderId.textContent = "Order #" + order.id;
        detailOrderDate.textContent = order.created_at || "";
        detailAddress.textContent = order.to_address || "Not specified";
        detailPaymentStatus.textContent = "Completed";
        detailOrderStatusText.textContent = statusText(order.status);

        detailItems.innerHTML = "";
        var total = 0;
        (order.items || []).forEach(function (item) {
            var row = document.createElement("div");
            row.className = "delivery-detail-item-row";

            var left = document.createElement("div");
            left.className = "delivery-detail-item-left";

            var thumb = document.createElement("div");
            thumb.className = "delivery-detail-item-thumb";
            if (item.image_path) {
                thumb.style.backgroundImage = "url('" + item.image_path + "')";
            }

            var textBox = document.createElement("div");
            textBox.className = "delivery-detail-item-text";

            var nameEl = document.createElement("div");
            nameEl.className = "delivery-detail-item-name";
            nameEl.textContent = item.name || "";

            var qtyEl = document.createElement("div");
            qtyEl.className = "delivery-detail-item-qty";
            qtyEl.textContent = "x" + item.qty;

            textBox.appendChild(nameEl);
            textBox.appendChild(qtyEl);

            left.appendChild(thumb);
            left.appendChild(textBox);

            var priceEl = document.createElement("div");
            priceEl.className = "delivery-detail-item-price";
            var lineTotal = (item.price || 0) * (item.qty || 0);
            total += lineTotal;
            priceEl.textContent = "₱" + lineTotal.toFixed(2);

            row.appendChild(left);
            row.appendChild(priceEl);

            detailItems.appendChild(row);
        });

        if (!total && typeof order.total === "number") {
            total = order.total;
        }
        detailTotal.textContent = "₱" + total.toFixed(2);

        updateStatusButtonsUI(order.status);

        if (mapEl) {
            var fromAddress = order.from_address || defaultFrom;
            var toAddress = order.to_address || defaultTo;
            if (fromAddress && toAddress) {
                initRouteMap(mapEl, fromAddress, toAddress);
            }
            if (addressValueEl && toAddress) {
                addressValueEl.textContent = toAddress;
            }
        }
    }

    function updateStatusButtonsUI(status) {
        statusButtons.forEach(function (btn) {
            btn.classList.remove("delivery-status-action-active");
        });
        var activeBtn = document.querySelector('.delivery-status-action-btn[data-status="' + status + '"]');
        if (activeBtn) {
            activeBtn.classList.add("delivery-status-action-active");
        }
    }

    function applyFilter() {
        orderRows.forEach(function (row) {
            var rowStatus = row.getAttribute("data-status");
            if (currentFilter === "all") {
                row.style.display = "";
            } else {
                row.style.display = rowStatus === currentFilter ? "" : "none";
            }
        });
    }

    orderRows.forEach(function (row) {
        row.addEventListener("click", function () {
            var id = parseInt(row.getAttribute("data-order-id"), 10);
            activeOrderId = id;

            orderRows.forEach(function (r) {
                r.classList.remove("delivery-order-row-active");
            });
            row.classList.add("delivery-order-row-active");

            var order = ordersById[id];
            renderDetail(order);
        });
    });

    tabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
            tabs.forEach(function (t) {
                t.classList.remove("delivery-tab-active");
            });
            tab.classList.add("delivery-tab-active");

            currentFilter = tab.getAttribute("data-filter") || "all";
            applyFilter();
        });
    });

    statusButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (!activeOrderId) {
                return;
            }

            var newStatus = btn.getAttribute("data-status");
            if (!newStatus) {
                return;
            }

            var order = ordersById[activeOrderId];
            if (!order) {
                return;
            }

            updateStatusButtonsUI(newStatus);

            order.status = newStatus;
            renderDetail(order);

            var row = document.querySelector('.delivery-order-row[data-order-id="' + activeOrderId + '"]');
            if (row) {
                row.setAttribute("data-status", newStatus);
                var statusEl = row.querySelector(".delivery-order-row-status");
                if (statusEl) {
                    statusEl.textContent = statusText(newStatus);
                }
            }

            applyFilter();

            var formData = new URLSearchParams();
            formData.append("action", "update_status");
            formData.append("order_id", String(activeOrderId));
            formData.append("status", newStatus);

            fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: formData.toString()
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                if (!data || !data.success) {
                    console.error("Failed to update status", data);
                }
            }).catch(function (err) {
                console.error("Network error while updating status", err);
            });
        });
    });

    if (!mapEl && mapStatusEl) {
        hideMapStatus();
    }

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
                return;
            }

            if (action === "logout") {
                window.location.href = "../../Main/Logout.php";
            }
        });
    });

    if (searchInput && ordersGrid) {
        searchInput.addEventListener("input", function () {
            var q = searchInput.value.toLowerCase().trim();
            var cards = ordersGrid.querySelectorAll(".menu-order-card");
            cards.forEach(function (card) {
                var text = card.textContent.toLowerCase();
                card.style.display = text.indexOf(q) !== -1 ? "" : "none";
            });
        });
    }

    if (mapEl) {
        if (typeof L === "undefined") {
            showMapError();
        } else {
            if (defaultFrom && defaultTo) {
                showMapLoading();
                initRouteMap(mapEl, defaultFrom, defaultTo);
            } else {
                showMapLoading();
                routeMap = L.map(mapEl).setView([14.5995, 120.9842], 5);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19
                }).addTo(routeMap);
                routeLayerGroup = L.layerGroup().addTo(routeMap);
                routeMap.once("load", function () {
                    hideMapStatus();
                });
            }
        }
    }

    if (orderRows.length > 0) {
        orderRows[0].click();
    }

    orderCards.forEach(function (card) {
        card.addEventListener("click", function () {
            var orderId = card.getAttribute("data-order-id");
            var isSame = orderId === selectedOrderId;

            orderCards.forEach(function (c) {
                c.classList.remove("menu-order-card-selected");
            });

            if (isSame) {
                selectedOrderId = null;
                if (defaultFrom && defaultTo && mapEl) {
                    initRouteMap(mapEl, defaultFrom, defaultTo);
                }
                if (addressValueEl && defaultAddressText !== null) {
                    addressValueEl.textContent = defaultAddressText;
                }
                if (defaultActiveOrderId) {
                    updateSideOrder(defaultActiveOrderId);
                }
                return;
            }

            selectedOrderId = orderId;
            card.classList.add("menu-order-card-selected");

            var fromAddress = card.getAttribute("data-from-address") || "";
            var toAddress = card.getAttribute("data-to-address") || "";

            if (addressValueEl) {
                if (toAddress) {
                    addressValueEl.textContent = toAddress;
                } else if (defaultAddressText !== null) {
                    addressValueEl.textContent = defaultAddressText;
                }
            }

            if (fromAddress && toAddress && mapEl) {
                initRouteMap(mapEl, fromAddress, toAddress);
            } else if (defaultFrom && defaultTo && mapEl) {
                initRouteMap(mapEl, defaultFrom, defaultTo);
            }

            if (orderId && menuOrdersById[orderId]) {
                updateSideOrder(orderId);
            }
        });
    });

    if (defaultActiveOrderId) {
        updateSideOrder(defaultActiveOrderId);
    }

    function initRouteMap(container, fromAddress, toAddress) {
        if (!container) {
            return;
        }
        if (typeof L === "undefined") {
            showMapError();
            return;
        }

        showMapLoading();

        if (!routeMap) {
            routeMap = L.map(container).setView([14.5995, 120.9842], 12);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19
            }).addTo(routeMap);
            routeLayerGroup = L.layerGroup().addTo(routeMap);
            routeMap.once("load", function () {
                hideMapStatus();
            });
        }

        if (routeLayerGroup) {
            routeLayerGroup.clearLayers();
        }

        Promise.all([
            geocode(fromAddress),
            geocode(toAddress)
        ]).then(function (results) {
            var from = results[0];
            var to = results[1];
            if (!from || !to) {
                showMapError();
                return;
            }

            var fromLatLng = [from.lat, from.lon];
            var toLatLng = [to.lat, to.lon];

            L.marker(fromLatLng).addTo(routeLayerGroup);
            L.marker(toLatLng).addTo(routeLayerGroup);

            routeMap.fitBounds([fromLatLng, toLatLng], { padding: [20, 20] });

            var url = "https://router.project-osrm.org/route/v1/driving/" +
                from.lon + "," + from.lat + ";" +
                to.lon + "," + to.lat +
                "?overview=full&geometries=geojson";

            fetch(url)
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    if (!data || !data.routes || !data.routes.length) {
                        showMapError();
                        return;
                    }
                    var coords = data.routes[0].geometry.coordinates.map(function (c) {
                        return [c[1], c[0]];
                    });
                    L.polyline(coords, { weight: 4, color: "#fbbf24" }).addTo(routeLayerGroup);
                    hideMapStatus();
                })
                .catch(function (err) {
                    console.error(err);
                    showMapError();
                });
        }).catch(function (err) {
            console.error(err);
            showMapError();
        });
    }

    function geocode(q) {
        var url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" + encodeURIComponent(q);
        return fetch(url, {
            headers: {
                "Accept-Language": "en"
            }
        })
            .then(function (res) {
                return res.json();
            })
            .then(function (json) {
                if (!json || !json.length) return null;
                return {
                    lat: parseFloat(json[0].lat),
                    lon: parseFloat(json[0].lon)
                };
            })
            .catch(function (err) {
                console.error(err);
                showMapError();
                return null;
            });
    }
});
