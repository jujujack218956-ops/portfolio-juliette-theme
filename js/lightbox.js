/**
 * Lightbox des fiches projet : agrandit les captures d'écran.
 *
 * Adaptée de la lightbox du projet Nathalie Mota, avec trois changements :
 * - elle s'appuie sur l'élément HTML natif <dialog> : le navigateur gère
 *   lui-même la fenêtre modale, le piège du focus et la touche Échap ;
 * - elle lit les images saisies dans l'éditeur (bloc Image ou Galerie
 *   réglé sur « Lien : fichier média »), sans classe ni attribut à ajouter ;
 * - amélioration progressive : sans JavaScript, le lien ouvre simplement
 *   l'image en grand.
 *
 * GreenIT : la grande image n'est téléchargée qu'au moment où on l'ouvre,
 * et ce script n'est chargé que sur les fiches projet (voir functions.php).
 */
document.addEventListener('DOMContentLoaded', function () {

  const zone = document.querySelector('.page-projet__contenu');
  if (!zone || typeof HTMLDialogElement !== 'function') {
    return; // Pas de contenu, ou navigateur trop ancien : les liens restent des liens.
  }

  // Liens de l'éditeur qui pointent vers une image et en contiennent une.
  const estUneImage = /\.(avif|webp|jpe?g|png|gif)(\?.*)?$/i;
  const liens = Array.from(zone.querySelectorAll('a[href]')).filter(function (lien) {
    return estUneImage.test(lien.getAttribute('href')) && lien.querySelector('img');
  });

  if (!liens.length) {
    return;
  }

  // La galerie : une entrée par lien, avec le texte alternatif et la légende.
  const images = liens.map(function (lien) {
    const miniature = lien.querySelector('img');
    const figure = lien.closest('figure');
    const legende = figure ? figure.querySelector('figcaption') : null;
    return {
      url: lien.getAttribute('href'),
      alt: miniature.getAttribute('alt') || '',
      legende: legende ? legende.textContent.trim() : '',
    };
  });

  // Une seule boîte de dialogue, créée une fois et réutilisée.
  const dialog = document.createElement('dialog');
  dialog.className = 'lightbox';
  dialog.setAttribute('aria-label', 'Capture d\'écran agrandie');
  dialog.innerHTML = `
    <button class="lightbox__fermer" type="button">
      <span class="sr-only">Fermer</span>
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false">
        <line x1="5" y1="5" x2="19" y2="19" />
        <line x1="19" y1="5" x2="5" y2="19" />
      </svg>
    </button>
    <figure class="lightbox__figure">
      <img class="lightbox__image" src="" alt="">
      <figcaption class="lightbox__legende">
        <span class="lightbox__texte"></span>
        <span class="lightbox__compteur" aria-live="polite"></span>
      </figcaption>
    </figure>
    <button class="lightbox__precedente" type="button">Précédente</button>
    <button class="lightbox__suivante" type="button">Suivante</button>`;
  document.body.appendChild(dialog);

  const image = dialog.querySelector('.lightbox__image');
  const texte = dialog.querySelector('.lightbox__texte');
  const compteur = dialog.querySelector('.lightbox__compteur');
  const boutonPrecedente = dialog.querySelector('.lightbox__precedente');
  const boutonSuivante = dialog.querySelector('.lightbox__suivante');

  // Une seule image : pas de navigation.
  if (images.length < 2) {
    boutonPrecedente.hidden = true;
    boutonSuivante.hidden = true;
  }

  let position = 0;
  let declencheur = null; // lien cliqué, pour lui rendre le focus à la fermeture

  function afficher(index) {
    // Boucle : après la dernière, on revient à la première (et inversement).
    position = (index + images.length) % images.length;
    const courante = images[position];

    image.src = courante.url;
    image.alt = courante.alt;
    texte.textContent = courante.legende;
    compteur.textContent = images.length > 1 ? (position + 1) + ' / ' + images.length : '';
  }

  function ouvrir(index, lien) {
    declencheur = lien;
    afficher(index);
    dialog.showModal(); // fenêtre modale : le reste de la page devient inerte
  }

  // Clic sur une miniature : on ouvre la lightbox au lieu de suivre le lien.
  liens.forEach(function (lien, index) {
    lien.addEventListener('click', function (evenement) {
      evenement.preventDefault();
      ouvrir(index, lien);
    });
  });

  dialog.querySelector('.lightbox__fermer').addEventListener('click', function () {
    dialog.close();
  });
  boutonPrecedente.addEventListener('click', function () {
    afficher(position - 1);
  });
  boutonSuivante.addEventListener('click', function () {
    afficher(position + 1);
  });

  // Clic sur le fond sombre (en dehors de l'image et des boutons) : fermeture.
  dialog.addEventListener('click', function (evenement) {
    if (evenement.target === dialog) {
      dialog.close();
    }
  });

  // Flèches du clavier. Échap est géré par <dialog> lui-même.
  dialog.addEventListener('keydown', function (evenement) {
    if (images.length < 2) {
      return;
    }
    if (evenement.key === 'ArrowRight') {
      afficher(position + 1);
    } else if (evenement.key === 'ArrowLeft') {
      afficher(position - 1);
    }
  });

  // À la fermeture (bouton, fond ou Échap) : on vide l'image et on rend
  // le focus au lien d'origine, pour que la navigation au clavier reprenne là.
  dialog.addEventListener('close', function () {
    image.removeAttribute('src');
    if (declencheur) {
      declencheur.focus();
    }
  });
});
