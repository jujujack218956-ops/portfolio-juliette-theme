<?php
// Empêche l'exécution du fichier en dehors de WordPress.
if (! defined('ABSPATH')) {
  exit;
}
/**
 * Traitement du formulaire de contact, codé sans extension.
 *
 * Deux fichiers, deux rôles :
 * - includes/traitement-contact.php (ce fichier) : vérifier et envoyer ;
 * - template-parts/formulaire-contact.php : afficher.
 *
 * Fonctionnement, dans l'ordre :
 * 1. page-contact.php affiche la page, puis appelle template-parts/formulaire-contact.php.
 * 2. Le formulaire est envoyé (POST) vers la page Contact elle-même.
 * 3. portfolio_juliette_traiter_contact() intercepte l'envoi AVANT l'affichage
 *    (hook template_redirect) : vérifications, puis envoi par wp_mail().
 * 4. Succès : redirection vers ?envoi=ok. Rafraîchir la page ne renvoie donc
 *    pas le message (motif « Post / Redirect / Get »).
 *    Erreur : la page s'affiche avec les messages et les champs déjà remplis.
 *
 * RGPD : aucune donnée n'est enregistrée en base. Le message part par email,
 * c'est tout (minimisation des données).
 *
 * @package portfolio-juliette
 */

/**
 * Adresse qui reçoit les messages.
 * Adresse publique de l'agence : elle peut figurer dans un dépôt public.
 */
function portfolio_juliette_contact_destinataire()
{
  return 'contact@agence-bethauv.fr';
}

/**
 * Les messages d'erreur, en un seul endroit.
 * Utilisés par la vérification PHP ET transmis au JavaScript par des attributs
 * data-* : le visiteur lit exactement le même texte, avec ou sans JavaScript.
 */
function portfolio_juliette_contact_messages()
{
  return array(
    'nom'          => array(
      'vide'     => 'Indiquez votre nom.',
      'longueur' => 'Votre nom ne doit pas dépasser 100 caractères.',
    ),
    'email'        => array(
      'vide'   => 'Indiquez votre adresse email.',
      'format' => 'Cette adresse email ne semble pas valide. Exemple : prenom@domaine.fr',
    ),
    'site'         => array(
      'format' => 'Cette adresse de site ne semble pas valide. Exemple : www.monsite.fr',
    ),
    'message'      => array(
      'vide'     => 'Écrivez votre message.',
      'longueur' => 'Votre message ne doit pas dépasser 5 000 caractères.',
    ),
    'consentement' => array(
      'vide' => 'Cochez cette case pour que je puisse vous répondre.',
    ),
  );
}

/**
 * Mémoire de la requête en cours.
 * Le traitement (template_redirect) a lieu avant l'affichage (gabarit) :
 * cette fonction transmet les erreurs et les valeurs saisies de l'un à l'autre.
 * Appelée sans argument, elle lit ; avec un tableau, elle enregistre.
 */
function portfolio_juliette_contact_etat($nouvel_etat = null)
{
  static $etat = array(
    'erreurs'     => array(),
    'valeurs'     => array(),
    'echec_envoi' => false,
  );

  if (null !== $nouvel_etat) {
    $etat = array_merge($etat, $nouvel_etat);
  }

  return $etat;
}

/**
 * Traitement de l'envoi.
 */
function portfolio_juliette_traiter_contact()
{
  // On ne traite que ce formulaire, sur la page Contact.
  if (! is_page('contact') || ! isset($_POST['jul_contact_nonce'])) {
    return;
  }

  // Récupération : wp_unslash() retire les antislashs ajoutés par WordPress,
  // puis chaque champ est assaini selon son type.
  $valeurs = array(
    'nom'          => sanitize_text_field(wp_unslash($_POST['jul_nom'] ?? '')),
    'email'        => sanitize_text_field(wp_unslash($_POST['jul_email'] ?? '')),
    'site'         => sanitize_text_field(wp_unslash($_POST['jul_site'] ?? '')),
    'message'      => sanitize_textarea_field(wp_unslash($_POST['jul_message'] ?? '')),
    'consentement' => ! empty($_POST['jul_consentement']),
  );

  $page_contact = get_permalink();

  // 1. Jeton de sécurité (nonce) : le formulaire vient bien de ce site,
  //    et il a été affiché il y a moins de 24 heures.
  $nonce = sanitize_text_field(wp_unslash($_POST['jul_contact_nonce']));
  if (! wp_verify_nonce($nonce, 'jul_contact_envoi')) {
    portfolio_juliette_contact_etat(
      array(
        'erreurs' => array('general' => 'La page est restée ouverte trop longtemps. Vérifiez votre message, puis renvoyez-le.'),
        'valeurs' => $valeurs,
      )
    );
    return;
  }

  // 2. Pièges à robots. Un humain ne voit pas le champ « piège » et met plus
  //    de 3 secondes à remplir le formulaire. Si l'un des deux signaux est là,
  //    on fait croire au robot que tout s'est bien passé, sans rien envoyer.
  $piege = sanitize_text_field(wp_unslash($_POST['jul_entreprise'] ?? ''));
  $debut = absint($_POST['jul_debut'] ?? 0);

  if ('' !== $piege || (time() - $debut) < 3) {
    wp_safe_redirect(add_query_arg('envoi', 'ok', $page_contact) . '#formulaire');
    exit;
  }

  // 3. Vérification des champs.
  $messages = portfolio_juliette_contact_messages();
  $erreurs  = array();

  if ('' === $valeurs['nom']) {
    $erreurs['nom'] = $messages['nom']['vide'];
  } elseif (mb_strlen($valeurs['nom']) > 100) {
    $erreurs['nom'] = $messages['nom']['longueur'];
  }

  if ('' === $valeurs['email']) {
    $erreurs['email'] = $messages['email']['vide'];
  } elseif (! is_email($valeurs['email'])) {
    $erreurs['email'] = $messages['email']['format'];
  }

  // Site facultatif. « monsite.fr » est accepté : on ajoute https:// s'il manque.
  $url_site = '';
  if ('' !== $valeurs['site']) {
    $url_site = preg_match('#^https?://#i', $valeurs['site']) ? $valeurs['site'] : 'https://' . $valeurs['site'];
    if (! filter_var($url_site, FILTER_VALIDATE_URL)) {
      $erreurs['site'] = $messages['site']['format'];
    }
  }

  if ('' === $valeurs['message']) {
    $erreurs['message'] = $messages['message']['vide'];
  } elseif (mb_strlen($valeurs['message']) > 5000) {
    $erreurs['message'] = $messages['message']['longueur'];
  }

  if (! $valeurs['consentement']) {
    $erreurs['consentement'] = $messages['consentement']['vide'];
  }

  if ($erreurs) {
    portfolio_juliette_contact_etat(
      array(
        'erreurs' => $erreurs,
        'valeurs' => $valeurs,
      )
    );
    return;
  }

  // 4. Envoi. Texte brut : rien à interpréter, rien à injecter.
  $email = sanitize_email($valeurs['email']);
  $sujet = sprintf('[Portfolio] Message de %s', $valeurs['nom']);

  $corps  = 'Nom : ' . $valeurs['nom'] . "\n";
  $corps .= 'Email : ' . $email . "\n";
  $corps .= 'Site : ' . ($url_site ? esc_url_raw($url_site) : 'non renseigné') . "\n\n";
  $corps .= "Message :\n" . $valeurs['message'] . "\n\n";
  $corps .= '—' . "\n" . 'Envoyé depuis ' . $page_contact . ' le ' . wp_date('j F Y à H:i');

  // « Répondre » dans la messagerie écrit directement au visiteur.
  // L'expéditeur reste celui du site (wordpress@domaine) : un email « de la part »
  // d'une adresse Gmail envoyé par le serveur serait rejeté comme usurpation.
  $entetes = array('Reply-To: ' . $email);

  if (wp_mail(portfolio_juliette_contact_destinataire(), $sujet, $corps, $entetes)) {
    wp_safe_redirect(add_query_arg('envoi', 'ok', $page_contact) . '#formulaire');
    exit;
  }

  // wp_mail() a échoué (serveur d'envoi indisponible, mal configuré…).
  portfolio_juliette_contact_etat(
    array(
      'valeurs'     => $valeurs,
      'echec_envoi' => true,
    )
  );
}
add_action('template_redirect', 'portfolio_juliette_traiter_contact');

/**
 * Titre de l'onglet : « Erreur — » ou « Message envoyé — » en tête.
 * C'est la première chose qu'annonce un lecteur d'écran au chargement de la page.
 */
function portfolio_juliette_contact_titre($parties)
{
  if (! is_page('contact')) {
    return $parties;
  }

  $etat = portfolio_juliette_contact_etat();

  if ($etat['erreurs'] || $etat['echec_envoi']) {
    $parties['title'] = 'Erreur — ' . $parties['title'];
  } elseif (isset($_GET['envoi']) && 'ok' === $_GET['envoi']) {
    $parties['title'] = 'Message envoyé — ' . $parties['title'];
  }

  return $parties;
}
add_filter('document_title_parts', 'portfolio_juliette_contact_titre');

/**
 * Script de validation : chargé sur la page Contact uniquement.
 */
function portfolio_juliette_contact_script()
{
  if (! is_page('contact')) {
    return;
  }

  wp_enqueue_script(
    'portfolio-juliette-contact',
    get_theme_file_uri('js/contact.js'),
    array(),
    filemtime(get_theme_file_path('js/contact.js')),
    array(
      'in_footer' => true,
      'strategy'  => 'defer',
    )
  );
}
add_action('wp_enqueue_scripts', 'portfolio_juliette_contact_script');
