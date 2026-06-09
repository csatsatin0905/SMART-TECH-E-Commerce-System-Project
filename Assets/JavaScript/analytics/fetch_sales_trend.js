let salesTrendChart;

let salesFilterOption = {
    group_by: 'month',
    paymentMethod: '',
    orderStatus: '',
    category: '',
};

const selectSales = document.querySelectorAll('select[data-chart="sales"]');

async function fetchSalesTrend() {
    const formData = new FormData();
    for (const key in salesFilterOption) {
        formData.append(key, salesFilterOption[key]);
    }
    try {
        const response = await fetch('../Actions/Analytics/sales_trend.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Sales Trend Data:', data);
        renderSalesTrend(data);
    } catch (error) {
        console.error('Error fetching sales trend data:', error);
    }
}

async function renderSalesTrend(data) {
    if (salesTrendChart) {
        salesTrendChart.destroy();
    }
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.sales_period),
            datasets: [{
                label: 'Total Sales',
                data: data.map(item => item.total_sales),
                backgroundColor: 'rgba(75, 192, 192, 1)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}


selectSales.forEach((select) => {
    select.addEventListener('change', (e) => {
        const filterType = e.target.dataset.filter;
        salesFilterOption[filterType] = e.target.value;
        fetchSalesTrend();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    fetchSalesTrend();
});