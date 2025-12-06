document.addEventListener("DOMContentLoaded", function () {
    var navButtons = document.querySelectorAll(".delivery-nav-item");
    var ordersData = Array.isArray(window.DELIVERY_ORDERS_DATA) ? window.DELIVERY_ORDERS_DATA : [];
    var listContainer = document.getElementById("delivery-order-list");
    var detailEmpty = document.getElementById("delivery-detail-empty");
    var detailCard = document.getElementById("delivery-detail-card");
    var detailOrderId = document.getElementById("detail-order-id");
    var detailOrderDate = document.getElementById("detail-order-date");
    var detailAddress = document.getElementById("detail-address");
    var detailPaymentStatus = document.getElementById("detail-payment-status");
    var detailOrderStatusText = document.getElementById("detail-order-status-text");
    var detailItems = document.getElementById("detail-items");
    var detailTotal = document.getElementById("detail-total");
    var tabs = document.querySelectorAll(".delivery-tab");
    var statusButtons = document.querySelectorAll(".delivery-status-action-btn");
    var printBtn = document.getElementById("delivery-print-btn");
    var currentOrderId = null;

    var statusTextMap = {
        preparing: "Order being prepared",
        delivering: "Delivering to you",
        completed: "Completed"
    };

    function setStatusButtonsByStatus(status) {
        statusButtons.forEach(function (btn) {
            var btnStatus = btn.getAttribute("data-status");
            if (btnStatus === status) {
                btn.classList.add("delivery-status-action-active");
            } else {
                btn.classList.remove("delivery-status-action-active");
            }
        });
    }

    navButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            navButtons.forEach(function (b) {
                b.classList.remove("delivery-nav-item-active");
            });
            btn.classList.add("delivery-nav-item-active");

            var label = btn.textContent.trim();

            if (label === "Dashboard") {
                window.location.href = "Delivery_Homepage.php";
                return;
            }

            if (label === "Profile") {
                window.location.href = "Profile_D.php";
                return;
            }
        });
    });

    function findOrder(id) {
        var numeric = parseInt(id, 10);
        for (var i = 0; i < ordersData.length; i++) {
            if (parseInt(ordersData[i].id, 10) === numeric) {
                return ordersData[i];
            }
        }
        return null;
    }

    function setActiveRow(orderId) {
        var rows = listContainer ? listContainer.querySelectorAll(".delivery-order-row") : [];
        rows.forEach(function (row) {
            if (row.getAttribute("data-order-id") === String(orderId)) {
                row.classList.add("delivery-order-row-active");
            } else {
                row.classList.remove("delivery-order-row-active");
            }
        });
    }

    function renderDetails(orderId) {
        var data = findOrder(orderId);
        if (!data) {
            if (detailCard) {
                detailCard.classList.remove("delivery-detail-card-active");
            }
            if (detailEmpty) {
                detailEmpty.style.display = "block";
            }
            currentOrderId = null;
            return;
        }

        if (detailEmpty) {
            detailEmpty.style.display = "none";
        }
        if (detailCard) {
            detailCard.classList.add("delivery-detail-card-active");
        }

        var labelStatus = statusTextMap[data.status] || data.status;

        if (detailOrderId) {
            detailOrderId.textContent = "Order #" + data.id;
        }
        if (detailOrderDate) {
            detailOrderDate.textContent = data.created_at || "";
        }
        if (detailAddress) {
            detailAddress.textContent = data.to_address || "";
        }
        if (detailPaymentStatus) {
            detailPaymentStatus.textContent = data.status === "completed" ? "Completed" : "Pending";
        }
        if (detailOrderStatusText) {
            detailOrderStatusText.textContent = labelStatus;
        }
        if (detailTotal) {
            detailTotal.textContent = "₱" + Number(data.total || 0).toFixed(2);
        }

        setStatusButtonsByStatus(data.status);

        if (detailItems) {
            var html = "";
            var items = Array.isArray(data.items) ? data.items : [];
            items.forEach(function (it) {
                var line = "";
                line += '<div class="delivery-detail-item-row">';
                line += '<div class="delivery-detail-item-left">';
                line += '<div class="delivery-detail-item-thumb" style="background-image:url(\'' + (it.image_path || "") + '\');"></div>';
                line += '<div class="delivery-detail-item-text">';
                line += '<div class="delivery-detail-item-name">' + (it.name || "") + '</div>';
                line += '<div class="delivery-detail-item-qty">x' + (it.qty || 0) + '</div>';
                line += '</div>';
                line += '</div>';
                line += '<div class="delivery-detail-item-price">₱' + Number(it.price || 0).toFixed(2) + '</div>';
                line += '</div>';
                html += line;
            });
            detailItems.innerHTML = html;
        }

        currentOrderId = data.id;
        setActiveRow(orderId);
    }

    function updateOrderStatusLocal(orderId, newStatus) {
        var data = findOrder(orderId);
        if (!data) {
            return;
        }

        data.status = newStatus;

        var labelStatus = statusTextMap[newStatus] || newStatus;

        var rows = listContainer ? listContainer.querySelectorAll(".delivery-order-row") : [];
        rows.forEach(function (row) {
            if (row.getAttribute("data-order-id") === String(orderId)) {
                row.setAttribute("data-status", newStatus);
                var labelElem = row.querySelector(".delivery-order-row-status");
                if (labelElem) {
                    labelElem.textContent = labelStatus;
                }
            }
        });

        if (detailOrderStatusText) {
            detailOrderStatusText.textContent = labelStatus;
        }
        if (detailPaymentStatus) {
            detailPaymentStatus.textContent = newStatus === "completed" ? "Completed" : "Pending";
        }

        setStatusButtonsByStatus(newStatus);
    }

    function handleRowClicks() {
        if (!listContainer) {
            return;
        }
        listContainer.addEventListener("click", function (e) {
            var row = e.target.closest(".delivery-order-row");
            if (!row) {
                return;
            }
            var orderId = row.getAttribute("data-order-id");
            renderDetails(orderId);
        });
    }

    function handleTabs() {
        tabs.forEach(function (tab) {
            tab.addEventListener("click", function () {
                var filter = tab.getAttribute("data-filter") || "all";

                tabs.forEach(function (t) {
                    t.classList.remove("delivery-tab-active");
                });
                tab.classList.add("delivery-tab-active");

                var rows = listContainer ? listContainer.querySelectorAll(".delivery-order-row") : [];
                rows.forEach(function (row) {
                    var st = row.getAttribute("data-status") || "";
                    if (filter === "all") {
                        row.style.display = "";
                    } else if (filter === "preparing") {
                        row.style.display = st === "preparing" ? "" : "none";
                    } else if (filter === "delivering") {
                        row.style.display = st === "delivering" ? "" : "none";
                    } else if (filter === "completed") {
                        row.style.display = st === "completed" ? "" : "none";
                    } else {
                        row.style.display = "";
                    }
                });
            });
        });
    }

    function handleStatusButtons() {
        statusButtons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                if (!detailOrderId) {
                    return;
                }

                var text = detailOrderId.textContent || "";
                var parts = text.split("#");
                if (parts.length < 2) {
                    return;
                }

                var orderId = parseInt(parts[1], 10);
                if (!orderId) {
                    return;
                }

                var newStatus = btn.getAttribute("data-status");
                if (!newStatus) {
                    return;
                }

                var formData = new FormData();
                formData.append("action", "update_status");
                formData.append("order_id", orderId);
                formData.append("status", newStatus);

                fetch("Delivery_Homepage.php", {
                    method: "POST",
                    body: formData
                })
                    .then(function (res) {
                        return res.json();
                    })
                    .then(function (data) {
                        if (!data || !data.success) {
                            return;
                        }
                        updateOrderStatusLocal(orderId, newStatus);
                    })
                    .catch(function (err) {
                        console.error("Status update error", err);
                    });
            });
        });
    }

    function buildReceiptHtml(order) {
        var items = Array.isArray(order.items) ? order.items : [];
        var itemsHtml = "";

        items.forEach(function (it) {
            itemsHtml += ''
                + '<div class="r-item-row">'
                + '  <div class="r-item-left">'
                + '    <div class="r-thumb"' + (it.image_path ? ' style="background-image:url(\'' + it.image_path + '\');"' : '') + '></div>'
                + '    <div class="r-item-text">'
                + '      <div class="r-item-name">' + (it.name || "") + '</div>'
                + '      <div class="r-item-qty">x' + (it.qty || 0) + '</div>'
                + '    </div>'
                + '  </div>'
                + '  <div class="r-item-price">₱' + Number(it.price || 0).toFixed(2) + '</div>'
                + '</div>';
        });

        var html =
            '<!DOCTYPE html>' +
            '<html lang="en">' +
            '<head>' +
            '<meta charset="UTF-8">' +
            '<title></title>' +
            '<style>' +
            '@page{margin:0;}' +
            'html,body{margin:0;padding:0;font-family:Arial,sans-serif;background:#ffffff;color:#000;}' +
            'body{padding:24px 32px;}' +
            '.r-card{max-width:650px;margin:0 auto;background:#ffffff;border-radius:24px;padding:24px 32px;}' +
            '.r-shop-title{font-size:22px,font-weight:700;text-align:center;margin-bottom:6px;}' +
            '.r-order-id{font-size:16px;font-weight:700;text-align:center;margin-bottom:4px;}' +
            '.r-order-date{font-size:12px;text-align:center;margin-bottom:16px;}' +
            '.r-meta-grid{border-top:1px solid #000;padding-top:16px;margin-top:4px;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;}' +
            '.r-meta-block{display:flex;flex-direction:column;gap:2px;font-size:12px;}' +
            '.r-meta-label{font-weight:400;}' +
            '.r-items{margin-top:16px;padding-top:16px;border-top:1px solid #000;display:flex;flex-direction:column;gap:8px;}' +
            '.r-item-row{display:flex;justify-content:space-between;align-items:center;}' +
            '.r-item-left{display:flex;align-items:center;gap:8px;}' +
            '.r-thumb{width:32px;height:32px;border-radius:999px;background-size:cover;background-position:center;background-repeat:no-repeat;}' +
            '.r-item-text{display:flex;flex-direction:column;font-size:12px;}' +
            '.r-item-qty{font-size:11px;}' +
            '.r-item-price{font-size:12px;font-weight:600;min-width:70px;text-align:right;white-space:nowrap;}' +
            '.r-total-row{margin-top:18px;padding-top:12px;border-top:1px solid #000;display:flex;justify-content:space-between;font-size:13px;}' +
            '@media print{' +
            '  body{background:#ffffff;padding:15mm 20mm;}' +
            '  .r-card{box-shadow:none;border-radius:0;max-width:100%;}' +
            '}' +
            '</style>' +
            '</head>' +
            '<body>' +
            '<div class="r-card">' +
            '<div class="r-shop-title">E-Baon</div>' +
            '<div class="r-order-id">Order #' + order.id + '</div>' +
            '<div class="r-order-date">' + (order.created_at || "") + '</div>' +
            '<div class="r-meta-grid">' +
            '  <div class="r-meta-block"><div class="r-meta-label">Delivery Address</div><div class="r-meta-value">' + (order.to_address || "") + '</div></div>' +
            '  <div class="r-meta-block"><div class="r-meta-label">Estimation Time</div><div class="r-meta-value">10 Min</div></div>' +
            '  <div class="r-meta-block"><div class="r-meta-label">Distance</div><div class="r-meta-value">2.5 Km</div></div>' +
            '  <div class="r-meta-block"><div class="r-meta-label">Payment</div><div class="r-meta-value">Cash on Delivery</div></div>' +
            '  <div class="r-meta-block"><div class="r-meta-label">Payment Status</div><div class="r-meta-value">' + (order.status === "completed" ? "Completed" : "Pending") + '</div></div>' +
            '  <div class="r-meta-block"><div class="r-meta-label">Order Status</div><div class="r-meta-value">' + (statusTextMap[order.status] || order.status) + '</div></div>' +
            '</div>' +
            '<div class="r-items">' + itemsHtml + '</div>' +
            '<div class="r-total-row"><span>Total</span><span>₱' + Number(order.total || 0).toFixed(2) + '</span></div>' +
            '</div>' +
            '</body>' +
            '</html>';

        return html;
    }

    function handlePrintButton() {
        if (!printBtn) {
            return;
        }

        printBtn.addEventListener("click", function () {
            if (!currentOrderId) {
                return;
            }

            var order = findOrder(currentOrderId);
            if (!order) {
                return;
            }

            var receiptHtml = buildReceiptHtml(order);
            var w = window.open("", "_blank");
            if (!w) {
                return;
            }

            w.document.open();
            w.document.write(receiptHtml);
            w.document.close();
            w.focus();
            w.print();
        });
    }

    handleRowClicks();
    handleTabs();
    handleStatusButtons();
    handlePrintButton();

    if (ordersData.length > 0) {
        renderDetails(ordersData[0].id);
    }
});
