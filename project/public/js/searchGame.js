document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterPills = document.querySelectorAll('.filter-pill');
    const gameCards = document.querySelectorAll('.game-card');
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const sortGamesSelect = document.getElementById('sort-games');
    
    // Filter games by category
    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            // Remove active class from all pills
            filterPills.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked pill
            pill.classList.add('active');
            
            const filter = pill.getAttribute('data-filter');
            
            // Show/hide game cards based on filter
            gameCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Also apply search filter if there is any
            if (searchInput.value.trim() !== '') {
                applySearchFilter();
            }
        });
    });
    
    // Search functionality
    searchInput.addEventListener('input', () => {
        if (searchInput.value.trim() !== '') {
            clearSearchBtn.classList.remove('hidden');
        } else {
            clearSearchBtn.classList.add('hidden');
        }
        
        applySearchFilter();
    });
    
    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearSearchBtn.classList.add('hidden');
        
        // Reset search filter
        gameCards.forEach(card => {
            // Only show cards that match the current category filter
            const activeFilter = document.querySelector('.filter-pill.active').getAttribute('data-filter');
            if (activeFilter === 'all' || card.getAttribute('data-category') === activeFilter) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
    
    function applySearchFilter() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const activeFilter = document.querySelector('.filter-pill.active').getAttribute('data-filter');
        
        gameCards.forEach(card => {
            const gameName = card.getAttribute('data-name');
            const gameCategory = card.getAttribute('data-category');
            
            // Check both filter and search criteria
            const matchesFilter = activeFilter === 'all' || gameCategory === activeFilter;
            const matchesSearch = gameName.includes(searchTerm);
            
            if (matchesFilter && matchesSearch) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
    
    // Sorting functionality
    sortGamesSelect.addEventListener('change', () => {
        const sortValue = sortGamesSelect.value;
        const gamesGrid = document.getElementById('games-grid');
        const gamesArray = Array.from(gameCards);
        
        // Sort the games array based on selected sort option
        gamesArray.sort((a, b) => {
            switch(sortValue) {
                case 'alpha':
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                case 'alpha-desc':
                    return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                // Add other sorting options as needed
                default:
                    return 0;
            }
        });
        
        // Remove all current cards
        gameCards.forEach(card => {
            card.remove();
        });
        
        // Append sorted cards
        gamesArray.forEach(card => {
            gamesGrid.appendChild(card);
        });
        
        // Re-apply current filters
        const activeFilter = document.querySelector('.filter-pill.active').getAttribute('data-filter');
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        gameCards.forEach(card => {
            const gameName = card.getAttribute('data-name');
            const gameCategory = card.getAttribute('data-category');
            
            const matchesFilter = activeFilter === 'all' || gameCategory === activeFilter;
            const matchesSearch = searchTerm === '' || gameName.includes(searchTerm);
            
            if (matchesFilter && matchesSearch) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});