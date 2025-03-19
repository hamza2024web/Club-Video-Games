function editGame(gameId) { 
    const title = document.getElementById('gameTitle-' + gameId).innerText;
    const genre = document.getElementById('gameGenre-' + gameId).innerText;
    const year = document.getElementById('gameYear-' + gameId).innerText;
    const developer = document.getElementById('gameDeveloper-' + gameId).innerText;
    const description = document.getElementById('gameDescription-' + gameId).innerText;
    const status = document.getElementById('gameStatus-' + gameId).innerText;
    const prix = document.getElementById('gamePrice-' + gameId).innerText;
    
    document.getElementById('formTitle').innerText = 'Edit Game';
    document.getElementById('addGame').innerText = 'Update Game';
    document.getElementById('addGameForm').action = '/updateGame';
    
    document.getElementById('gameId').value = gameId;
    document.getElementById('gameTitle').value = title;
    
    const genreSelect = document.getElementById('gameGenre');
    const genreValues = genre.split(', ').map(g => g.trim());
    for (let option of genreSelect.options) {
        option.selected = genreValues.includes(option.text);
    }
    
    document.getElementById('gameYear').value = year;
    document.getElementById('gameDeveloper').value = developer;
    document.getElementById('gameDescription').value = description;
    document.getElementById('gameStatus').value = status;
    document.getElementById('prix').value = prix;
    
    document.getElementById('formTitle').scrollIntoView({ behavior: 'smooth' });
}