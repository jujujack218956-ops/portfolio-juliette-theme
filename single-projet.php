<?php

/**
 * Gabarit d'une fiche projet (CPT « projet »).
 *
 * @package portfolio-juliette
 */

get_header();
?>

<div class="page-projet">

  <?php while (have_posts()) : the_post(); ?>

    <?php
    // Métadonnées enregistrées par includes/metabox-projet.php.
    $id       = get_the_ID();
    $contexte = get_post_meta($id, '_jul_contexte', true);
    $annee    = get_post_meta($id, '_jul_annee', true);
    $technos  = get_post_meta($id, '_jul_technos', true);
    $url_site = get_post_meta($id, '_jul_url_site', true);
    $url_code = get_post_meta($id, '_jul_url_code', true);
    $resultat = get_post_meta($id, '_jul_resultat', true);

    // Types de projet (taxonomie) : get_the_terms() renvoie false ou WP_Error si rien.
    $types = get_the_terms($id, 'type_projet');
    ?>

    <article class="page-projet__container">

      <div class="page-projet__description">

        <?php if ($types && ! is_wp_error($types)) : ?>
          <p class="page-projet__types">
            <?php echo esc_html(implode(' · ', wp_list_pluck($types, 'name'))); ?>
          </p>
        <?php endif; ?>

        <h1 class="page-projet__title"><?php the_title(); ?></h1>

        <dl class="page-projet__meta">
          <?php if ($annee) : ?>
            <dt>Année</dt>
            <dd><?php echo esc_html($annee); ?></dd>
          <?php endif; ?>

          <?php if ($technos) : ?>
            <dt>Technologies</dt>
            <dd><?php echo esc_html($technos); ?></dd>
          <?php endif; ?>
        </dl>

        <?php if ($contexte) : ?>
          <h2>Contexte</h2>
          <p><?php echo nl2br(esc_html($contexte)); ?></p>
        <?php endif; ?>

        <div class="page-projet__contenu">
          <?php the_content(); ?>
        </div>

        <?php if ($resultat) : ?>
          <h2>Résultat</h2>
          <p><?php echo nl2br(esc_html($resultat)); ?></p>
        <?php endif; ?>

        <?php if ($url_site || $url_code) : ?>
          <ul class="page-projet__liens">
            <?php if ($url_site) : ?>
              <li><a href="<?php echo esc_url($url_site); ?>">Voir le site en ligne</a></li>
            <?php endif; ?>
            <?php if ($url_code) : ?>
              <li><a href="<?php echo esc_url($url_code); ?>">Voir le code source</a></li>
            <?php endif; ?>
          </ul>
        <?php endif; ?>

      </div>

      <?php if (has_post_thumbnail()) : ?>
        <div class="page-projet__image">
          <?php the_post_thumbnail('large', array(
            'style' => 'view-transition-name: projet-' . get_the_ID(),
          )); ?>
        </div>
      <?php endif; ?>

    </article>

    <div class="page-projet__single-bottom">

      <p class="page-projet__interet">
        Un projet similaire ?
        <a class="page-projet__cta" href="<?php echo esc_url(home_url('/contact/')); ?>">Parlons-en</a>
      </p>

      <?php
      $previous_post = get_previous_post();
      $next_post     = get_next_post();
      ?>

      <?php if ($previous_post || $next_post) : ?>
        <nav class="card-projet" aria-label="Autres projets">

          <?php if ($previous_post) : ?>
            <a href="<?php echo esc_url(get_permalink($previous_post)); ?>" class="card-projet__previous">
              <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="36" height="24" viewBox="0 0 36 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <line x1="33" y1="12" x2="3" y2="12"></line>
                <polyline points="6 8 3 12 6 16"></polyline>
              </svg>
              <span>Projet précédent : <?php echo esc_html(get_the_title($previous_post)); ?></span>
            </a>
          <?php endif; ?>

          <?php if ($next_post) : ?>
            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="card-projet__next">
              <span>Projet suivant : <?php echo esc_html(get_the_title($next_post)); ?></span>
              <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="36" height="24" viewBox="0 0 36 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="33" y2="12"></line>
                <polyline points="30 8 33 12 30 16"></polyline>
              </svg>
            </a>
          <?php endif; ?>

        </nav>
      <?php endif; ?>

    </div>

  <?php endwhile; ?>

</div>

<?php
get_footer();
