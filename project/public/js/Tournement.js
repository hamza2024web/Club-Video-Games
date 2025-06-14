        function openTournamentModal(modalId) {
            let fullModalId;
            
            if (modalId.startsWith('inscription-') || modalId.startsWith('modal-tournoi-')) {
                fullModalId = modalId;
            } else {
                fullModalId = document.getElementById(`inscription-${modalId}`) 
                    ? `inscription-${modalId}` 
                    : `modal-tournoi-${modalId}`;
            }
            
            const modal = document.getElementById(fullModalId);
            
            if (modal) {
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.add('active');
                } else {
                    modal.classList.remove('hidden');
                    
                    const modalContent = document.getElementById(`modal-content-${modalId.split('-').pop()}`);
                    if (modalContent) {
                        setTimeout(() => {
                            modalContent.classList.remove('opacity-0', 'scale-95');
                            modalContent.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    }
                }
                
                document.body.style.overflow = 'hidden';
            }
        }

        function closeTournamentModal(modalId) {
            let fullModalId;
            
            if (modalId.startsWith('inscription-') || modalId.startsWith('modal-tournoi-')) {
                fullModalId = modalId;
            } else {
                fullModalId = document.getElementById(`inscription-${modalId}`) 
                    ? `inscription-${modalId}` 
                    : `modal-tournoi-${modalId}`;
            }
            
            const modal = document.getElementById(fullModalId);
            
            if (modal) {
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.remove('active');
                } else {
                    const modalContent = document.getElementById(`modal-content-${modalId.split('-').pop()}`);
                    if (modalContent) {
                        modalContent.classList.remove('opacity-100', 'scale-100');
                        modalContent.classList.add('opacity-0', 'scale-95');
                        
                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 200);
                    }
                }
                
                document.body.style.overflow = 'auto';
            }
        }

        function miniTab(contentId, button) {
            const modalId = contentId.split('-')[1];
            
            const contents = document.querySelectorAll(`[id$="-${modalId}"].mini-content`);
            contents.forEach(c => c.classList.add('hidden'));
            
            const selectedTab = document.getElementById(contentId);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
            
            const tabButtons = button.parentElement.querySelectorAll('.mini-tab');
            tabButtons.forEach(b => {
                b.classList.remove('active', 'border-purple-500', 'text-purple-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('active', 'border-purple-500', 'text-purple-600');
        }

        function redirectToTournaments() {
            const allTournamentsTab = document.querySelector('button[data-tab="all-tournaments"]');
            if (allTournamentsTab) {
                allTournamentsTab.click();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tournament-tabs button');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('tab-active'));
                    
                    this.classList.add('tab-active');
                    
                    document.querySelectorAll('section[id]').forEach(section => {
                        if (section.id === 'all-tournaments' || 
                            section.id === 'mes-inscriptions' || 
                            section.id === 'tournois-termines' || 
                            section.id === 'calendrier') {
                            section.classList.add('hidden');
                        }
                    });
                    
                    const targetId = this.getAttribute('data-tab');
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
            
            const rechargeBtn = document.querySelector('.sidebar-footer button');
            const rechargeModal = document.getElementById('recharge-modal');
            const closeModalBtn = document.getElementById('close-recharge-modal');
            
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
            
            if (urlParams.has('inscription_error')) {
                Swal.fire({
                    title: 'Erreur',
                    text: "L'inscription au tournoi a échoué. Veuillez vérifier votre solde !",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#8b5cf6'
                }).then(cleanURL);
            }
            
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


    function showPaymentForm(tournoiId, fraisInscription, tournoiName) {
        document.getElementById('payment-tournament-name').textContent = tournoiName;
        
        const frais = parseFloat(fraisInscription);
        if (frais > 0) {
            document.getElementById('payment-amount').textContent = `${frais.toFixed(2)} €`;
            document.getElementById('payment-paid-notice').classList.remove('hidden');
        } else {
            document.getElementById('payment-amount').textContent = 'Gratuit';
        }
        
        document.getElementById('payment-tournoi-id').value = tournoiId;
        document.getElementById('payment-frais').value = fraisInscription;
        
        const modal = document.getElementById('payment-modal');
        const modalContent = document.getElementById('payment-modal-content');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modalContent.classList.remove('opacity-0', 'scale-95');
            modalContent.classList.add('opacity-100', 'scale-100');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }
    
    function closePaymentModal() {
        const modal = document.getElementById('payment-modal');
        const modalContent = document.getElementById('payment-modal-content');
        
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    function processPayment() {
        Swal.fire({
            title: 'Traitement en cours',
            text: 'Veuillez patienter pendant le traitement du paiement...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            const tournoiId = document.getElementById('payment-tournoi-id').value;
            const fraisInscription = document.getElementById('payment-frais').value;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/member/inscription';
            
            const inputTournoiId = document.createElement('input');
            inputTournoiId.type = 'hidden';
            inputTournoiId.name = 'tournoi_id';
            inputTournoiId.value = tournoiId;
            form.appendChild(inputTournoiId);
            
            const inputFrais = document.createElement('input');
            inputFrais.type = 'hidden';
            inputFrais.name = 'frais_inscription';
            inputFrais.value = fraisInscription;
            form.appendChild(inputFrais);
            
            document.body.appendChild(form);
            form.submit();
        }, 2000);
    }