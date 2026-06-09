let salesProductChart;

let salesProductFilterOption = {
    group_by: 'category',
    orderStatus: '',
    time_filter: '',
};

const selectSalesProduct = document.querySelectorAll('select[data-chart="sales_product"]');

async function fetchSalesProduct() {
    const formData = new FormData();
    for (const key in salesProductFilterOption) {
        formData.append(key, salesProductFilterOption[key]);
        console.log(key, salesProductFilterOption[key]);
    }
    try {
        const response = await fetch('../Actions/Analytics/sales_by_product.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Sales Product Data:', data);
        renderSalesProduct(data);
    } catch (error) {
        console.error('Error fetching sales product data:', error);
    }
}

async function renderSalesProduct(data) {
    if (salesProductChart) {
        salesProductChart.destroy();
    }
    const ctx = document.getElementById('salesProductChart').getContext('2d');
    salesProductChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => item.product_group),
            datasets: [{
                label: 'Total Sales',
                data: data.map(item => item.total_sales),
                backgroundColor: backgroundColors.slice(0, data.length),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
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


selectSalesProduct.forEach((select) => {
    select.addEventListener('change', (e) => {
        const filterType = e.target.dataset.filter;
        salesProductFilterOption[filterType] = e.target.value;
        console.log(salesProductFilterOption);
        fetchSalesProduct();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    fetchSalesProduct();
});