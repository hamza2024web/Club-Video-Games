document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const gameCards = document.querySelectorAll('.game-card');
    const filterButtons = document.querySelectorAll('.filter-button');
    const sortSelect = document.getElementById('sort-games');

    // Search functionality
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // Toggle clear search button visibility
        clearSearchBtn.classList.toggle('hidden', searchTerm === '');
        
        gameCards.forEach(card => {
            const gameName = card.querySelector('h3').textContent.toLowerCase();
            const gameDescription = card.querySelector('p.text-gray-600').textContent.toLowerCase();
            const gameGenre = card.querySelector('.game-badge').textContent.toLowerCase();
            
            const isVisible = gameName.includes(searchTerm) || 
                              gameDescription.includes(searchTerm) || 
                              gameGenre.includes(searchTerm);
            
            card.style.display = isVisible ? 'block' : 'none';
        });
    }

    // Clear search input
    function clearSearch() {
        searchInput.value = '';
        clearSearchBtn.classList.add('hidden');
        gameCards.forEach(card => {
            card.style.display = 'block';
        });
    }

    // Filter by genre
    function filterByGenre(genre) {
        gameCards.forEach(card => {
            const cardGenre = card.querySelector('.game-badge').textContent.toLowerCase();
            const isVisible = genre === 'all' || cardGenre === genre.toLowerCase();
            card.style.display = isVisible ? 'block' : 'none';
        });

        // Update active filter button
        filterButtons.forEach(btn => {
            btn.classList.toggle('filter-active', btn.dataset.filter.toLowerCase() === genre.toLowerCase());
        });
    }

    // Sort games
    function sortGames(sortType) {
        const gameCardsArray = Array.from(gameCards);
        const gamesContainer = gameCards[0].parentElement;

        gameCardsArray.sort((a, b) => {
            const nameA = a.querySelector('h3').textContent;
            const nameB = b.querySelector('h3').textContent;
            const priceA = parseFloat(a.querySelector('.text-purple-600').textContent);
            const priceB = parseFloat(b.querySelector('.text-purple-600').textContent);
            const ratingA = parseFloat(a.querySelector('.flex.items-center .text-sm').textContent);
            const ratingB = parseFloat(b.querySelector('.flex.items-center .text-sm').textContent);

            switch(sortType) {
                case 'popular':
                    return ratingB - ratingA;
                case 'price-asc':
                    return priceA - priceB;
                case 'price-desc':
                    return priceB - priceA;
                case 'newest':
                    // Assuming recent games have lower IDs or you might want to add a date attribute
                    return nameA.localeCompare(nameB);
                case 'rating':
                    return ratingB - ratingA;
                default:
                    return 0;
            }
        });

        // Reinsert sorted cards
        gameCardsArray.forEach(card => gamesContainer.appendChild(card));
    }

    // Event Listeners
    searchInput.addEventListener('input', performSearch);
    clearSearchBtn.addEventListener('click', clearSearch);

    // Genre filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterByGenre(button.dataset.filter);
        });
    });

    // Sort select
    sortSelect.addEventListener('change', (e) => {
        sortGames(e.target.value);
    });
});