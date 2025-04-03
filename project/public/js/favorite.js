document.addEventListener('DOMContentLoaded', function() {
    const wishlistCount = document.getElementById('wishlist-count');
    
    // Initialize wishlist from local storage
    function initializeWishlist() {
        const wishlist = JSON.parse(localStorage.getItem('gameWishlist') || '[]');
        wishlistCount.textContent = wishlist.length;
        
        // Update wishlist icons on page load
        document.querySelectorAll('.wish-btn').forEach(btn => {
            const gameCard = btn.closest('.game-card');
            const gameName = gameCard.querySelector('h3').textContent;
            
            if (wishlist.includes(gameName)) {
                btn.classList.add('active');
                btn.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                btn.classList.remove('active');
                btn.innerHTML = '<i class="far fa-heart"></i>';
            }
        });
    }

    // Toggle wishlist
    function toggleWishlist(btn, gameName) {
        // Get current wishlist from local storage
        let wishlist = JSON.parse(localStorage.getItem('gameWishlist') || '[]');
        
        // Check if game is already in wishlist
        const gameIndex = wishlist.indexOf(gameName);
        
        if (gameIndex === -1) {
            // Add to wishlist
            wishlist.push(gameName);
            btn.classList.add('active');
            btn.innerHTML = '<i class="fas fa-heart"></i>';
            
            // Show success toast
            Swal.fire({
                title: 'Ajouté aux favoris',
                text: `${gameName} a été ajouté à vos favoris`,
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            // Remove from wishlist
            wishlist.splice(gameIndex, 1);
            btn.classList.remove('active');
            btn.innerHTML = '<i class="far fa-heart"></i>';
            
            // Show remove toast
            Swal.fire({
                title: 'Retiré des favoris',
                text: `${gameName} a été retiré de vos favoris`,
                icon: 'info',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        // Save updated wishlist to local storage
        localStorage.setItem('gameWishlist', JSON.stringify(wishlist));
        
        // Update wishlist count
        wishlistCount.textContent = wishlist.length;
    }

    // Show wishlist modal
    function showWishlist() {
        const wishlist = JSON.parse(localStorage.getItem('gameWishlist') || '[]');
        
        if (wishlist.length === 0) {
            Swal.fire({
                title: 'Favoris vides',
                text: 'Vous n\'avez pas encore ajouté de jeux à vos favoris',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Create wishlist content
        const wishlistContent = wishlist.map(gameName => {
            // Find the game card with matching name
            const gameCard = Array.from(document.querySelectorAll('.game-card'))
                .find(card => card.querySelector('h3').textContent === gameName);
            
            if (!gameCard) return '';
            
            const gameImage = gameCard.querySelector('img').src;
            const gamePrice = gameCard.querySelector('.text-purple-600').textContent;
            const gameGenre = gameCard.querySelector('.game-badge').textContent;
            
            return `
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg mb-3">
                    <div class="flex items-center">
                        <img src="${gameImage}" alt="${gameName}" class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h4 class="font-semibold">${gameName}</h4>
                            <p class="text-sm text-gray-500">${gameGenre}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-purple-600 font-bold mr-4">${gamePrice}</span>
                        <button onclick="removeFromWishlist('${gameName}')" class="text-red-500 hover:text-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
        
        Swal.fire({
            title: 'Mes Favoris',
            html: `
                <div class="max-h-[400px] overflow-y-auto">
                    ${wishlistContent}
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px'
        });
    }

    // Remove from wishlist (used in wishlist modal)
    function removeFromWishlist(gameName) {
        // Get current wishlist
        let wishlist = JSON.parse(localStorage.getItem('gameWishlist') || '[]');
        
        // Remove game
        const updatedWishlist = wishlist.filter(name => name !== gameName);
        
        // Save updated wishlist
        localStorage.setItem('gameWishlist', JSON.stringify(updatedWishlist));
        
        // Update wishlist count
        wishlistCount.textContent = updatedWishlist.length;
        
        // Update wishlist button on game card
        const gameCard = Array.from(document.querySelectorAll('.game-card'))
            .find(card => card.querySelector('h3').textContent === gameName);
        
        if (gameCard) {
            const wishBtn = gameCard.querySelector('.wish-btn');
            wishBtn.classList.remove('active');
            wishBtn.innerHTML = '<i class="far fa-heart"></i>';
        }
        
        // Refresh wishlist modal
        showWishlist();
    }

    // Attach event listeners to wishlist buttons
    document.querySelectorAll('.wish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const gameCard = this.closest('.game-card');
            const gameName = gameCard.querySelector('h3').textContent;
            toggleWishlist(this, gameName);
        });
    });

    // Global functions for modal use
    window.showWishlist = showWishlist;
    window.removeFromWishlist = removeFromWishlist;

    // Initialize wishlist on page load
    initializeWishlist();
});