        // Fonction unifiée pour ouvrir n'importe quel modal (tournoi ou inscription)
        function openTournamentModal(modalId) {
            // Gérer les deux formats d'ID possibles
            let fullModalId;
            
            // Si l'ID est déjà complet (commence par 'inscription-' ou 'modal-tournoi-')
            if (modalId.startsWith('inscription-') || modalId.startsWith('modal-tournoi-')) {
                fullModalId = modalId;
            } else {
                // Vérifier si le modal d'inscription existe d'abord
                fullModalId = document.getElementById(`inscription-${modalId}`) 
                    ? `inscription-${modalId}` 
                    : `modal-tournoi-${modalId}`;
            }
            
            const modal = document.getElementById(fullModalId);
            
            if (modal) {
                // Pour les modals classiques
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.add('active');
                } else {
                    // Pour les modals avec nouvelle structure
                    modal.classList.remove('hidden');
                    
                    // Animation
                    const modalContent = document.getElementById(`modal-content-${modalId.split('-').pop()}`);
                    if (modalContent) {
                        setTimeout(() => {
                            modalContent.classList.remove('opacity-0', 'scale-95');
                            modalContent.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    }
                }
                
                // Empêcher le défilement de la page
                document.body.style.overflow = 'hidden';
            }
        }

        // Fonction unifiée pour fermer n'importe quel modal (tournoi ou inscription)
        function closeTournamentModal(modalId) {
            // Gérer les deux formats d'ID possibles
            let fullModalId;
            
            // Si l'ID est déjà complet (commence par 'inscription-' ou 'modal-tournoi-')
            if (modalId.startsWith('inscription-') || modalId.startsWith('modal-tournoi-')) {
                fullModalId = modalId;
            } else {
                // Vérifier si le modal d'inscription existe d'abord
                fullModalId = document.getElementById(`inscription-${modalId}`) 
                    ? `inscription-${modalId}` 
                    : `modal-tournoi-${modalId}`;
            }
            
            const modal = document.getElementById(fullModalId);
            
            if (modal) {
                // Pour les modals classiques
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.remove('active');
                } else {
                    // Pour les modals avec nouvelle structure
                    const modalContent = document.getElementById(`modal-content-${modalId.split('-').pop()}`);
                    if (modalContent) {
                        modalContent.classList.remove('opacity-100', 'scale-100');
                        modalContent.classList.add('opacity-0', 'scale-95');
                        
                        // Masquer le modal après l'animation
                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 200);
                    }
                }
                
                // Réactiver le défilement de la page
                document.body.style.overflow = 'auto';
            }
        }

        // Fonction pour gérer les onglets dans le modal compact
        function miniTab(contentId, button) {
            // Get modal ID from content ID
            const modalId = contentId.split('-')[1];
            
            // Hide all content
            const contents = document.querySelectorAll(`[id$="-${modalId}"].mini-content`);
            contents.forEach(c => c.classList.add('hidden'));
            
            // Show selected content
            const selectedTab = document.getElementById(contentId);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
            
            // Reset all buttons
            const tabButtons = button.parentElement.querySelectorAll('.mini-tab');
            tabButtons.forEach(b => {
                b.classList.remove('active', 'border-purple-500', 'text-purple-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Activate clicked button
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('active', 'border-purple-500', 'text-purple-600');
        }

        // Fonction pour rediriger vers l'onglet de tous les tournois
        function redirectToTournaments() {
            const allTournamentsTab = document.querySelector('button[data-tab="all-tournaments"]');
            if (allTournamentsTab) {
                allTournamentsTab.click();
            }
        }

        // Initialiser les événements quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function() {
            // Tabs functionality
            const tabs = document.querySelectorAll('.tournament-tabs button');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('tab-active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('tab-active');
                    
                    // Hide all sections
                    document.querySelectorAll('section[id]').forEach(section => {
                        if (section.id === 'all-tournaments' || 
                            section.id === 'mes-inscriptions' || 
                            section.id === 'tournois-termines' || 
                            section.id === 'calendrier') {
                            section.classList.add('hidden');
                        }
                    });
                    
                    // Show the corresponding section
                    const targetId = this.getAttribute('data-tab');
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
            
            // Recharge modal functionality
            const rechargeBtn = document.querySelector('.sidebar-footer button');
            const rechargeModal = document.getElementById('recharge-modal');
            const closeModalBtn = document.getElementById('close-recharge-modal');
            
            // Function to open the modal
            function openRechargeModal() {
                if (rechargeModal) {
                    rechargeModal.classList.remove('hidden');
                    rechargeModal.classList.add('flex');
                }
            }
            
            // Function to close the modal
            function closeRechargeModal() {
                if (rechargeModal) {
                    rechargeModal.classList.add('hidden');
                    rechargeModal.classList.remove('flex');
                }
            }
            
            // Add an event click to the "Recharger" button
            if (rechargeBtn) {
                rechargeBtn.addEventListener('click', openRechargeModal);
            }
            
            // Add an event click to the close button
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeRechargeModal);
            }
            
            // Close the modal when clicking outside
            if (rechargeModal) {
                rechargeModal.addEventListener('click', function(e) {
                    if (e.target === rechargeModal) {
                        closeRechargeModal();
                    }
                });
            }
            
            // Handle recharge form submission
            const rechargeForm = document.getElementById('recharge-form');
            
            // Event handler for form submission
            if (rechargeForm) {
                rechargeForm.addEventListener('submit', function(e) {
                    // Prevent default form submission
                    e.preventDefault();
                    
                    // Get the entered amount
                    const amount = document.getElementById('recharge-amount').value;
                    
                    // Show loading alert
                    Swal.fire({
                        title: 'Rechargement en cours',
                        text: `Rechargement de ${amount} € en cours...`,
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form after a short delay to show the animation
                    setTimeout(() => {
                        rechargeForm.submit();
                    }, 1500);
                });
            }
            
            // Check URL parameters for notifications
            const urlParams = new URLSearchParams(window.location.search);
            const currentPath = window.location.pathname;
            
            // Fonction pour nettoyer l'URL
            function cleanURL() {
                window.history.replaceState({}, document.title, currentPath);
            }
            
            // Gestion des notifications
            if (urlParams.has('Rechargement_de_votre_solde_réussite')) {
                Swal.fire({
                    title: 'Succès!',
                    text: 'Rechargement de votre solde réussi',
                    icon: 'success',
                    confirmButtonColor: '#8b5cf6'
                }).then(cleanURL);
            }
            
            if (urlParams.has('Rechargement_de_votre_solde_échouée')) {
                Swal.fire({
                    title: 'Échec',
                    text: 'Le rechargement de votre solde a échoué. Veuillez réessayer.',
                    icon: 'error',
                    confirmButtonColor: '#8b5cf6'
                }).then(cleanURL);
            }
            
            if (urlParams.has('Inscription_succefully')) {
                Swal.fire({
                    title: "Succès !",
                    text: "L'inscription au tournament a été effectuée avec succès !",
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor: '#8b5cf6',
                    timer: 3000 
                }).then(cleanURL);
            }
            
            if (urlParams.has('error') && urlParams.get('error') === 'Inscription_failed') {
                Swal.fire({
                    title: 'Erreur',
                    text: "L'inscription au tournament a échoué",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#8b5cf6'
                }).then(cleanURL);
            }
            
            // Close modal with escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const activeModal = document.querySelector('.modal-overlay.active');
                    if (activeModal) {
                        activeModal.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                }
            });
        });