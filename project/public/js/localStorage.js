        // Initialize the cart UI on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadCartFromLocalStorage();
            updateCartUI();
            
            // Close cart when clicking on overlay
            document.getElementById('overlay').addEventListener('click', () => {
                document.getElementById('shopping-cart').classList.add('translate-x-full');
                document.getElementById('payment-modal').classList.add('hidden');
                document.getElementById('overlay').classList.add('hidden');
            });
        });
        function saveCartToLocalStorage(){
            localStorage.setItem('gamersHubCart',JSON.stringify(cart));
        }
        function loadCartFromLocalStorage (){
            const savedCart = localStorage.getItem('gamersHubCart');
            if (savedCart){
                cart.length = 0;
                const loadedCart = JSON.parse(savedCart);
                loadedCart.forEach(item => cart.push(item));
            }
        }