// Centered Card Carousel - Carte active au centre
function createCenteredSlider(sliderSelector, prevBtnSelector, nextBtnSelector) {
    const slider = document.querySelector(sliderSelector);
    if (!slider) return;
    
    const cards = slider.querySelectorAll('.candidat');
    const prevBtn = document.querySelector(prevBtnSelector);
    const nextBtn = document.querySelector(nextBtnSelector);

    let currentIndex = 0;

    function updateSlider() {
        const container = slider.parentElement;
        const containerWidth = container.offsetWidth;
        const cardWidth = cards[0].offsetWidth;
        const gap = 30;
        
        // Centrer la carte active
        const centerOffset = (containerWidth / 2) - (cardWidth / 2);
        const cardOffset = currentIndex * (cardWidth + gap);
        const translateX = centerOffset - cardOffset;
        
        slider.style.transform = `translateX(${translateX}px)`;
        
        // Mettre à jour les classes active
        cards.forEach((card, index) => {
            if (index === currentIndex) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < cards.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateSlider();
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = cards.length - 1;
        }
        updateSlider();
    });

    // Support clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevBtn.click();
        if (e.key === 'ArrowRight') nextBtn.click();
    });

    // Mettre à jour au redimensionnement
    window.addEventListener('resize', updateSlider);

    // Initialiser
    updateSlider();
}

// Sliders indépendants
createCenteredSlider('.roi-slider', '.prev-roi', '.next-roi');
createCenteredSlider('.reine-slider', '.prev-reine', '.next-reine');

// Base de données locale pour les votes
const VoteDB = {
    // Récupérer les votes depuis localStorage
    getVotes: function() {
        const votes = localStorage.getItem('codersDinerVotes');
        return votes ? JSON.parse(votes) : { roi: {}, reine: {} };
    },
    
    // Sauvegarder un vote
    saveVote: function(type, candidateName) {
        const votes = this.getVotes();
        const userId = this.getUserId();
        
        if (!votes[type][candidateName]) {
            votes[type][candidateName] = [];
        }
        
        if (!votes[type][candidateName].includes(userId)) {
            votes[type][candidateName].push(userId);
        }
        
        localStorage.setItem('codersDinerVotes', JSON.stringify(votes));
        this.saveUserVote(type, candidateName);
    },
    
    // Récupérer l'ID utilisateur (simulé)
    getUserId: function() {
        let userId = localStorage.getItem('userId');
        if (!userId) {
            userId = 'user_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('userId', userId);
        }
        return userId;
    },
    
    // Sauvegarder le vote de l'utilisateur
    saveUserVote: function(type, candidateName) {
        const userVotes = this.getUserVotes();
        userVotes[type] = candidateName;
        localStorage.setItem('userVotes', JSON.stringify(userVotes));
    },
    
    // Récupérer les votes de l'utilisateur
    getUserVotes: function() {
        const votes = localStorage.getItem('userVotes');
        return votes ? JSON.parse(votes) : { roi: null, reine: null };
    },
    
    // Obtenir le nombre de votes pour un candidat
    getVoteCount: function(type, candidateName) {
        const votes = this.getVotes();
        return votes[type][candidateName] ? votes[type][candidateName].length : 0;
    }
};

// Gestion votes : 1 roi et 1 reine max
let voted = VoteDB.getUserVotes();

// Restaurer l'état des votes au chargement
document.addEventListener('DOMContentLoaded', () => {
    Object.keys(voted).forEach(type => {
        if (voted[type]) {
            document.querySelectorAll(`.candidat[data-type="${type}"]`).forEach(card => {
                const name = card.querySelector('.nom-candidat').textContent;
                const btn = card.querySelector('.vote-btn');
                
                if (name === voted[type]) {
                    btn.textContent = "Voté ✅";
                    btn.disabled = true;
                    btn.style.opacity = "0.6";
                } else {
                    btn.disabled = true;
                    btn.style.opacity = "0.4";
                }
            });
        }
    });
});

document.querySelectorAll('.vote-btn').forEach(button => {
    button.addEventListener('click', () => {
        const card = button.closest('.candidat');
        const type = card.dataset.type;
        const name = card.querySelector('.nom-candidat').textContent;

        if (voted[type]) {
            alert(`Vous avez déjà voté pour un ${type} !`);
            return;
        }

        // Enregistrer le vote dans la base de données
        VoteDB.saveVote(type, name);
        voted[type] = name;
        
        alert(`Tu as voté pour ${name} (${type}) 👑`);
        button.textContent = "Voté ✅";
        button.disabled = true;
        button.style.opacity = "0.6";

        // Désactiver les autres boutons du même type
        document.querySelectorAll(`.candidat[data-type="${type}"]`).forEach(c => {
            if (c !== card) {
                const btn = c.querySelector('.vote-btn');
                btn.disabled = true;
                btn.style.opacity = "0.4";
            }
        });
    });
});
