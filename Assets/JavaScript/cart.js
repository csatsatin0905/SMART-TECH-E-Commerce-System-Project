  function changeQty(btn, amount) {
      let qtySpan = btn.parentElement.querySelector('.qty-value');
      let qty = parseInt(qtySpan.textContent);
      qty = Math.max(1, qty + amount);
      qtySpan.textContent = qty;
      
      let row = btn.closest('tr');
      let unitPrice = 5000;
      row.querySelector('.total-price').textContent = '₱' + (unitPrice * qty).toLocaleString();
    }
