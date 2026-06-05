 function changeQty(amount) {
      let qty = document.getElementById('quantity');
      let newValue = parseInt(qty.textContent) + amount;
      if (newValue >= 1) qty.textContent = newValue;
    }

    function addToCart() {
      showAddedPopup();
    }

    function showAddedPopup() {
      const popup = document.getElementById('addedToCartPopup');
      popup.classList.add('show');
      
      setTimeout(() => {
        popup.classList.remove('show');
      }, 2500);
    }
