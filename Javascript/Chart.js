const ctx = document.getElementById('salesChart').getContext('2d');

// Initialize empty chart
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Monthly Orders',
            data: Array(12).fill(0), // start empty
            backgroundColor: 'rgba(6, 95, 70, 0.7)',
            borderColor: 'rgba(6, 95, 70, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        },
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Monthly Orders Overview (' + new Date().getFullYear() + ')' }
        }
    }
});

// Function to fetch latest data from server
function updateChart() {
    fetch('../Main/fetch_orders.php')
        .then(response => response.json())
        .then(data => {
            salesChart.data.datasets[0].data = data;
            salesChart.update();
        })
        .catch(err => console.error('Failed to fetch data:', err));
}

// Initial fetch
updateChart();

// Update chart every 10 seconds
setInterval(updateChart, 10000);