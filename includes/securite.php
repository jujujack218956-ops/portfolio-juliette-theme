<?php

/**
 * Sécurité : ne pas exposer l'identifiant de connexion.
 * - Pages auteur redirigées vers l'accueil (site à autrice unique).
 * - Liste des utilisateurs retirée de l'API REST pour les visiteurs non connectés.
 */
defined('ABSPATH') || exit;
add_action('template_redirect', function () {
  if (is_author()) {
    wp_safe_redirect(home_url('/'), 301);
    exit;
  }
}, 1);

add_filter('rest_endpoints', function ($endpoints) {
  if (! is_user_logged_in()) {
    unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  }
  return $endpoints;
});

/*
 * Allègement du <head> : pas de script emoji (requête inutile, images
 * chargées depuis s.w.org sur les anciens navigateurs), pas de numéro
 * de version de WordPress (en-tête et flux RSS).
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
remove_action('wp_print_styles', 'print_emoji_styles');
add_filter('the_generator', '__return_empty_string');
