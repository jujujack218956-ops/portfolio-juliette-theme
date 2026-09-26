/**
 * Formulaire de contact : vérification en français, accessible.
 *
 * Amélioration progressive : sans JavaScript, le formulaire fonctionne
 * (le serveur vérifie tout, voir includes/traitement-contact.php).
 * Avec JavaScript, les erreurs s'affichent avant l'envoi, sans recharger
 * la page, avec les mêmes textes que ceux du serveur (attributs data-*).
 */
document.addEventListener('DOMContentLoaded', function () {

  const formulaire = document.querySelector('.formulaire-contact__form');

  if (!formulaire) {
    return; // Page de confirmation : pas de formulaire à surveiller.
  }

  // On remplace les bulles du navigateur : peu lisibles au zoom,
  // pas toujours en français, et mal lues par certains lecteurs d'écran.
  formulaire.noValidate = true;

  const champs = formulaire.querySelectorAll('[data-champ]');
  const bouton = formulaire.querySelector('button[type="submit"]');

  // L'API de validation du navigateur (champ.validity) dit ce qui ne va pas ;
  // on choisit le message correspondant.
  function messageErreur(champ) {
    const etat = champ.validity;

    if (etat.valueMissing) {
      return champ.dataset.erreurVide;
    }
    if (etat.typeMismatch || etat.patternMismatch) {
      return champ.dataset.erreurFormat;
    }
    if (etat.tooLong) {
      return champ.dataset.erreurLongueur;
    }
    return '';
  }

  // Affiche (ou efface) le message sous le champ. Renvoie true si le champ est valide.
  function verifier(champ) {
    const message = messageErreur(champ) || '';
    const zone = document.getElementById('erreur-' + champ.dataset.champ);

    if (zone) {
      zone.textContent = message;
    }

    if (message) {
      champ.setAttribute('aria-invalid', 'true');
    } else {
      champ.removeAttribute('aria-invalid');
    }

    return message === '';
  }

  formulaire.addEventListener('submit', function (evenement) {
    let premierInvalide = null;

    champs.forEach(function (champ) {
      if (!verifier(champ) && !premierInvalide) {
        premierInvalide = champ;
      }
    });

    if (premierInvalide) {
      evenement.preventDefault();
      // Le focus sur le champ fait lire : étiquette, message d'erreur, « invalide ».
      premierInvalide.focus();
      return;
    }

    // Tout est bon : on évite le double clic (et le double envoi).
    bouton.disabled = true;
    bouton.textContent = 'Envoi en cours…';
  });

  // Correction en direct : dès qu'un champ en erreur redevient valide,
  // son message disparaît. On n'affiche pas d'erreur pendant la saisie.
  champs.forEach(function (champ) {
    const evenement = champ.type === 'checkbox' ? 'change' : 'input';

    champ.addEventListener(evenement, function () {
      if (champ.getAttribute('aria-invalid') === 'true') {
        verifier(champ);
      }
    });
  });

  // Retour arrière du navigateur : la page peut revenir du cache avec
  // le bouton encore désactivé. On le réactive.
  window.addEventListener('pageshow', function () {
    bouton.disabled = false;
    bouton.textContent = 'Envoyer mon message';
  });
});