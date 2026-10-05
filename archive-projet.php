<?php

/**
 * Liste des réalisations : archive du CPT « projet » (/realisations/).
 * Sert aussi aux pages de type de projet via taxonomy-type_projet.php.
 *
 * @package portfolio-juliette
 */

get_header();

$lien_archive = get_post_type_archive_link('projet');
$types        = get_terms(
  array(
    'taxonomy'   => 'type_projet',
    'hide_empty' => true, // Pas de filtre qui mène à une page vide.
  )
);
?>

<div class="archive-projet">

  <header class="archive-projet__entete">
    <h1 class="archive-projet__titre">
      <?php
      if (is_tax('type_projet')) {
        echo 'Réalisations : ' . esc_html(single_term_title('', false));
      } else {
        echo 'Mes réalisations';
      }
      ?>
    </h1>
    <p class="archive-projet__intro">
      Chaque projet part d'une situation concrète et d'un besoin précis. Voici ce que
      j'ai fait, comment, et ce que ça a changé.
    </p>
  </header>

  <?php if ($types && ! is_wp_error($types)) : ?>
    <nav class="archive-projet__filtres" aria-label="Filtrer par type de projet">
      <ul>
        <li>
          <a href="<?php echo esc_url($lien_archive); ?>" <?php echo is_post_type_archive('projet') ? ' aria-current="page"' : ''; ?>>Tous</a>
        </li>
        <?php foreach ($types as $type) : ?>
          <li>
            <a href="<?php echo esc_url(get_term_link($type)); ?>" <?php echo is_tax('type_projet', $type->term_id) ? ' aria-current="page"' : ''; ?>>
              <?php echo esc_html($type->name); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  <?php endif; ?>

  <?php if (have_posts()) : ?>

    <div class="archive-projet__grille">
      <?php
      while (have_posts()) :
        the_post();
        get_template_part('template-parts/projet-block');
      endwhile;
      ?>
    </div>

    <?php the_posts_pagination(); ?>

  <?php else : ?>

    <p>Aucun projet pour le moment.</p>

  <?php endif; ?>

</div>

<?php
get_footer();
