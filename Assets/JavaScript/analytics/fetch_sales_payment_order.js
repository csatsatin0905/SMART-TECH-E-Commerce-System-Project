let salesPaymentOrderChart;

let salesPaymentOrderFilterOption = {
    group_by: 'province',
    value_by: 'sales_amount',
    category: '',
    time_filter: '',
};

const selectSalesPaymentOrder = document.querySelectorAll('select[data-chart="sales_payment_order"]');

async function fetchSalesPaymentOrder() {
    const formData = new FormData();
    for (const key in salesPaymentOrderFilterOption) {
        formData.append(key, salesPaymentOrderFilterOption[key]);
        console.log(key, salesPaymentOrderFilterOption[key]);
    }
    try {
        const response = await fetch('../Actions/Analytics/sales_by_payment_order.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Sales Payment Order Data:', data);
        renderSalesPaymentOrder(data);
    } catch (error) {
        console.error('Error fetching sales payment order data:', error);
    }
}

async function renderSalesPaymentOrder(data) {
    if (salesPaymentOrderChart) {
        salesPaymentOrderChart.destroy();
    }
    const ctx = document.getElementById('salesPaymentOrderChart').getContext('2d');
    salesPaymentOrderChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => item.chart_label),
            datasets: [{
                label: 'Total Sales',
                data: data.map(item => item.chart_value),
                backgroundColor: backgroundColors.slice(0, data.length),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
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


selectSalesPaymentOrder.forEach((select) => {
    select.addEventListener('change', (e) => {
        const filterType = e.target.dataset.filter;
        salesPaymentOrderFilterOption[filterType] = e.target.value;
        console.log(salesPaymentOrderFilterOption);
        fetchSalesPaymentOrder();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    fetchSalesPaymentOrder();
});