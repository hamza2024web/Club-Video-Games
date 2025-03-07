function editGenre(id) {
    const name = document.getElementById(`genreName-${id}`).textContent.trim();
    const description = document.getElementById(`genreDescription-${id}`).textContent.trim();
    const status = document.getElementById(`genreStatus-${id}`).textContent.trim();

    document.getElementById('genreId').value = id;
    document.getElementById('genreName').value = name;
    document.getElementById('Description').value = description;
    document.getElementById('genreStatus').value = status;

    document.getElementById('addGenreForm').action = '/updateGenre';
    document.getElementById('formTitle').textContent = 'Edit Genre';
    document.getElementById('addGenre').textContent = 'Update Genre';
}