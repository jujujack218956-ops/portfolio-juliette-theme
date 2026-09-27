<?php

/**
 * Gabarit de repli : utilisé quand aucun gabarit plus précis n'existe
 * (article isolé, liste d'articles, résultats de recherche, archives…).
 * Le site n'a pas de blog : ce gabarit reste simple, mais propre et accessible.
 * Il remplace aussi l'ancien single.php hérité de Mota.
 *
 * Pas de <main> ici : header.php l'ouvre, footer.php le ferme.
 *
 * @package portfolio-juliette
 */

get_header();
?>

<div class="page-contenu">

  <?php if (is_singular()) : ?>

    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="page-contenu__entete">
          <h1 class="page-contenu__titre"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>

  <?php else : ?>

    <header class="page-contenu__entete">
      <h1 class="page-contenu__titre">
        <?php
        if (is_search()) {
          printf('Résultats pour « %s »', esc_html(get_search_query()));
        } elseif (is_archive()) {
          echo esc_html(wp_strip_all_tags(get_the_archive_title()));
        } else {
          echo 'Articles';
        }
        ?>
      </h1>
    </header>

    <?php if (have_posts()) : ?>

      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php the_excerpt(); ?>
        </article>
      <?php endwhile; ?>

      <?php the_posts_pagination(); ?>

    <?php else : ?>

      <p>Aucun contenu à afficher pour le moment.</p>

    <?php endif; ?>

  <?php endif; ?>

</div>

<?php
get_footer();
