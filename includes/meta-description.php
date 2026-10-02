<?php

// Empêche l'exécution du fichier en dehors de WordPress.
if (! defined('ABSPATH')) {
  exit;
}
/**
 * Méta-description de chaque page, générée par le thème (sans extension SEO).
 *
 * Source, dans l'ordre :
 * 1. l'extrait saisi dans l'administration (pages et projets) ;
 * 2. la description du type de contenu (liste des réalisations) ou du type de projet ;
 * 3. à défaut, le slogan du site (Réglages › Général).
 *
 * @package portfolio-juliette
 */

// Les pages (Accueil, Contact…) reçoivent elles aussi un champ « Extrait ».
function portfolio_juliette_extrait_pages()
{
  add_post_type_support('page', 'excerpt');
}
add_action('init', 'portfolio_juliette_extrait_pages');

function portfolio_juliette_meta_description()
{
  $texte = '';

  if (is_singular()) {
    $id = get_queried_object_id();
    // has_excerpt() : seulement un extrait écrit à la main, jamais le début du contenu.
    if (has_excerpt($id)) {
      $texte = get_the_excerpt($id);
    }
  } elseif (is_post_type_archive('projet')) {
    $texte = get_the_post_type_description();
  } elseif (is_tax('type_projet')) {
    $texte = term_description();
  }

  if ('' === trim(wp_strip_all_tags($texte))) {
    $texte = get_bloginfo('description');
  }

  // Texte brut, 155 caractères au plus (au-delà, Google coupe l'affichage).
  $texte = wp_html_excerpt($texte, 155, '…');

  if ('' === $texte) {
    return;
  }

  printf('<meta name="description" content="%s">' . "\n", esc_attr($texte));
}
add_action('wp_head', 'portfolio_juliette_meta_description', 1);
