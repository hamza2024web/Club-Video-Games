// Payment functionality
function checkout() {
    if (cart.length === 0) return;
    
    const paymentModal = document.getElementById('payment-modal');
    const paymentContent = document.getElementById('payment-content');
    const overlay = document.getElementById('overlay');
    const itemsCountElement = document.getElementById('payment-items-count');
    const totalElement = document.getElementById('payment-total');
    const buttonTotalElement = document.getElementById('payment-button-total');
    
    // Calculate total
    const subtotal = cart.reduce((sum, item) => sum + item.price, 0);
    const tax = subtotal * 0.2;
    const total = subtotal + tax;
    
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
        
function processPayment() {
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
            
// Simulate payment delay
setTimeout(() => {
    Swal.fire({
        title: 'Paiement réussi!',
        text: 'Vos jeux sont maintenant disponibles pour vos tournois',
        icon: 'success',
        confirmButtonColor: '#8b5cf6'
    }).then(() => {
        // Clear cart
        cart.length = 0;
        updateCartUI();
        saveCartToLocalStorage();
        
        // Close payment modal
        paymentModal.classList.add('hidden');
        overlay.classList.add('hidden');
    });
}, 2000);
}