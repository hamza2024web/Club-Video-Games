// Modal Handling
function openTournamentModal() {
    const modal = document.getElementById('tournamentModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Animation timing
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeTournamentModal() {
    const modal = document.getElementById('tournamentModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Fee Toggle Logic
document.addEventListener('DOMContentLoaded', function() {
    
    // Tab switching logic
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
    button.addEventListener('click', function() {
    // Remove active class from all tabs
    tabButtons.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked tab
    this.classList.add('active');
    });
    });
    
    // Form validation
    const tournamentForm = document.querySelector('form');
    
    tournamentForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Get required fields
    const tournamentName = this.querySelector('input[name="name"]').value;
    const game = this.querySelector('select[name="game"]').value;
    const startDate = this.querySelector('input[name="start_date"]').value;
    const endDate = this.querySelector('input[name="end_date"]').value;
    const maxParticipants = this.querySelector('input[name="max_participants"]').value;

    // Simple validation
    if (!tournamentName || !game || !startDate || !endDate || !maxParticipants) {
        Swal.fire({
        title: 'Formulaire incomplet',
        text: 'Veuillez remplir tous les champs obligatoires',
        icon: 'error',
        confirmButtonColor: '#8b5cf6'
    });
    return;
    }

    // Check that end date is after start date
    if (new Date(endDate) <= new Date(startDate)) {
        Swal.fire({
        title: 'Erreur de date',
        text: 'La date de fin doit être postérieure à la date de début',
        icon: 'error',
        confirmButtonColor: '#8b5cf6'
        });
        return;
    }


// Tooltip initialization
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltipTriggerList.forEach(tooltipTriggerEl => {
    new bootstrap.Tooltip(tooltipTriggerEl);
});

// Tournament card status highlighting
document.addEventListener('DOMContentLoaded', function() {
    const tournamentCards = document.querySelectorAll('.tournament-card');
    
    tournamentCards.forEach(card => {
// Highlight the card when hovered
card.addEventListener('mouseenter', function() {
    this.classList.add('shadow-md');
});

card.addEventListener('mouseleave', function() {
    this.classList.remove('shadow-md');
});
    });
});