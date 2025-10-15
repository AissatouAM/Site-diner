document.addEventListener('DOMContentLoaded', () => {
    const setupCarousel = (gridId) => {
        const grid = document.getElementById(gridId);
        if (!grid) return;

        const navButtons = document.querySelectorAll(`.nav-button[data-target="${gridId}"]`);
        const cards = Array.from(grid.querySelectorAll('.candidate-card'));
        
        if (cards.length === 0) return; // Pas de cartes, pas de carrousel

        const getCardsPerView = () => {
            if (window.innerWidth <= 768) {
                return 1;
            } else {
                return 3;
            }
        };

        const highlightMiddleCandidate = () => {
            cards.forEach(card => card.classList.remove('highlighted-candidate'));

            const gridRect = grid.getBoundingClientRect();
            let middleCard = null;
            let minDistance = Infinity;

            cards.forEach(card => {
                const cardRect = card.getBoundingClientRect();
                const cardCenter = cardRect.left + cardRect.width / 2;
                const gridCenter = gridRect.left + gridRect.width / 2;

                const distance = Math.abs(cardCenter - gridCenter);

                if (cardRect.right > gridRect.left && cardRect.left < gridRect.right) {
                    if (distance < minDistance) {
                        minDistance = distance;
                        middleCard = card;
                    }
                }
            });

            if (middleCard) {
                middleCard.classList.add('highlighted-candidate');
            }
        };

        // Initialiser la mise en évidence au chargement
        highlightMiddleCandidate();

        let isScrolling;
        grid.addEventListener('scroll', () => {
            clearTimeout(isScrolling);
            isScrolling = setTimeout(() => {
                highlightMiddleCandidate();
            }, 150); // Délai pour la fin du défilement
        });

        // Obtenir le nombre de cartes originales (sans les clonées)
        const originalCards = Array.from(grid.querySelectorAll('.candidate-card:not(.cloned-card)'));
        const totalOriginalCards = originalCards.length;
        const clonedCardsCount = 3; // Nombre de cartes clonées de chaque côté

        // Fonction pour recalculer les dimensions
        const updateDimensions = () => {
            const cardWidth = cards[0].offsetWidth;
            const gap = window.innerWidth <= 768 ? 15 : 30; // Gap adaptatif
            return { cardWidth, gap, scrollStep: cardWidth + gap };
        };

        // Initialiser la position au début des cartes originales (après les clones du début)
        let dimensions = updateDimensions();
        grid.scrollLeft = clonedCardsCount * dimensions.scrollStep;

        navButtons.forEach(button => {
            button.addEventListener('click', () => {
                dimensions = updateDimensions(); // Recalculer à chaque clic
                const direction = button.classList.contains('right') ? 1 : -1;
                const currentScroll = grid.scrollLeft;
                const targetScroll = currentScroll + (direction * dimensions.scrollStep);

                grid.scrollTo({
                    left: targetScroll,
                    behavior: 'smooth'
                });

                // Après l'animation, vérifier si on doit repositionner pour la boucle infinie
                setTimeout(() => {
                    const maxScroll = grid.scrollWidth - grid.clientWidth;
                    const minThreshold = (clonedCardsCount - 1) * dimensions.scrollStep;
                    const maxThreshold = maxScroll - (clonedCardsCount - 1) * dimensions.scrollStep;

                    if (grid.scrollLeft <= minThreshold) {
                        // On est trop à gauche, téléporter vers la fin des cartes originales
                        grid.scrollTo({
                            left: grid.scrollLeft + (totalOriginalCards * dimensions.scrollStep),
                            behavior: 'auto' // Pas d'animation pour le téléport
                        });
                    } else if (grid.scrollLeft >= maxThreshold) {
                        // On est trop à droite, téléporter vers le début des cartes originales
                        grid.scrollTo({
                            left: grid.scrollLeft - (totalOriginalCards * dimensions.scrollStep),
                            behavior: 'auto' // Pas d'animation pour le téléport
                        });
                    }
                }, 500); // Attendre la fin de l'animation smooth (300ms) + marge
            });
        });

        // Mettre à jour la mise en évidence lors du redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            dimensions = updateDimensions(); // Recalculer les dimensions
            highlightMiddleCandidate();
        });
    };

    setupCarousel('male-candidates-grid');
    setupCarousel('female-candidates-grid');
});