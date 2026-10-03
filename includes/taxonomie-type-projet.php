<?php

/**
 * Déclaration de la taxonomie « Type de projet ».
 *
 * Remplace le plugin CPT UI : la configuration vit ici, dans le thème,
 * donc elle est versionnée et voyage avec lui.
 */

// Empêche l'exécution du fichier en dehors de WordPress.
if (! defined('ABSPATH')) {
  exit;
}

/**
 * Enregistre le type de taxonomie de projet.
 */
function portfolio_juliette_taxonomie_projet()
{
  $etiquettes = array(
    'name'               => 'Types de projets',
    'singular_name'      => 'Type de projet',
    'menu_name'          => 'Types de projets',
    'all_items'          => 'Tous les types de projets',
    'add_new_item'       => 'Ajouter un type de projet',
    'edit_item'          => 'Modifier le type de projet',
    'view_item'          => 'Voir le type de projet',
    'search_items'       => 'Rechercher un type de projet',
    'not_found'          => 'Aucun type de projet trouvé',
  );

  $arguments = array(
    'labels'            => $etiquettes,
    'public'            => true,
    'hierarchical'      => true,
    'show_admin_column' => true,
    'rewrite'           => array('slug' => 'types-projets'),
    'show_in_rest'      => true,
  );

  register_taxonomy('type_projet', 'projet', $arguments);
}
add_action('init', 'portfolio_juliette_taxonomie_projet');
