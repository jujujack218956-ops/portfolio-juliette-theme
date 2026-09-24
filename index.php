<?php

/**
 * Gabarit de repli universel.
 * Utilisé quand aucun gabarit plus spécifique ne correspond.
 */
get_header();
?>

<main class="site-content">

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h2>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile; ?>

    <?php the_posts_pagination(); ?>

  <?php else : ?>

    <p>Aucun contenu à afficher pour le moment.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>