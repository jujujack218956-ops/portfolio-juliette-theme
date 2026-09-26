<?php

/**
 * Page Contact (slug « contact »).
 *
 * Hiérarchie des gabarits : WordPress choisit page-{slug}.php avant page.php.
 * Ce fichier sert donc automatiquement la page dont le slug est « contact ».
 *
 * Le texte d'introduction et les coordonnées sont saisis dans l'éditeur ;
 * le formulaire est ajouté par le gabarit.
 *
 * @package portfolio-juliette
 */

get_header();

while (have_posts()) :
  the_post();
?>

  <article id="post-<?php the_ID(); ?>" <?php post_class('page-contenu page-contact'); ?>>

    <header class="page-contenu__entete">
      <h1 class="page-contenu__titre"><?php the_title(); ?></h1>
      <?php edit_post_link('Modifier cette page', '<p class="page-contenu__modifier">', '</p>'); ?>
    </header>

    <div class="page-contact__grille">

      <div class="entry-content page-contact__texte">
        <?php the_content(); ?>
      </div>

      <?php get_template_part('template-parts/formulaire-contact'); ?>

    </div>

  </article>

<?php
endwhile;

get_footer();
