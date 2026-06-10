 // Initial Chart Generation Setup
  new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May'],
      datasets: [
        {
          label: 'Sales',
          data: [38000, 45000, 52000, 61000, 88500],
          backgroundColor: '#4E0B99',
          borderRadius: 7,
          borderSkipped: false,
          barPercentage: 0.55
        },
        {
          label: 'Target',
          data: [50000, 50000, 60000, 70000, 80000],
          backgroundColor: '#e0d4ff',
          borderRadius: 7,
          borderSkipped: false,
          barPercentage: 0.55
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ' ₱' + ctx.raw.toLocaleString() } }
      },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
        y: {
          grid: { color: '#f5f0ff' },
          ticks: {
            font: { size: 11 },
            color: '#9ca3af',
            callback: v => '₱' + (v / 1000).toFixed(0) + 'k'
          }
        }
      }
    }
  });



// Log out function
function handleLogout() {
  // Closing profile menu popover
  const profileMenu = document.getElementById('profileMenu');
  if (profileMenu) profileMenu.classList.remove('show');

  // Show custom logout modal
  document.getElementById('logoutModal').classList.add('show');
}

 // 'Cancel' o 'X' - Close Modal
function closeLogout() {
  document.getElementById('logoutModal').classList.remove('show');
}

 // Log-out destination redirection
function confirmLogoutAction() {
  window.location.href = '../Admin/adminLog-in.php';
}