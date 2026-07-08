// GESTION DU MENU MOBILE
const menuBtn = document.getElementById('menuBtn');
const navLinks = document.getElementById('navLinks');

if (menuBtn && navLinks) {
    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });
}

function toggleMenu() {
    if (navLinks.style.display === 'flex') {
        navLinks.style.display = 'none';
    } else {
        navLinks.style.display = 'flex';
        navLinks.style.flexDirection = 'column';
        navLinks.style.background = '#2c3e50';
        navLinks.style.position = 'absolute';
        navLinks.style.top = '70px';
        navLinks.style.right = '0';
        navLinks.style.width = '100%';
        navLinks.style.zIndex = '9999';
        navLinks.style.padding = '20px';
    }
}

// FERMETURE AUTOMATIQUE DU MENU MOBILE APRÈS SÉLECTION
function closeMenu() {
    if (window.innerWidth <= 768) {
        navLinks.style.display = 'none';
    }
}

// FERMETURE AU CLIC EXTÉRIEUR
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 && navLinks.style.display === 'flex' && !navLinks.contains(e.target) && e.target !== menuBtn) {
        navLinks.style.display = 'none';
    }
});

// LANGUE PERSISTANTE AVEC RELOAD PROPRE
function changeLanguage(lang) {
    fetch('change_language.php?lang=' + lang)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => {
            console.error("Erreur de changement de langue, rechargement forcé.");
            location.reload();
        });
}