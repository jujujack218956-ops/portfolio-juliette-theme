<?php
get_header();
?>

<div class="page-photo">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="page-photo__container">

        <div class="page-photo__description">
          <h2 class="page-photo__title"><?php the_title(); ?></h2>
          <div class="page-photo__meta">
            <?php
            $id = get_the_ID();
            $reference = get_post_meta($id, 'reference', true);
            $categories = get_the_terms($id, 'categorie');
            $formats = get_the_terms($id, 'format');
            $type = get_post_meta($id, 'type', true);
            $date = get_the_date('Y');
            $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
            $format_name   = ($formats && !is_wp_error($formats)) ? $formats[0]->name : '';
            ?>
            <p class="page-photo__reference"><span>Référence :</span> <?php echo esc_html($reference); ?></p>
            <p class="page-photo__category"><span>Catégorie :</span> <?php echo esc_html($category_name); ?></p>
            <p class="page-photo__format"><span>Format :</span> <?php echo esc_html($format_name); ?></p>
            <p class="page-photo__type"><span>Type :</span> <?php echo esc_html($type); ?></p>
            <p class="page-photo__date"><span>Année :</span> <?php echo esc_html($date); ?></p>
          </div>
        </div>
        <div class="page-photo__image">
          <?php the_post_thumbnail('full'); ?> </div>
      </div>
      <div class="page-photo__single-bottom">
        <p class="page-photo__interet">Cette photo vous intéresse? <button class="contact-photo" type="button" data-ref="<?php echo esc_attr($reference); ?>">Contact</button></p>
        <div class="card-photo">
          <?php $previous_post = get_previous_post(); ?>
          <?php $next_post = get_next_post(); ?>
          <?php if ($previous_post) : ?>
            <a href="<?php echo esc_url(get_permalink($previous_post->ID)); ?>" class="card-photo__previous" aria-label="Photo précédente"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="24" viewBox="0 0 36 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left">
                <line x1="33" y1="12" x2="3" y2="12"></line>
                <polyline points="6 8 3 12 6 16"></polyline>
              </svg>
              <div>
                <?php echo get_the_post_thumbnail($previous_post->ID, 'thumbnail', ['class' => 'card-photo__thumbnail']); ?>
              </div>
            </a>

          <?php endif; ?>
          <?php if ($next_post) : ?>
            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="card-photo__next" aria-label="Photo suivante"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="24" viewBox="0 0 36 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                <line x1="3" y1="12" x2="33" y2="12"></line>
                <polyline points="30 8 33 12 30 16"></polyline>
              </svg>
              <div>
                <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail', ['class' => 'card-photo__thumbnail']); ?>
              </div>
            </a>
          <?php endif; ?>
        </div>
      </div>
  <?php endwhile;
  endif; ?>
  <div class="related-photos">
    <p class="related-photos__title">Vous aimerez aussi</p>
    <div class="related-photos__container">
      <?php
      // Récupérer la catégorie de la photo courante
      $terms = get_the_terms(get_the_ID(), 'categorie');
      $term_ids = $terms ? wp_list_pluck($terms, 'term_id') : array();

      // Requête : 2 photos de la même catégorie, hors photo courante
      $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 2,
        'post__not_in'   => array(get_the_ID()),
        'tax_query'      => array(
          array(
            'taxonomy' => 'categorie',
            'field'    => 'term_id',
            'terms'    => $term_ids,
          ),
        ),
      );
      $related_photos = new WP_Query($args);

      // Boucle : pour chaque photo, on appelle le bloc réutilisable
      if ($related_photos->have_posts()) :
        while ($related_photos->have_posts()) : $related_photos->the_post();
          get_template_part('template-parts/photo-block', null, ['show_meta' => false]);
        endwhile;
        wp_reset_postdata();
      endif;
      ?>
    </div>
  </div>
</div>
<?php
get_footer();
?>