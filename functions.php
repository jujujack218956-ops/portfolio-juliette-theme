<?php

/**
 * Fonctions du thème portfolio-juliette.
 *
 * @package portfolio-juliette
 */

// Fonctionnalités du thème.
function portfolio_juliette_setup()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

  register_nav_menu('main-menu', 'Menu principal');
}
add_action('after_setup_theme', 'portfolio_juliette_setup');

// Styles et scripts : uniquement ce qui sert.
function portfolio_juliette_enqueue()
{
  // La version suit la date de modification du fichier :
  // le navigateur recharge le fichier seulement quand il a changé.
  wp_enqueue_style(
    'portfolio-juliette-style',
    get_stylesheet_uri(),
    array(),
    filemtime(get_theme_file_path('style.css'))
  );

  // Menu burger et modale.
  wp_enqueue_script(
    'portfolio-juliette-scripts',
    get_theme_file_uri('js/scripts.js'),
    array(),
    filemtime(get_theme_file_path('js/scripts.js')),
    true
  );
}
add_action('wp_enqueue_scripts', 'portfolio_juliette_enqueue');

// Préchargement des 2 polices affichées dès l'arrivée sur la page
// (titres et texte courant). Roboto Bold se charge ensuite, à la demande.
function portfolio_juliette_precharger_polices()
{
  $polices = array('MavenPro-SemiBold.woff2', 'Roboto-Regular.woff2');
  foreach ($polices as $police) {
    printf(
      '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
      esc_url(get_theme_file_uri('assets/fonts/' . $police))
    );
  }
}
add_action('wp_head', 'portfolio_juliette_precharger_polices', 1);

// Type de contenu « Projet ».
require_once get_theme_file_path('includes/cpt-projet.php');

// Taxonomie « Type de projet ».
require_once get_theme_file_path('includes/taxonomie-type-projet.php');

// Champs personnalisés des fiches projet.
require_once get_theme_file_path('includes/metabox-projet.php');
