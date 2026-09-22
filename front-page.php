<?php
get_header();
?>

<main class="home-main">
  <div class="home-hero">
    <?php $image_hero = get_field('image_hero', 'option'); ?>
    <?php if ($image_hero) : ?>
      <?php echo wp_get_attachment_image($image_hero['ID'], 'large'); ?>
    <?php endif; ?>
    <h1 class="home-hero__title">photographe event</h1>
  </div>

  <div class="filter">
    <div class="filter__group">
      <?php $categories = get_terms(array(
        'taxonomy'   => 'categorie',
        'hide_empty' => true,
      )); ?>
      <select id="filter-category" name="filter-category" aria-label="Filtrer par catégorie">
        <option value="">catégories</option>
        <?php foreach ($categories as $category) : ?>
          <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
        <?php endforeach; ?>
      </select>

      <?php $formats = get_terms(array(
        'taxonomy'   => 'format',
        'hide_empty' => true,
      )); ?>
      <select id="filter-format" name="filter-format" aria-label="Filtrer par format">
        <option value="">formats</option>
        <?php foreach ($formats as $format) : ?>
          <option value="<?php echo esc_attr($format->term_id); ?>"><?php echo esc_html($format->name); ?></option> <?php endforeach; ?>
      </select>
    </div>

    <select id="filter-sort" class="filter__sort" name="filter-sort" aria-label="Trier par date croissante ou décroissante">
      <option value="">trier par</option>
      <option value="desc">A partir des plus récentes</option>
      <option value="asc">A partir des plus anciennes</option>
    </select>
  </div>

  <?php
  $args = array(
    'post_type'      => 'photo',
    'posts_per_page' => 8,
    'orderby'        => 'date',
    'order'          => 'DESC',
  );

  $query = new WP_Query($args);
  ?>

  <!-- Boucle : pour chaque photo, on appelle le bloc réutilisable -->
  <div class="home-photos">
    <?php
    if ($query->have_posts()) :
      while ($query->have_posts()) : $query->the_post();
        get_template_part('template-parts/photo-block', null, ['show_meta' => true]);
      endwhile;
      wp_reset_postdata();
    endif;
    ?>
  </div>
  <button class="load-more" id="load-more" data-offset="8" aria-label="Charger plus de photos">Charger plus</button>
</main>

<?php
get_footer();
?>