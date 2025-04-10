// Initialize the cart UI on page load
document.addEventListener('DOMContentLoaded', () => {

    let currentStorageKey;

    if (window.location.pathname.includes('/member/')) {
        currentStorageKey = 'gamersMember';
    } else  {
        currentStorageKey = 'gamersHubCart';
    } 

    document.body.dataset.storageKey = currentStorageKey;

    loadCartFromLocalStorage(currentStorageKey);
    updateCartUI();
            
    // Close cart when clicking on overlay
    document.getElementById('overlay').addEventListener('click', () => {
    document.getElementById('shopping-cart').classList.add('translate-x-full');
    document.getElementById('payment-modal').classList.add('hidden');
    document.getElementById('overlay').classList.add('hidden');
    });
});

function saveCartToLocalStorage(storageKey) {
    localStorage.setItem(storageKey, JSON.stringify(cart));
}

function loadCartFromLocalStorage(storageKey) {
    const savedCart = localStorage.getItem(storageKey);
    if (savedCart) {
        cart.length = 0; // Clear current cart
        const loadedCart = JSON.parse(savedCart);
        loadedCart.forEach(item => cart.push(item));
    }
}