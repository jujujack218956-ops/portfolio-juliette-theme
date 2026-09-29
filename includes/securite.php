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
});

add_filter('rest_endpoints', function ($endpoints) {
  if (! is_user_logged_in()) {
    unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  }
  return $endpoints;
});
