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
 * Affiche le contenu de l'encadré.
 *
 * @param WP_Post $post Le projet en cours d'édition.
 */
function portfolio_juliette_afficher_metabox($post)
{
  // Jeton de sécurité : prouve que le formulaire vient bien d'ici.
  wp_nonce_field('jul_enregistrer_projet', 'jul_nonce_projet');

  // Valeur déjà enregistrée, s'il y en a une.
  $annee = get_post_meta($post->ID, '_jul_annee', true);
?>
  <p>
    <label for="jul_annee">Année</label><br>
    <input type="text"
      id="jul_annee"
      name="jul_annee"
      value="<?php echo esc_attr($annee); ?>"
      class="widefat"
      maxlength="4">
  </p>
<?php
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

  // 4. Assainissement, puis enregistrement.
  if (isset($_POST['jul_annee'])) {
    $annee = sanitize_text_field(wp_unslash($_POST['jul_annee']));
    update_post_meta($post_id, '_jul_annee', $annee);
  }
}
add_action('save_post_projet', 'portfolio_juliette_enregistrer_metabox');
