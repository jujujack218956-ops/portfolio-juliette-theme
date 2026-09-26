<?php

/**
 * Affichage du formulaire de contact (HTML uniquement).
 * Le traitement est dans includes/traitement-contact.php.
 *
 * Accessibilité :
 * - chaque champ a une étiquette <label> visible, reliée par for / id ;
 * - chaque message d'erreur est relié à son champ par aria-describedby ;
 * - un champ en erreur porte aria-invalid="true" ;
 * - autocomplete aide à remplir (et répond au critère WCAG 1.3.5).
 *
 * @package portfolio-juliette
 */

$etat     = portfolio_juliette_contact_etat();
$erreurs  = $etat['erreurs'];
$messages = portfolio_juliette_contact_messages();
$valeurs  = wp_parse_args(
  $etat['valeurs'],
  array(
    'nom'          => '',
    'email'        => '',
    'site'         => '',
    'message'      => '',
    'consentement' => false,
  )
);

$envoye          = isset($_GET['envoi']) && 'ok' === $_GET['envoi'];
$confidentialite = get_privacy_policy_url();

// Libellés du résumé d'erreurs : chaque lien mène au champ concerné.
$ancres = array(
  'nom'          => 'jul-nom',
  'email'        => 'jul-email',
  'site'         => 'jul-site',
  'message'      => 'jul-message',
  'consentement' => 'jul-consentement',
);

/*
 * Petite aide d'affichage : attributs communs d'un champ.
 * aria-invalid n'est ajouté que si le champ est en erreur.
 */
$attributs_erreur = function ($champ) use ($erreurs) {
  $html = ' aria-describedby="erreur-' . esc_attr($champ) . '"';
  if (isset($erreurs[$champ])) {
    $html .= ' aria-invalid="true"';
  }
  return $html;
};

// Zone de message d'erreur : toujours présente (le JavaScript la remplit),
// masquée en CSS quand elle est vide.
$zone_erreur = function ($champ) use ($erreurs) {
  printf(
    '<p class="champ__erreur" id="erreur-%1$s">%2$s</p>',
    esc_attr($champ),
    isset($erreurs[$champ]) ? esc_html($erreurs[$champ]) : ''
  );
};
?>

<section class="formulaire-contact" id="formulaire" aria-labelledby="titre-formulaire" tabindex="-1">

  <h2 id="titre-formulaire">Votre message</h2>

  <?php if ($envoye) : ?>

    <div class="formulaire-contact__succes" role="status">
      <p>Merci, votre message est bien parti. Je vous réponds sous 48 heures ouvrées.</p>
    </div>

  <?php else : ?>

    <?php if ($etat['echec_envoi']) : ?>
      <div class="formulaire-contact__alerte" role="alert">
        <p>
          Votre message n'a pas pu être envoyé. Vous pouvez me joindre directement à
          <a href="mailto:<?php echo esc_attr(antispambot(portfolio_juliette_contact_destinataire())); ?>"><?php echo esc_html(antispambot(portfolio_juliette_contact_destinataire())); ?></a>.
        </p>
      </div>
    <?php endif; ?>

    <?php if ($erreurs) : ?>
      <div class="formulaire-contact__alerte" role="alert">
        <p><strong>Le message n'est pas parti. Merci de corriger :</strong></p>
        <ul>
          <?php foreach ($erreurs as $champ => $texte) : ?>
            <li>
              <?php if (isset($ancres[$champ])) : ?>
                <a href="#<?php echo esc_attr($ancres[$champ]); ?>"><?php echo esc_html($texte); ?></a>
              <?php else : ?>
                <?php echo esc_html($texte); ?>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="formulaire-contact__form" method="post" action="<?php echo esc_url(get_permalink() . '#formulaire'); ?>">

      <p class="formulaire-contact__consigne">Tous les champs sont obligatoires, sauf mention « facultatif ».</p>

      <?php wp_nonce_field('jul_contact_envoi', 'jul_contact_nonce'); ?>
      <input type="hidden" name="jul_debut" value="<?php echo esc_attr(time()); ?>">

      <div class="champ">
        <label for="jul-nom">Votre nom</label>
        <input type="text" id="jul-nom" name="jul_nom" required maxlength="100" autocomplete="name"
          value="<?php echo esc_attr($valeurs['nom']); ?>"
          data-champ="nom"
          data-erreur-vide="<?php echo esc_attr($messages['nom']['vide']); ?>"
          data-erreur-longueur="<?php echo esc_attr($messages['nom']['longueur']); ?>"
          <?php echo $attributs_erreur('nom'); // phpcs:ignore WordPress.Security.EscapeOutput -- échappé dans la fonction. 
          ?>>
        <?php $zone_erreur('nom'); ?>
      </div>

      <div class="champ">
        <label for="jul-email">Votre email</label>
        <input type="email" id="jul-email" name="jul_email" required maxlength="254" autocomplete="email"
          pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
          value="<?php echo esc_attr($valeurs['email']); ?>"
          data-champ="email"
          data-erreur-vide="<?php echo esc_attr($messages['email']['vide']); ?>"
          data-erreur-format="<?php echo esc_attr($messages['email']['format']); ?>"
          <?php echo $attributs_erreur('email'); // phpcs:ignore WordPress.Security.EscapeOutput 
          ?>>
        <?php $zone_erreur('email'); ?>
      </div>

      <div class="champ">
        <!-- type="text" et non "url" : le navigateur refuserait « monsite.fr » sans https://.
				     inputmode="url" affiche quand même le clavier adapté sur téléphone. -->
        <label for="jul-site">Votre site actuel, si vous en avez un <span class="champ__facultatif">(facultatif)</span></label>
        <input type="text" id="jul-site" name="jul_site" maxlength="200" inputmode="url" autocomplete="url"
          value="<?php echo esc_attr($valeurs['site']); ?>"
          <?php echo $attributs_erreur('site'); // phpcs:ignore WordPress.Security.EscapeOutput 
          ?>>
        <?php $zone_erreur('site'); ?>
      </div>

      <div class="champ">
        <label for="jul-message">Votre message</label>
        <textarea id="jul-message" name="jul_message" rows="8" required maxlength="5000"
          data-champ="message"
          data-erreur-vide="<?php echo esc_attr($messages['message']['vide']); ?>"
          data-erreur-longueur="<?php echo esc_attr($messages['message']['longueur']); ?>"
          <?php echo $attributs_erreur('message'); // phpcs:ignore WordPress.Security.EscapeOutput 
          ?>><?php echo esc_textarea($valeurs['message']); ?></textarea>
        <?php $zone_erreur('message'); ?>
      </div>

      <!-- Piège à robots : invisible pour les humains, ignoré au clavier
			     et par les lecteurs d'écran. Un robot le remplit, un humain non. -->
      <div class="champ-piege" aria-hidden="true">
        <label for="jul-entreprise">Ne pas remplir ce champ</label>
        <input type="text" id="jul-entreprise" name="jul_entreprise" tabindex="-1" autocomplete="off" value="">
      </div>

      <div class="champ champ--case">
        <input type="checkbox" id="jul-consentement" name="jul_consentement" value="1" required
          data-champ="consentement"
          data-erreur-vide="<?php echo esc_attr($messages['consentement']['vide']); ?>"
          <?php checked($valeurs['consentement']); ?>
          <?php echo $attributs_erreur('consentement'); // phpcs:ignore WordPress.Security.EscapeOutput 
          ?>>
        <label for="jul-consentement">
          J'accepte que mes informations soient utilisées pour répondre à ma demande.
          Elles ne sont ni transmises à des tiers, ni utilisées à d'autres fins.
        </label>
        <?php $zone_erreur('consentement'); ?>
      </div>

      <?php if ($confidentialite) : ?>
        <p class="formulaire-contact__rgpd">
          <a href="<?php echo esc_url($confidentialite); ?>">Politique de confidentialité</a>
        </p>
      <?php endif; ?>

      <button type="submit" class="formulaire-contact__bouton">Envoyer mon message</button>

    </form>

  <?php endif; ?>

</section>