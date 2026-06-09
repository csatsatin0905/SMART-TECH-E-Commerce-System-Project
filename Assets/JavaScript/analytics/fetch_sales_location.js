let salesLocationChart;

let salesLocationFilterOption = {
    group_by: 'province',
    category: '',
    orderStatus: '',
    paymentMethod: '',
    gender: '',
};

const selectSalesLocation = document.querySelectorAll('select[data-chart="sales_location"]');

async function fetchSalesLocation() {
    const formData = new FormData();
    for (const key in salesLocationFilterOption) {
        formData.append(key, salesLocationFilterOption[key]);
        console.log(key, salesLocationFilterOption[key]);
    }
    try {
        const response = await fetch('../Actions/Analytics/sales_by_location.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Sales Location Data:', data);
        renderSalesLocation(data);
    } catch (error) {
        console.error('Error fetching sales location data:', error);
    }
}

let backgroundColors = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
]; 

async function renderSalesLocation(data) {
    if (salesLocationChart) {
        salesLocationChart.destroy();
    }
    const ctx = document.getElementById('salesLocationChart').getContext('2d');
    salesLocationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => item.location_group),
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


selectSalesLocation.forEach((select) => {
    select.addEventListener('change', (e) => {
        const filterType = e.target.dataset.filter;
        salesLocationFilterOption[filterType] = e.target.value;
        console.log(salesLocationFilterOption);
        fetchSalesLocation();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    fetchSalesLocation();
});