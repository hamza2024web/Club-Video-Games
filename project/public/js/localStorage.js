// Initialize the cart UI on page load
document.addEventListener('DOMContentLoaded', () => {
        loadCartFromLocalStorage(identifier);
        updateCartUI();
            
        // Close cart when clicking on overlay
        document.getElementById('overlay').addEventListener('click', () => {
        document.getElementById('shopping-cart').classList.add('translate-x-full');
        document.getElementById('payment-modal').classList.add('hidden');
        document.getElementById('overlay').classList.add('hidden');
    });
});
function saveCartToLocalStorage(identifier){
    if (identifier == 2){
        localStorage.setItem('gamersMember',JSON.stringify(cart));    
    } 
    if (identifier == 1){
        localStorage.setItem('gamersHubCart',JSON.stringify(cart));
    }
}
function loadCartFromLocalStorage (identifier){
    const savedCartOrgan = localStorage.getItem('gamersHubCart');
    const savedCartMem = localStorage.getItem('gamersMember');
    if (identifier == 1) {
        if (savedCartOrgan){
            cart.length = 0;
            const loadedCart = JSON.parse(savedCartOrgan);
            loadedCart.forEach(item => cart.push(item));
        }
    } 
    if (identifier == 2){
        if (savedCartMem){
            cart.length = 0;
            const loadedCart = JSON.parse(savedCartMem);
            loadedCart.forEach(item => cart.push(item));
        }
    }

}