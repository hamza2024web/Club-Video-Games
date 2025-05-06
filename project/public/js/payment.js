// Payment functionality
let cartTotal = 0;

function checkout() {
    if (cart.length === 0) return;
    
    const paymentModal = document.getElementById('payment-modal');
    const paymentContent = document.getElementById('payment-content');
    const overlay = document.getElementById('overlay');
    const itemsCountElement = document.getElementById('payment-items-count');
    const totalElement = document.getElementById('payment-total');
    const buttonTotalElement = document.getElementById('payment-button-total');
    document.getElementById('payment-paid-notice').classList.remove('hidden');
    
    // Calculate total
    const subtotal = cart.reduce((sum, item) => sum + item.price, 0);
    const tax = subtotal * 0.2;
    const total = subtotal + tax;
    
    // Stockez le total dans la variable globale
    cartTotal = total;
    
    // Update payment modal info
    itemsCountElement.textContent = cart.length;
    totalElement.textContent = `${total.toFixed(2)} €`;
    buttonTotalElement.textContent = `${total.toFixed(2)} €`;
    
    // Show payment modal
    paymentModal.classList.remove('hidden');
    overlay.classList.remove('hidden');
    
    // Animation
    setTimeout(() => {
        paymentContent.classList.remove('scale-95', 'opacity-0');
        paymentContent.classList.add('scale-100', 'opacity-100');
    }, 10);
            
    // Hide cart
    document.getElementById('shopping-cart').classList.add('translate-x-full');
}
        
function closePaymentModal() {
    const paymentModal = document.getElementById('payment-modal');
    const paymentContent = document.getElementById('payment-content');
    const overlay = document.getElementById('overlay');
    
    // Animation
    paymentContent.classList.remove('scale-100', 'opacity-100');
    paymentContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
paymentModal.classList.add('hidden');

// Only hide overlay if the cart is also closed
if (document.getElementById('shopping-cart').classList.contains('translate-x-full')) {
    overlay.classList.add('hidden');
}
    }, 300);
}
        
function processPayment(storageKey) {
    // Simulate payment processing
    const paymentModal = document.getElementById('payment-modal');
    const overlay = document.getElementById('overlay');
    
    Swal.fire({
        title: 'Traitement du paiement',
        text: 'Veuillez patienter...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get the cart items directly from the current cart in memory
    // No need to reload from localStorage as we're working with the active cart
    if (cart && cart.length > 0) {
        // Map cart items to the format needed for the order
        let orderDetails = cart.map(item => ({
            game_id: item.id,
            order_id: item.order_id, 
            price: item.price
        }));
                
        // Create a hidden form to submit the data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/payer';
        form.style.display = 'none';
        
        // Add a hidden field for game IDs
        const gameIdsInput = document.createElement('input');
        gameIdsInput.type = 'hidden';
        gameIdsInput.name = 'games_id';
        gameIdsInput.value = JSON.stringify(orderDetails.map(item => item.game_id));
        form.appendChild(gameIdsInput);
        
        // Add a hidden field for order IDs
        const orderIdsInput = document.createElement('input');
        orderIdsInput.type = 'hidden';
        orderIdsInput.name = 'order_id';
        orderIdsInput.value = JSON.stringify(orderDetails.map(item => item.order_id));
        form.appendChild(orderIdsInput);

        // Add a hidden field for individual prices
        const priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'price';
        priceInput.value = JSON.stringify(orderDetails.map(item => item.price));
        form.appendChild(priceInput);
        
        // Add a hidden field for the total amount
        const totalAmountInput = document.createElement('input');
        totalAmountInput.type = 'hidden';
        totalAmountInput.name = 'total_amount';
        totalAmountInput.value = cartTotal.toFixed(2);
        form.appendChild(totalAmountInput);
        
        // Add the form to the document
        document.body.appendChild(form);
        
        // Submit the form
        form.submit();
        
        // After submission, clear the cart
        cart.length = 0;
        saveCartToLocalStorage(storageKey);
        updateCartUI();
    } else {
        console.log("Le panier est vide");
        
        // Show error message for empty cart
        setTimeout(() => {
            Swal.fire({
                title: 'Panier vide',
                text: 'Veuillez ajouter des jeux à votre panier',
                icon: 'error',
                confirmButtonColor: '#8b5cf6'
            }).then(() => {
                // Close payment modal
                paymentModal.classList.add('hidden');
                overlay.classList.add('hidden');
            });
        }, 1000);
    }
}

