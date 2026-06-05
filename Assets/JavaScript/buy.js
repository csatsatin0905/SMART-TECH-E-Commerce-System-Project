
function placeOrder() {
      showOrderPlacedPopup();
    }

    function showOrderPlacedPopup() {
      const popup = document.getElementById('orderPlacedPopup');
      popup.classList.add('show');
      
      setTimeout(() => {
        popup.classList.remove('show');
      }, 3000);
    }

    // Payment Selection Function
    function selectPayment(btn) {
      if (btn.classList.contains('disabled')) return; // Prevent clicking disabled buttons

      // Remove active from all buttons
      document.querySelectorAll('.payment-btn').forEach(button => {
        button.classList.remove('active');
      });

      // Add active to clicked button
      btn.classList.add('active');
    }



