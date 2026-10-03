<?php

/**
 * Champs personnalisés des fiches projet.
 */

if (! defined('ABSPATH')) {
  exit;
}

/**
 * Déclare l'encadré sur l'écran d'édition des projets.
 */
function portfolio_juliette_ajouter_metabox()
{
  add_meta_box(
    'jul_infos_projet',              // identifiant de l'encadré
    'Informations du projet',        // titre affiché
    'portfolio_juliette_afficher_metabox', // fonction qui affiche le contenu
    'projet',                        // type de contenu concerné
    'normal',                        // emplacement : sous l'éditeur
    'high'                           // priorité d'affichage
  );
}
add_action('add_meta_boxes', 'portfolio_juliette_ajouter_metabox');



/**
 * Définition des champs personnalisés du projet.
 *
 * La clé est le nom du post meta ; le nom du champ HTML s'en déduit
 * en retirant l'underscore initial.
 */
function portfolio_juliette_champs_projet()
{
  return array(
    '_jul_contexte' => array('libelle' => 'Contexte / client',    'type' => 'textarea'),
    '_jul_annee'    => array('libelle' => 'Année',                'type' => 'text'),
    '_jul_technos'  => array('libelle' => 'Technologies (séparées par des virgules)', 'type' => 'text'),
    '_jul_url_site' => array('libelle' => 'Lien du site en ligne', 'type' => 'url'),
    '_jul_url_code' => array('libelle' => 'Lien du code source',   'type' => 'url'),
    '_jul_resultat' => array('libelle' => 'Résultat / bénéfice',   'type' => 'textarea'),
  );
}

function portfolio_juliette_afficher_metabox($post)
{
  wp_nonce_field('jul_enregistrer_projet', 'jul_nonce_projet');

  /**
   * Affiche le contenu de l'encadré.
   *
   * @param WP_Post $post Le projet en cours d'édition.
   */
  foreach (portfolio_juliette_champs_projet() as $cle => $champ) {
    $valeur = get_post_meta($post->ID, $cle, true);
    $nom    = ltrim($cle, '_'); // _jul_annee devient jul_annee
?>
    <p>
      <label for="<?php echo esc_attr($nom); ?>">
        <strong><?php echo esc_html($champ['libelle']); ?></strong>
      </label><br>

      <?php if ('textarea' === $champ['type']) : ?>
        <textarea id="<?php echo esc_attr($nom); ?>"
          name="<?php echo esc_attr($nom); ?>"
          rows="3"
          class="widefat"><?php echo esc_textarea($valeur); ?></textarea>
      <?php else : ?>
        <input type="<?php echo esc_attr($champ['type']); ?>"
          id="<?php echo esc_attr($nom); ?>"
          name="<?php echo esc_attr($nom); ?>"
          value="<?php echo esc_attr($valeur); ?>"
          class="widefat">
      <?php endif; ?>
    </p>
<?php
  }
}

/**
 * Enregistre les champs du projet.
 *
 * @param int $post_id Identifiant du contenu enregistré.
 */
function portfolio_juliette_enregistrer_metabox($post_id)
{
  // 1. Le formulaire vient-il bien de notre metabox ?
  if (
    ! isset($_POST['jul_nonce_projet'])
    || ! wp_verify_nonce($_POST['jul_nonce_projet'], 'jul_enregistrer_projet')
  ) {
    return;
  }

  // 2. S'agit-il d'un enregistrement automatique ?
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 3. L'utilisateur a-t-il le droit de modifier ce contenu ?
  if (! current_user_can('edit_post', $post_id)) {
    return;
  }

  foreach (portfolio_juliette_champs_projet() as $cle => $champ) {
    $nom = ltrim($cle, '_');

    if (! isset($_POST[$nom])) {
      continue;
    }

    $valeur = wp_unslash($_POST[$nom]);

    // L'assainissement dépend de la nature de la donnée.
    switch ($champ['type']) {
      case 'url':
        $valeur = esc_url_raw($valeur);
        break;
      case 'textarea':
        $valeur = sanitize_textarea_field($valeur);
        break;
      default:
        $valeur = sanitize_text_field($valeur);
    }

    update_post_meta($post_id, $cle, $valeur);
  }
}
add_action('save_post_projet', 'portfolio_juliette_enregistrer_metabox');
