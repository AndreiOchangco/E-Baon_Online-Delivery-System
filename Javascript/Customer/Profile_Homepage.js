document.addEventListener("DOMContentLoaded", function () {
    var sidebarItems = document.querySelectorAll(".customer-sidebar-item");
    var passwordInput = document.getElementById("profile-password");
    var togglePasswordBtn = document.getElementById("profile-password-toggle");

    var mapEl = document.getElementById("profile-location-map");
    var mapStatusEl = document.getElementById("profile-map-status");
    var addressText = document.getElementById("profile-address-text");
    var addressStatusEl = document.getElementById("profile-address-status");

    var profileMap = null;
    var profileLayerGroup = null;

    function showMapLoading() {
        if (mapStatusEl) {
            mapStatusEl.textContent = "Loading...";
            mapStatusEl.style.display = "inline-block";
            mapStatusEl.style.color = "#9ca3af";
        }
    }

    function showMapError() {
        if (mapStatusEl) {
            mapStatusEl.textContent = "No Network";
            mapStatusEl.style.display = "inline-block";
            mapStatusEl.style.color = "#dc2626";
        }
    }

    function hideMapStatus() {
        if (mapStatusEl) {
            mapStatusEl.style.display = "none";
        }
    }

    function showAddressLoading() {
        if (addressStatusEl) {
            addressStatusEl.textContent = "Loading...";
            addressStatusEl.style.display = "inline-block";
            addressStatusEl.style.color = "#9ca3af";
        }
    }

    function showAddressError() {
        if (addressStatusEl) {
            addressStatusEl.textContent = "No Network";
            addressStatusEl.style.display = "inline-block";
            addressStatusEl.style.color = "#dc2626";
        }
    }

    function hideAddressStatus() {
        if (addressStatusEl) {
            addressStatusEl.style.display = "none";
        }
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
                window.location.href = "Customer_Homepage.php#customer-cart";
                return;
            }

            if (action === "logout") {
                window.location.href = "../../Main/Logout.php";
            }
        });
    });

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePasswordBtn.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                togglePasswordBtn.textContent = "Show";
            }
        });
    }

    if (!mapEl && mapStatusEl) {
        hideMapStatus();
    }

    if (mapEl) {
        var toAddress = mapEl.getAttribute("data-to") || "";
        var fromAddress = mapEl.getAttribute("data-from") || "";

        if (!toAddress) {
            hideMapStatus();
        } else {
            if (typeof L === "undefined") {
                showMapError();
            } else {
                showMapLoading();
                initProfileMap(mapEl, fromAddress, toAddress);
            }
        }
    }

    if (addressText) {
        var rawAddress = addressText.value.trim();

        if (!rawAddress) {
            hideAddressStatus();
        } else {
            showAddressLoading();
            geocode(rawAddress)
                .then(function (result) {
                    if (!result) {
                        showAddressError();
                        return;
                    }
                    addressText.value = result.label;
                    hideAddressStatus();
                })
                .catch(function () {
                    showAddressError();
                });
        }
    }

    function initProfileMap(container, fromAddress, toAddress) {
        if (!container) {
            return;
        }

        if (typeof L === "undefined") {
            showMapError();
            return;
        }

        if (!profileMap) {
            profileMap = L.map(container).setView([14.5995, 120.9842], 12);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19
            }).addTo(profileMap);
            profileLayerGroup = L.layerGroup().addTo(profileMap);
            profileMap.once("load", function () {
                hideMapStatus();
            });
        }

        if (profileLayerGroup) {
            profileLayerGroup.clearLayers();
        }

        Promise.all([
            geocode(fromAddress || toAddress),
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

            L.marker(fromLatLng).addTo(profileLayerGroup);
            L.marker(toLatLng).addTo(profileLayerGroup);

            profileMap.fitBounds([fromLatLng, toLatLng], { padding: [20, 20] });

            var url =
                "https://router.project-osrm.org/route/v1/driving/" +
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
                    L.polyline(coords, { weight: 4, color: "#fbbf24" }).addTo(profileLayerGroup);
                    hideMapStatus();
                })
                .catch(function () {
                    showMapError();
                });
        }).catch(function () {
            showMapError();
        });
    }

    function geocode(q) {
        if (!q) {
            return Promise.resolve(null);
        }

        var url =
            "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" +
            encodeURIComponent(q);

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
                    lon: parseFloat(json[0].lon),
                    label: json[0].display_name || q
                };
            })
            .catch(function () {
                return null;
            });
    }
});
