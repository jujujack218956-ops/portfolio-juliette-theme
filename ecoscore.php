<?php

// Empêche l'exécution du fichier en dehors de WordPress.
if (! defined('ABSPATH')) {
  exit;
}
/**
 * Écoscore réglable dans Apparence › Personnaliser › Écoscore.
 * Les valeurs sont affichées dans le pied de page (footer.php)
 * et peuvent être reprises dans un texte grâce à des codes courts.
 *
 * @package portfolio-juliette
 */

// La note EcoIndex va de A à G : toute autre saisie est refusée (champ vidé).
function portfolio_juliette_nettoyer_note_ecoindex($valeur)
{
  $valeur = strtoupper(trim($valeur));
  return preg_match('/^[A-G]$/', $valeur) ? $valeur : '';
}

function portfolio_juliette_personnaliser_ecoscore($wp_customize)
{
  $wp_customize->add_section('portfolio_juliette_ecoscore', array(
    'title'       => 'Écoscore (pied de page)',
    'description' => "Valeurs MESURÉES sur le site en ligne (page d'accueil), avec EcoIndex. "
      . "À re-mesurer après chaque ajout de page. Tant que le poids ou la note est vide, "
      . "la ligne ne s'affiche pas.",
    'priority'    => 160,
  ));

  // Clé => libellé, exemple, fonction de nettoyage.
  $champs = array(
    'poids' => array("Poids de la page d'accueil", 'ex. 312 Ko', 'sanitize_text_field'),
    'note'  => array('Note EcoIndex (A à G)', 'ex. A', 'portfolio_juliette_nettoyer_note_ecoindex'),
    'date'  => array('Date de la mesure', 'ex. octobre 2026', 'sanitize_text_field'),
  );

  foreach ($champs as $cle => $infos) {
    $reglage = 'portfolio_juliette_ecoscore_' . $cle;

    $wp_customize->add_setting($reglage, array(
      'default'           => '',
      'sanitize_callback' => $infos[2],
    ));

    $wp_customize->add_control($reglage, array(
      'label'       => $infos[0],
      'section'     => 'portfolio_juliette_ecoscore',
      'type'        => 'text',
      'input_attrs' => array('placeholder' => $infos[1]),
    ));
  }
}
add_action('customize_register', 'portfolio_juliette_personnaliser_ecoscore');

// Lecture d'une valeur : une seule fonction, utilisée partout.
function portfolio_juliette_ecoscore($cle)
{
  return (string) get_theme_mod('portfolio_juliette_ecoscore_' . $cle, '');
}

// [ecoscore_poids] : le poids mesuré, dans n'importe quel texte de l'éditeur.
function portfolio_juliette_code_court_poids()
{
  return esc_html(portfolio_juliette_ecoscore('poids'));
}
add_shortcode('ecoscore_poids', 'portfolio_juliette_code_court_poids');

// [si_ecoscore]…[/si_ecoscore] : n'affiche la phrase que si le poids est renseigné.
function portfolio_juliette_code_court_si_ecoscore($attributs, $contenu = '')
{
  if ('' === portfolio_juliette_ecoscore('poids')) {
    return '';
  }
  return do_shortcode($contenu);
}
add_shortcode('si_ecoscore', 'portfolio_juliette_code_court_si_ecoscore');
