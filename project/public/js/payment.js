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
    
    // Charger le panier depuis le localStorage
    let cartItems = loadCartFromLocalStorage();
    
    if (cartItems && cartItems.length > 0) {
        // Vous pouvez également créer un objet combiné si nécessaire
        let orderDetails = cartItems.map(item => ({
            game_id: item.id,
            order_id: item.order_id
        }));
                
        // Créer un formulaire caché pour soumettre les données
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/payer';
        form.style.display = 'none';
        
        // Ajouter un champ caché pour les IDs des jeux
        const gameIdsInput = document.createElement('input');
        gameIdsInput.type = 'hidden';
        gameIdsInput.name = 'games_id';
        gameIdsInput.value = JSON.stringify(orderDetails.map(item => item.game_id));
        form.appendChild(gameIdsInput);
        
        // Ajouter un champ caché pour les order_IDs
        const orderIdsInput = document.createElement('input');
        orderIdsInput.type = 'hidden';
        orderIdsInput.name = 'order_id';
        orderIdsInput.value = JSON.stringify(orderDetails.map(item => item.order_id));
        form.appendChild(orderIdsInput);
        
        // Ajouter le formulaire au document
        document.body.appendChild(form);
        
        // Soumettre le formulaire
        form.submit();
    } else {
        console.log("Le panier est vide");
        
        // Simulate payment delay
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

// Fonction pour charger le panier depuis le localStorage
function loadCartFromLocalStorage() {
    const savedCart = localStorage.getItem('gamersHubCart');
    if (savedCart) {
        return JSON.parse(savedCart);
    }
    return [];
}