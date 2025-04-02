const cart = [];

function addToCart(name, price, image) {
    // Check if the game is already in the cart
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        // Show notification that the game is already in the cart
        Swal.fire({
            title: 'Déjà dans le panier',
            text: `${name} est déjà dans votre panier`,
            icon: 'info',
            confirmButtonColor: '#8b5cf6',
            timer: 2000,
            timerProgressBar: true
        });
        return;
    }
    price = Number(price);
    
    // Add to cart
    cart.push({
        name,
        price,
        image,
        id: Date.now() // Simple unique identifier
    });
    
    // Update cart UI
    updateCartUI();
    saveCartToLocalStorage();
    
    // Show success notification
    Swal.fire({
        title: 'Ajouté au panier',
        text: `${name} a été ajouté à votre panier`,
        icon: 'success',
        confirmButtonColor: '#8b5cf6',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}
function removeFromCart(id) {
    const index = cart.findIndex(item => item.id === id);
    if (index !== -1) {
        const removedItem = cart[index];
        cart.splice(index, 1);
        updateCartUI();
        saveCartToLocalStorage();
        
        // Show notification
        Swal.fire({
            title: 'Retiré du panier',
            text: `${removedItem.name} a été retiré de votre panier`,
            icon: 'info',
            confirmButtonColor: '#8b5cf6',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }
}

function clearCart() {
    if (cart.length === 0) return;
    
    Swal.fire({
        title: 'Vider le panier?',
        text: 'Êtes-vous sûr de vouloir supprimer tous les jeux du panier?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#d1d5db',
        confirmButtonText: 'Oui, vider',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            cart.length = 0;
            updateCartUI();
            saveCartToLocalStorage();
        }
    });
}

function updateCartUI() {
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const totalElement = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const cartCountElement = document.getElementById('cart-count');
    
    // Vérifiez si tous les éléments nécessaires existent
    if (!cartItemsContainer || !subtotalElement || !taxElement || !totalElement || !cartCountElement || !checkoutBtn) {
        console.error("Certains éléments du panier n'ont pas été trouvés dans le DOM");
        return;
    }
    
    // Update cart count badge
    cartCountElement.textContent = cart.length;
    
    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + item.price, 0);
    const tax = subtotal * 0.2; // 20% VAT
    const total = subtotal + tax;
    
    // Update price displays
    subtotalElement.textContent = `${subtotal.toFixed(2)} €`;
    taxElement.textContent = `${tax.toFixed(2)} €`;
    totalElement.textContent = `${total.toFixed(2)} €`;
    
    // Enable/disable checkout button
    checkoutBtn.disabled = cart.length === 0;
    
    // Show/hide empty cart message
    if (cart.length === 0) {
        if (emptyCartMessage) {
            emptyCartMessage.classList.remove('hidden');
            cartItemsContainer.innerHTML = '';
            cartItemsContainer.appendChild(emptyCartMessage);
        } else {
            cartItemsContainer.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-shopping-cart text-4xl mb-3"></i><p>Votre panier est vide</p><p class="text-sm mt-2">Ajoutez des jeux pour commencer vos achats</p></div>';
        }
        return;
    } else {
        if (emptyCartMessage) {
            emptyCartMessage.classList.add('hidden');
        }
    }
    
    // Rebuild cart items
    cartItemsContainer.innerHTML = '';
    
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item flex items-center p-3 border border-gray-100 rounded-lg';
        
        // Vérifier si l'URL de l'image commence par 'http' ou non
        const imgSrc = item.image.startsWith('http') ? item.image : `/${item.image}`;
        
        cartItem.innerHTML = `
            <img src="${imgSrc}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md">
            <div class="ml-3 flex-1">
                <h4 class="font-medium text-gray-800">${item.name}</h4>
                <p class="text-purple-600 font-semibold">${item.price === 0 ? 'Gratuit' : item.price.toFixed(2) + ' €'}</p>
            </div>
            <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500">
                <i class="fas fa-trash"></i>
            </button>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
}