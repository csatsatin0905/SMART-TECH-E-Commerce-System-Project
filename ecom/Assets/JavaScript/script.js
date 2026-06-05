/* =========================================
   1. LOGOUT MODAL SYSTEM
========================================= */

function logoutUser() {
    const modal = document.getElementById("logoutModal");
    if (modal) {
        modal.style.display = "flex";
    }
}

function closeLogoutModal() {
    const modal = document.getElementById("logoutModal");
    if (modal) {
        modal.style.display = "none";
    }
}

function confirmLogout() {
    window.location.href = "log-in.html";
}

function goBack() {
    window.history.back();
}

/* =========================================
   2. SHOPPING CART SYSTEM
========================================= */

let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Update the counter on page load
updateCartCount();

function addToCart(name, price, image) {
    let item = cart.find(p => p.name === name);
    
    if (item) {
        item.qty++;
    } else {
        cart.push({
            name,
            price,
            image,
            qty: 1
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    
    // Check if the addedToCartPopup exists (from your GPU page)
    const popup = document.getElementById('addedToCartPopup');
    if (popup) {
        popup.classList.add('show');
        setTimeout(() => popup.classList.remove('show'), 2500);
    } else {
        alert(name + " added to cart!");
    }
}

function updateCartCount() {
    let count = cart.reduce((total, item) => total + item.qty, 0);
    let cartCount = document.getElementById("cartCount");
    
    if (cartCount) {
        cartCount.innerText = count;
    }
}

function displayCart() {
    let container = document.getElementById("cartItems");
    let total = 0;

    if (!container) return; // Stop if not on the cart page
    
    container.innerHTML = "";

    cart.forEach((item, index) => {
        total += item.price * item.qty;

        container.innerHTML += `
        <div class="cart-item">
            <div class="cart-left">
                <img src="${item.image}">
                <div>
                    <h3>${item.name}</h3>
                    <p>₱${item.price}</p>
                </div>
            </div>
            
            <div class="cart-controls">
                <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
                <span>${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
            </div>
        </div>
        `;
    });

    document.getElementById("cartTotal").innerText = total;
}

function changeQty(index, amount) {
    cart[index].qty += amount;
    
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
    updateCartCount();
}

function removeItem(index) {
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
    updateCartCount();
}

function goCheckout() {
    if (cart.length === 0) {
        alert("Cart is empty!");
        return;
    }
    window.location.href = "order.html";
}


/* =========================================
   3. GLOBAL SEARCH SYSTEM
========================================= */


window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q'); // Looks for ?q=word in the URL
    const searchInput = document.getElementById("searchInput");

    if (query && searchInput) {
        searchInput.value = query; 
        searchProduct();           
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                let word = searchInput.value.trim();
                
                if (word !== "") {
                    if (!window.location.href.includes("shop.html")) {
                        window.location.href = `shop.html?q=${encodeURIComponent(word)}`;
                    } else {
                        searchProduct();
                    }
                }
            }
        });
    }
});

function searchProduct() {
    let inputBox = document.getElementById("searchInput");
    if (!inputBox) return; // Stop if there's no search bar on this page

    let input = inputBox.value.toLowerCase();
    
    // Grabs both product cards and category cards
    let cards = document.querySelectorAll(".product-card, .category-card");

    cards.forEach(card => {
        let text = card.innerText.toLowerCase();
        
        if (text.includes(input)) {
            card.style.display = ""; // Shows the item
        } else {
            card.style.display = "none"; // Hides the item
        }
    });
}

//  --CART JSCRIPT-- 
    function changeQty(btn, amount) {
      let qtySpan = btn.parentElement.querySelector('.qty-value');
      let qty = parseInt(qtySpan.textContent);
      qty = Math.max(1, qty + amount);
      qtySpan.textContent = qty;
      
      let row = btn.closest('tr');
      let unitPrice = 5000;
      row.querySelector('.total-price').textContent = '₱' + (unitPrice * qty).toLocaleString();
    }



