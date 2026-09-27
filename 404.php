<?php

/**
 * Page introuvable (erreur 404).
 * WordPress envoie lui-même le code 404 et le titre « Page non trouvée ».
 *
 * @package portfolio-juliette
 */

get_header();
?>

<div class="page-contenu">

  <header class="page-contenu__entete">
    <h1 class="page-contenu__titre">Page introuvable</h1>
  </header>

  <div class="entry-content">
    <p>
      Cette adresse ne mène nulle part : la page a peut-être été déplacée,
      ou l'adresse contient une faute de frappe.
    </p>
    <ul>
      <li><a href="<?php echo esc_url(home_url('/')); ?>">Revenir à l'accueil</a></li>
      <li><a href="<?php echo esc_url(get_post_type_archive_link('projet')); ?>">Voir mes réalisations</a></li>
      <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Me contacter</a></li>
    </ul>
  </div>

</div>

<?php
get_footer();
