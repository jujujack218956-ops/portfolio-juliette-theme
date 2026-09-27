<?php

/**
 * Déclaration du type de contenu « Projet ».
 *
 * Remplace le plugin CPT UI : la configuration vit ici, dans le thème,
 * donc elle est versionnée et voyage avec lui.
 */

// Empêche l'exécution du fichier en dehors de WordPress.
if (! defined('ABSPATH')) {
  exit;
}

/**
 * Enregistre le type de contenu « projet ».
 */
function portfolio_juliette_cpt_projet()
{
  $etiquettes = array(
    'name'               => 'Projets',
    'singular_name'      => 'Projet',
    'menu_name'          => 'Projets',
    'all_items'          => 'Tous les projets',
    'add_new'            => 'Ajouter',
    'add_new_item'       => 'Ajouter un projet',
    'edit_item'          => 'Modifier le projet',
    'new_item'           => 'Nouveau projet',
    'view_item'          => 'Voir le projet',
    'search_items'       => 'Rechercher un projet',
    'not_found'          => 'Aucun projet trouvé',
    'not_found_in_trash' => 'Aucun projet dans la corbeille',
  );

  $arguments = array(
    'description'  => "Mes réalisations WordPress : intégration de maquette, animations, débogage. Pour chaque projet : le contexte, la démarche et le code source.",
    'labels'        => $etiquettes,
    'public'        => true,
    'has_archive'   => 'realisations',
    'rewrite'       => array('slug' => 'realisations'),
    'menu_icon'     => 'dashicons-portfolio',
    'menu_position' => 5,
    'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
    'show_in_rest'  => true,
  );

  register_post_type('projet', $arguments);
}
add_action('init', 'portfolio_juliette_cpt_projet');
