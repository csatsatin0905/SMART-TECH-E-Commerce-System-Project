async function changeQty(btn, amount, stock) {
  let qtySpan = btn.parentElement.querySelector('.qty-value');
  let qty = parseInt(qtySpan.textContent);
  qty = Math.max(1, Math.min(qty + amount, stock));
  btn.parentElement.querySelectorAll('.qty-btn').forEach(button => {
    button.disabled = (qty <= 1 && button.textContent === '–') || (qty >= stock && button.textContent === '+');
  });
  qtySpan.textContent = qty;

  let row = btn.closest('tr');
  let unitPrice = row.querySelector('.unit-price').textContent.replace('₱', '').replace(/,/g, '');
  unitPrice = parseFloat(unitPrice);
  let totalPrice = unitPrice * qty;
  row.querySelector('.total-price').textContent =
    '₱' + totalPrice.toLocaleString('en-PH', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

  try {
    const formData = new FormData();
    formData.append('product_id', row.id);
    formData.append('quantity', qty);
    const response = await fetch('Actions/Product/update-quantity.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (!result.success) {
      alert('Failed to update cart. Please try again.'); //TO CHANGE INTO SWEET ALERT
    }
  } catch (error) {
    console.error('Error updating cart:', error);
    alert('An error occurred while updating the cart. Please try again.'); //TO CHANGE INTO SWEET ALERT
  }
}

async function deleteCart(cartId) {
  try {
    const formData = new FormData();
    formData.append('cart_id', cartId);
    const response = await fetch('Actions/Product/delete-product-cart.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.success) {
      window.location.reload();
    }
  } catch (error) {
    console.error('Error updating cart:', error);
    alert('An error occurred while updating the cart. Please try again.'); //TO CHANGE INTO SWEET ALERT
  }
}
