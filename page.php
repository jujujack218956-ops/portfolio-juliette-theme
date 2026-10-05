<?php

/**
 * Pages classiques : Mentions légales,
 * Politique de confidentialité.
 *
 * @package portfolio-juliette
 */

get_header();

while (have_posts()) :
  the_post();
?>

  <article id="post-<?php the_ID(); ?>" <?php post_class('page-contenu'); ?>>

    <header class="page-contenu__entete">
      <h1 class="page-contenu__titre"><?php the_title(); ?></h1>
    </header>

    <?php if (has_post_thumbnail()) : ?>
      <div class="page-contenu__image">
        <?php the_post_thumbnail('large'); ?>
      </div>
    <?php endif; ?>

    <div class="entry-content">
      <?php
      the_content();

      // Si une page est découpée avec le bloc « Saut de page ».
      wp_link_pages(
        array(
          'before' => '<nav class="page-contenu__pages" aria-label="Pages de cet article">',
          'after'  => '</nav>',
        )
      );
      ?>
    </div>

  </article>

<?php
endwhile;

get_footer();
