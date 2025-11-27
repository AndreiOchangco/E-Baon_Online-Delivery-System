const ctx = document.getElementById('salesChart').getContext('2d');

// Get current month (0–11)
const currentMonth = new Date().getMonth();

const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Monthly Orders',
            data: Array(12).fill(0),
            backgroundColor: Array(12).fill('rgba(6, 95, 70, 0.7)').map((color, index) => 
                index === currentMonth ? 'rgba(255, 99, 132, 0.8)' : color),
            borderColor: Array(12).fill('rgba(6, 95, 70, 1)').map((color, index) =>
                index === currentMonth ? 'rgba(255, 99, 132, 1)' : color),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        // Smooth animation on update
        animation: {
            duration: 800,
            easing: 'easeInOutQuart'
        },

        scales: {
            y: { 
                beginAtZero: true,
                ticks: { stepSize: 1 } 
            }
        },

        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: (context) => context.dataset.label + ': ' + context.raw + ' orders'
                }
            },
            title: { 
                display: true, 
                text: 'Monthly Orders Overview (' + new Date().getFullYear() + ')' 
            }
        }
    }
});

// Fetch and update chart data with animation
function updateChart() {
    fetch('fetch_orders.php')
        .then(response => response.json())
        .then(data => {
            salesChart.data.datasets[0].data = data;

            // Highlight current month dynamically
            salesChart.data.datasets[0].backgroundColor = data.map((_, index) =>
                index === currentMonth ? 'rgba(255, 99, 132, 0.8)' : 'rgba(6, 95, 70, 0.7)'
            );
            salesChart.data.datasets[0].borderColor = data.map((_, index) =>
                index === currentMonth ? 'rgba(255, 99, 132, 1)' : 'rgba(6, 95, 70, 1)'
            );

            salesChart.update();
        })
        .catch(err => console.error('Failed to fetch data:', err));
}

// Initial load
updateChart();

// Refresh every 10 seconds with animation
setInterval(updateChart, 10000);