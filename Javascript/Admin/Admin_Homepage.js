document.addEventListener("DOMContentLoaded", function () {
    var sidebarItems = document.querySelectorAll(".admin-sidebar-item");
    var panels = document.querySelectorAll(".admin-panel");

    function showPanel(panelId) {
        panels.forEach(function (panel) {
            if (panel.id === panelId) {
                panel.classList.add("active");
            } else {
                panel.classList.remove("active");
            }
        });
    }

    sidebarItems.forEach(function (btn) {
        btn.addEventListener("click", function () {
            sidebarItems.forEach(function (b) {
                b.classList.remove("active");
            });
            btn.classList.add("active");
            var target = btn.getAttribute("data-panel");
            if (target) {
                showPanel(target);
            }
        });
    });

    var metricsElements = {
        total_orders: document.getElementById("metric-total-orders"),
        total_delivered: document.getElementById("metric-total-delivered"),
        total_canceled: document.getElementById("metric-total-canceled"),
        total_revenue: document.getElementById("metric-total-revenue"),
        open_orders: document.getElementById("metric-open-orders")
    };

    function getNumberFromElement(el) {
        if (!el) {
            return 0;
        }
        var text = el.textContent || "";
        var cleaned = text.replace(/[^0-9\-]/g, "");
        var value = parseInt(cleaned, 10);
        if (isNaN(value)) {
            return 0;
        }
        return value;
    }

    function recalcOpenOrders() {
        var open = getNumberFromElement(metricsElements.open_orders);
        if (isNaN(open) || open < 0) {
            open = 0;
        }
        metricsElements.open_orders.textContent = open;
    }

    var ordersPieChart;
    var revenueLineChart;
    var ordersBarChart;

    function buildOrdersBarData() {
        var labels = [];
        var totalOrdersArr = [];
        var deliveredArr = [];
        var canceledArr = [];

        if (!window.monthlyOrdersData) {
            return {
                labels: labels,
                totalOrdersArr: totalOrdersArr,
                deliveredArr: deliveredArr,
                canceledArr: canceledArr
            };
        }

        window.monthlyOrdersData.forEach(function (row) {
            labels.push(row.month);
            totalOrdersArr.push(parseInt(row.total_orders, 10) || 0);
            deliveredArr.push(parseInt(row.delivered, 10) || 0);
            canceledArr.push(parseInt(row.canceled, 10) || 0);
        });

        return {
            labels: labels,
            totalOrdersArr: totalOrdersArr,
            deliveredArr: deliveredArr,
            canceledArr: canceledArr
        };
    }

    function buildRevenueLineData() {
        var labels = [];
        var revenueArr = [];

        if (!window.monthlyRevenueData || !window.monthlyRevenueData.length) {
            var fallbackMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            fallbackMonths.forEach(function (m) {
                labels.push(m);
                revenueArr.push(0);
            });
            return {
                labels: labels,
                revenueArr: revenueArr
            };
        }

        window.monthlyRevenueData.forEach(function (row) {
            labels.push(row.month);
            revenueArr.push(parseInt(row.revenue, 10) || 0);
        });

        return {
            labels: labels,
            revenueArr: revenueArr
        };
    }

    var saveModal;
    var saveModalOk;

    function setupSaveModal() {
        saveModal = document.createElement("div");
        saveModal.id = "saveModal";
        saveModal.style.position = "fixed";
        saveModal.style.inset = "0";
        saveModal.style.background = "rgba(15,23,42,0.45)";
        saveModal.style.display = "none";
        saveModal.style.alignItems = "center";
        saveModal.style.justifyContent = "center";
        saveModal.style.zIndex = "1200";

        var box = document.createElement("div");
        box.style.background = "#ffffff";
        box.style.padding = "20px 24px";
        box.style.borderRadius = "16px";
        box.style.boxShadow = "0 20px 40px rgba(15,23,42,0.3)";
        box.style.minWidth = "260px";
        box.style.textAlign = "center";

        var p = document.createElement("p");
        p.textContent = "The data has been saved!";
        p.style.marginBottom = "16px";
        p.style.fontSize = "14px";
        p.style.color = "#111827";

        saveModalOk = document.createElement("button");
        saveModalOk.textContent = "OK";
        saveModalOk.style.padding = "8px 18px";
        saveModalOk.style.borderRadius = "999px";
        saveModalOk.style.border = "none";
        saveModalOk.style.background = "#22c55e";
        saveModalOk.style.color = "#0f172a";
        saveModalOk.style.fontWeight = "bold";
        saveModalOk.style.cursor = "pointer";

        box.appendChild(p);
        box.appendChild(saveModalOk);
        saveModal.appendChild(box);
        document.body.appendChild(saveModal);

        saveModalOk.addEventListener("click", function () {
            saveModal.style.display = "none";
        });

        saveModal.addEventListener("click", function (e) {
            if (e.target === saveModal) {
                saveModal.style.display = "none";
            }
        });
    }

    function showSaveModal() {
        if (saveModal) {
            saveModal.style.display = "flex";
        }
    }

    setupSaveModal();

    function initCharts() {
        var totalOrders = getNumberFromElement(metricsElements.total_orders);
        var delivered = getNumberFromElement(metricsElements.total_delivered);
        var canceled = getNumberFromElement(metricsElements.total_canceled);
        var open = getNumberFromElement(metricsElements.open_orders);
        var revenue = getNumberFromElement(metricsElements.total_revenue);

        var pieCtx = document.getElementById("ordersPieChart").getContext("2d");
        var lineCtx = document.getElementById("revenueLineChart").getContext("2d");
        var barCtx = document.getElementById("ordersBarChart").getContext("2d");

        ordersPieChart = new Chart(pieCtx, {
            type: "doughnut",
            data: {
                labels: ["Delivered", "Canceled", "Orders Being Process", "Total Orders", "Total Revenue"],
                datasets: [
                    {
                        data: [delivered, canceled, open, totalOrders, revenue]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "right",
                        align: "center",
                        labels: {
                            boxWidth: 16,
                            padding: 12
                        }
                    }
                },
                layout: {
                    padding: {
                        right: 20
                    }
                }
            }
        });

        var lineData = buildRevenueLineData();

        revenueLineChart = new Chart(lineCtx, {
            type: "line",
            data: {
                labels: lineData.labels,
                datasets: [
                    {
                        label: "Revenue 2025",
                        data: lineData.revenueArr,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "right",
                        align: "center",
                        labels: {
                            boxWidth: 16,
                            padding: 12
                        }
                    }
                },
                layout: {
                    padding: {
                        right: 20
                    }
                }
            }
        });

        var barData = buildOrdersBarData();

        ordersBarChart = new Chart(barCtx, {
            type: "bar",
            data: {
                labels: barData.labels,
                datasets: [
                    {
                        label: "Total Orders",
                        data: barData.totalOrdersArr
                    },
                    {
                        label: "Delivered",
                        data: barData.deliveredArr
                    },
                    {
                        label: "Canceled",
                        data: barData.canceledArr
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "right",
                        align: "center",
                        labels: {
                            boxWidth: 16,
                            padding: 12
                        }
                    }
                },
                layout: {
                    padding: {
                        right: 20
                    }
                }
            }
        });
    }

    function refreshPieAndLine() {
        if (!ordersPieChart || !revenueLineChart) {
            return;
        }

        var totalOrders = getNumberFromElement(metricsElements.total_orders);
        var delivered = getNumberFromElement(metricsElements.total_delivered);
        var canceled = getNumberFromElement(metricsElements.total_canceled);
        var open = getNumberFromElement(metricsElements.open_orders);
        var baseRevenue = getNumberFromElement(metricsElements.total_revenue);

        ordersPieChart.data.datasets[0].data = [delivered, canceled, open, totalOrders, baseRevenue];
        ordersPieChart.update();

        var lineData = buildRevenueLineData();
        revenueLineChart.data.labels = lineData.labels;
        revenueLineChart.data.datasets[0].data = lineData.revenueArr;
        revenueLineChart.update();
    }

    function refreshOrdersBarChart() {
        if (!ordersBarChart) {
            return;
        }
        var barData = buildOrdersBarData();
        ordersBarChart.data.labels = barData.labels;
        if (ordersBarChart.data.datasets[0]) {
            ordersBarChart.data.datasets[0].data = barData.totalOrdersArr;
        }
        if (ordersBarChart.data.datasets[1]) {
            ordersBarChart.data.datasets[1].data = barData.deliveredArr;
        }
        if (ordersBarChart.data.datasets[2]) {
            ordersBarChart.data.datasets[2].data = barData.canceledArr;
        }
        ordersBarChart.update();
    }

    function refreshCharts() {
        refreshPieAndLine();
        refreshOrdersBarChart();
    }

    function sendMetricUpdate(metricKey, metricValue) {
        var params = "metric_key=" + encodeURIComponent(metricKey) + "&metric_value=" + encodeURIComponent(metricValue);

        fetch("update_dashboard_metric.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: params
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (!data.success) {
                    alert(data.message || "Error while saving");
                    return;
                }
                if (metricKey === "total_orders") {
                    metricsElements.total_orders.textContent = metricValue;
                } else if (metricKey === "total_delivered") {
                    metricsElements.total_delivered.textContent = metricValue;
                } else if (metricKey === "total_canceled") {
                    metricsElements.total_canceled.textContent = metricValue;
                } else if (metricKey === "total_revenue") {
                    metricsElements.total_revenue.textContent = metricValue;
                } else if (metricKey === "open_orders") {
                    metricsElements.open_orders.textContent = metricValue;
                }
                recalcOpenOrders();
                refreshCharts();
                showSaveModal();
            })
            .catch(function () {
                alert("Network error");
            });
    }

    var formTotalOrders = document.getElementById("form-total-orders");
    var formTotalDelivered = document.getElementById("form-total-delivered");
    var formTotalCanceled = document.getElementById("form-total-canceled");
    var formTotalRevenue = document.getElementById("form-total-revenue");
    var formMonthlyOrders = document.getElementById("form-monthly-orders");
    var formMonthlyRevenue = document.getElementById("form-monthly-revenue");
    var formOpenOrders = document.getElementById("form-open-orders");

    if (formTotalOrders) {
        formTotalOrders.addEventListener("submit", function (e) {
            e.preventDefault();
            var value = document.getElementById("input-total-orders").value;
            if (value === "") {
                return;
            }
            sendMetricUpdate("total_orders", value);
        });
    }

    if (formTotalDelivered) {
        formTotalDelivered.addEventListener("submit", function (e) {
            e.preventDefault();
            var value = document.getElementById("input-total-delivered").value;
            if (value === "") {
                return;
            }
            sendMetricUpdate("total_delivered", value);
        });
    }

    if (formTotalCanceled) {
        formTotalCanceled.addEventListener("submit", function (e) {
            e.preventDefault();
            var value = document.getElementById("input-total-canceled").value;
            if (value === "") {
                return;
            }
            sendMetricUpdate("total_canceled", value);
        });
    }

    if (formTotalRevenue) {
        formTotalRevenue.addEventListener("submit", function (e) {
            e.preventDefault();
            var value = document.getElementById("input-total-revenue").value;
            if (value === "") {
                return;
            }
            sendMetricUpdate("total_revenue", value);
        });
    }

    if (formOpenOrders) {
        formOpenOrders.addEventListener("submit", function (e) {
            e.preventDefault();
            var value = document.getElementById("input-open-orders").value;
            if (value === "") {
                return;
            }
            sendMetricUpdate("open_orders", value);
        });
    }

    if (formMonthlyOrders) {
        formMonthlyOrders.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!window.monthlyOrdersData) {
                window.monthlyOrdersData = [];
            }

            var updated = [];
            var months = [];

            window.monthlyOrdersData.forEach(function (row) {
                months.push(row.month);
            });

            months.forEach(function (month) {
                var totalField = formMonthlyOrders.querySelector('input[name="total_orders[' + month + ']"]');
                var deliveredField = formMonthlyOrders.querySelector('input[name="delivered[' + month + ']"]');
                var canceledField = formMonthlyOrders.querySelector('input[name="canceled[' + month + ']"]');

                var totalVal = totalField ? parseInt(totalField.value, 10) : 0;
                var deliveredVal = deliveredField ? parseInt(deliveredField.value, 10) : 0;
                var canceledVal = canceledField ? parseInt(canceledField.value, 10) : 0;

                if (isNaN(totalVal)) totalVal = 0;
                if (isNaN(deliveredVal)) deliveredVal = 0;
                if (isNaN(canceledVal)) canceledVal = 0;

                updated.push({
                    month: month,
                    total_orders: totalVal,
                    delivered: deliveredVal,
                    canceled: canceledVal
                });
            });

            var params = "data=" + encodeURIComponent(JSON.stringify(updated));

            fetch("update_dashboard_monthly_orders.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.success) {
                        alert(data.message || "Error while saving monthly data");
                        return;
                    }
                    if (data.months) {
                        window.monthlyOrdersData = data.months;
                    } else {
                        window.monthlyOrdersData = updated;
                    }
                    refreshOrdersBarChart();
                    showSaveModal();
                })
                .catch(function () {
                    alert("Network error");
                });
        });
    }

    if (formMonthlyRevenue) {
        formMonthlyRevenue.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!window.monthlyRevenueData) {
                window.monthlyRevenueData = [];
            }

            var updated = [];
            var months = [];

            window.monthlyRevenueData.forEach(function (row) {
                months.push(row.month);
            });

            months.forEach(function (month) {
                var revenueField = formMonthlyRevenue.querySelector('input[name="revenue[' + month + ']"]');
                var revenueVal = revenueField ? parseInt(revenueField.value, 10) : 0;
                if (isNaN(revenueVal)) revenueVal = 0;

                updated.push({
                    month: month,
                    revenue: revenueVal
                });
            });

            var params = "data=" + encodeURIComponent(JSON.stringify(updated));

            fetch("update_dashboard_monthly_revenue.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.success) {
                        alert(data.message || "Error while saving monthly revenue");
                        return;
                    }
                    if (data.months) {
                        window.monthlyRevenueData = data.months;
                    } else {
                        window.monthlyRevenueData = updated;
                    }
                    refreshPieAndLine();
                    showSaveModal();
                })
                .catch(function () {
                    alert("Network error");
                });
        });
    }

    recalcOpenOrders();
    initCharts();
});
