/**
 * Menu mobile du thème portfolio-juliette.
 * Accessible au clavier : panneau inerte quand il est fermé,
 * focus déplacé à l'ouverture et rendu au bouton à la fermeture,
 * fermeture par la touche Échap.
 */
document.addEventListener('DOMContentLoaded', function () {

  const boutonMenu = document.querySelector('.menu-toggle');
  const panneau = document.querySelector('.mobile-panel');
  const boutonFermer = document.querySelector('.menu-toggle-close');

  if (!boutonMenu || !panneau) {
    return;
  }

  // Fermé au départ : ni lu par les lecteurs d'écran, ni atteignable au clavier.
  panneau.inert = true;

  function ouvrirMenu() {
    panneau.inert = false;
    panneau.classList.add('active');
    boutonMenu.classList.add('active');
    boutonMenu.setAttribute('aria-expanded', 'true');
    document.body.classList.add('menu-open');

    // Le focus passe dans le panneau, sur le bouton de fermeture.
    if (boutonFermer) {
      boutonFermer.focus();
    }
  }

  function fermerMenu() {
    panneau.classList.remove('active');
    boutonMenu.classList.remove('active');
    boutonMenu.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');
    panneau.inert = true;

    // Le focus revient là où l'utilisateur était.
    boutonMenu.focus();
  }

  boutonMenu.addEventListener('click', function () {
    if (boutonMenu.getAttribute('aria-expanded') === 'true') {
      fermerMenu();
    } else {
      ouvrirMenu();
    }
  });

  if (boutonFermer) {
    boutonFermer.addEventListener('click', fermerMenu);
  }

  // Touche Échap : ferme le menu s'il est ouvert.
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && panneau.classList.contains('active')) {
      fermerMenu();
    }
  });
});