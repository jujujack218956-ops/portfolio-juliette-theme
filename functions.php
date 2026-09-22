<?php
// Chargement du thème
function nathalie_mota_setup()
{
  add_theme_support('title-tag');
  register_nav_menu('main-menu', 'Menu principal');
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// Chargement des styles et scripts
function nathalie_mota_enqueue()
{
  wp_enqueue_style(
    'nathalie-mota-style',
    get_stylesheet_uri(),
    [],
    '1.0.0'
  );


  wp_enqueue_script(
    'nathalie-mota-scripts',
    get_stylesheet_directory_uri() . '/js/scripts.js',
    [],
    '1.0.0',
    true
  );

  if (is_front_page()) {
    wp_enqueue_script(
      'nathalie-mota-home-scripts',
      get_stylesheet_directory_uri() . '/js/home.js',
      [],
      '1.0.0',
      true
    );

    wp_localize_script('nathalie-mota-home-scripts', 'nathalie_mota_home', array(
      'rest_url' => rest_url(),
    ));
  }

  // Lightbox : accueil + page d'une photo (photos apparentées)
  if (is_front_page() || is_singular('photo')) {
    wp_enqueue_script(
      'nathalie-mota-lightbox',
      get_stylesheet_directory_uri() . '/js/lightbox.js',
      [],
      '1.0.0',
      true
    );
  }
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue');

add_action('rest_api_init', function () {
  register_rest_field('photo', 'reference', array(
    'get_callback' => function ($post) {
      return get_post_meta($post['id'], 'reference', true);
    },
  ));
});


add_action('rest_api_init', function () {
  register_rest_field('photo', 'image_url', array(
    'get_callback' => function ($post) {
      $thumbnail_id = get_post_thumbnail_id($post['id']);
      if ($thumbnail_id) {
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'medium_large');
        return $thumbnail_url ? $thumbnail_url[0] : null;
      }
      return null;
    },
  ));
});


add_action('rest_api_init', function () {
  register_rest_field('photo', 'image_full', array(
    'get_callback' => function ($post) {
      $thumbnail_id = get_post_thumbnail_id($post['id']);
      return $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : null;
    },
  ));
});


add_action('rest_api_init', function () {
  register_rest_field('photo', 'categorie', array(
    'get_callback' => function ($post) {
      $terms = get_the_terms($post['id'], 'categorie');
      if ($terms && !is_wp_error($terms)) {
        return $terms[0]->name;
      }
      return null;
    },
  ));
});
