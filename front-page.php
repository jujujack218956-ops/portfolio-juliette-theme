<?php

/**
 * Page d'accueil.
 *
 * Le texte est saisi dans l'éditeur de la page « Accueil » (modifiable sans code).
 * Un bloc « Lire la suite » (More) placé dans l'éditeur sépare ce texte en deux :
 * ce qui est avant s'affiche au-dessus des projets, ce qui est après en dessous.
 * Les 3 derniers projets sont insérés entre les deux par ce gabarit.
 *
 * @package portfolio-juliette
 */

get_header();

while (have_posts()) :
  the_post();

  // Découpage du contenu au niveau du bloc « Lire la suite ».
  $parties = get_extended(get_post_field('post_content', get_the_ID()));

  // Retire les délimiteurs du bloc More restés de part et d'autre de la coupure.
  $avant = preg_replace('#<!-- /?wp:more[^>]*-->#', '', $parties['main']);
  $apres = preg_replace('#<!-- /?wp:more[^>]*-->#', '', $parties['extended']);
?>

  <div class="accueil">

    <header class="accueil__hero">
      <h1 class="accueil__titre"><?php the_title(); ?></h1>
    </header>

    <?php if (trim($avant)) : ?>
      <div class="accueil__contenu entry-content">
        <?php echo apply_filters('the_content', $avant); // phpcs:ignore WordPress.Security.EscapeOutput -- contenu de l'éditeur, filtré par WordPress. 
        ?>
      </div>
    <?php endif; ?>

    <?php
    // Les 3 derniers projets. no_found_rows : pas de pagination,
    // donc WordPress n'a pas à compter tous les projets (une requête SQL en moins).
    $projets = new WP_Query(
      array(
        'post_type'      => 'projet',
        'posts_per_page' => 3,
        'no_found_rows'  => true,
      )
    );
    ?>

    <?php if ($projets->have_posts()) : ?>
      <section class="accueil__projets" aria-labelledby="titre-projets">
        <div class="accueil__projets-entete">
          <h2 id="titre-projets">Quelques projets récents</h2>
          <a href="<?php echo esc_url(get_post_type_archive_link('projet')); ?>">Toutes mes réalisations</a>
        </div>

        <div class="archive-projet__grille">
          <?php
          while ($projets->have_posts()) :
            $projets->the_post();
            // Titres des cartes en H3 : ils sont sous le H2 de la section.
            get_template_part('template-parts/projet-block', null, array('niveau_titre' => 3));
          endwhile;
          wp_reset_postdata(); // Rend la main à la page Accueil.
          ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if (trim($apres)) : ?>
      <div class="accueil__contenu entry-content">
        <?php echo apply_filters('the_content', $apres); // phpcs:ignore WordPress.Security.EscapeOutput 
        ?>
      </div>
    <?php endif; ?>

  </div>

<?php
endwhile;

get_footer();
