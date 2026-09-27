# Portfolio Juliette — thème WordPress sur mesure

Thème WordPress écrit à la main pour le portfolio de **Juliette Béthery**
(Agence Bethauv, Côte-d'Or) : présentation, réalisations, contact.

**Aucune extension, aucun page builder** : types de contenus, champs,
formulaire, options et référencement sont codés dans le thème.

Projet 13 du parcours « Développeur WordPress » d'OpenClassrooms.

![Aperçu du thème](screenshot.png)

- Site en ligne : *à venir* — `portfolio.agence-bethauv.fr`
- Auteure : Juliette Béthery — [agence-bethauv.fr](https://agence-bethauv.fr)

---

## Ce que fait le thème

| Fonction | Où | En bref |
|---|---|---|
| Type de contenu **Projet** | `includes/cpt-projet.php` | Réalisations ajoutées et modifiées depuis l'administration, archive `/realisations/` |
| Taxonomie **Type de projet** | `includes/taxonomie-type-projet.php` | Hiérarchique ; filtres de l'archive en simples liens, **sans JavaScript** |
| **Champs** de la fiche projet | `includes/metabox-projet.php` | 6 champs (contexte, année, technologies, liens, résultat) : nonce, droits, assainissement à l'entrée, échappement à la sortie |
| **Accueil éditable** | `front-page.php` | Texte saisi dans l'éditeur ; le bloc « Lire la suite » place les 3 derniers projets |
| **Formulaire de contact** | `includes/traitement-contact.php`, `template-parts/formulaire-contact.php`, `js/contact.js` | Voir ci-dessous |
| **Lightbox** des captures | `js/lightbox.js` | Élément `<dialog>` natif, clavier, focus rendu ; chargée sur les fiches projet seulement |
| **Méta-description** | `includes/meta-description.php` | Générée depuis l'extrait de chaque page ou projet |
| **Écoscore** | `includes/ecoscore.php` | Réglable dans Apparence › Personnaliser ; affiché seulement s'il a été mesuré |
| **Palette et tailles** | `theme.json` | L'éditeur ne propose que les couleurs de la charte |
| Page **404** | `404.php` | Message clair et trois liens de retour |

## Formulaire de contact, sans extension

- Traitement sur `template_redirect`, puis redirection (Post/Redirect/Get) :
  pas de double envoi en rechargeant la page.
- Protections anti-spam **sans reCAPTCHA** (donc sans traceur tiers) :
  nonce, champ piège invisible, délai minimal de remplissage.
- Validation côté serveur ; validation JavaScript en **amélioration
  progressive** (le formulaire fonctionne sans JS).
- Erreurs reliées aux champs (`aria-describedby`, `aria-invalid`), résumé
  annoncé aux lecteurs d'écran, titre de page préfixé « Erreur — ».
- Envoi par `wp_mail()`, adresse du visiteur en `Reply-To`.
  **Aucune donnée n'est enregistrée en base.**

## Choix techniques

**Accessibilité (WCAG 2.1 AA visé)**
- Lien d'évitement, un seul `<main>`, un seul H1 par page.
- Contrastes vérifiés (titres 12,2:1, texte 7,5:1, liens 7,6:1) ; l'orange
  de la charte (3,1:1) ne sert qu'au décor.
- Menu mobile : `inert`, focus géré, touche Échap.
- Préférences système respectées : `prefers-reduced-motion`,
  `prefers-reduced-transparency`.

**Sobriété (éco-conception)**
- 1 feuille de style ; en JavaScript, seul le menu mobile est chargé
  partout ; le formulaire et la lightbox ne le sont que sur leur page
  (`defer`).
- Polices auto-hébergées en WOFF2, sous-ensemble latin (43 Ko pour 3 graisses).
- Images en WebP ; aucune ressource externe (ni Google Fonts, ni CDN, ni carte).
- Effets en CSS seul : en-tête verre (`backdrop-filter`), transitions entre
  les pages (API View Transitions).

**Sécurité**
- Nonces et vérification des droits sur chaque enregistrement.
- `sanitize_*` à l'entrée, `esc_*` à la sortie.
- Aucun identifiant ni mot de passe dans le code.

## Structure

```
portfolio-juliette/
├── style.css, theme.json, functions.php
├── header.php, footer.php, index.php, 404.php
├── front-page.php, page.php, page-contact.php
├── archive-projet.php, taxonomy-type_projet.php, single-projet.php
├── includes/        CPT, taxonomie, champs, formulaire, méta, écoscore
├── template-parts/  carte projet, formulaire
├── js/              menu mobile, formulaire, lightbox
└── assets/          polices WOFF2 (+ licences), logo WebP
```

## Installation

1. Copier le dossier `portfolio-juliette` dans `wp-content/themes/`.
2. Activer le thème (Apparence › Thèmes).
3. Réglages › Permaliens › Enregistrer (active l'adresse `/realisations/`).
4. Créer une page avec le slug `contact` (formulaire) et une page d'accueil
   statique (Réglages › Lecture).

Prérequis : WordPress 6.6 ou plus, PHP 8.2 ou plus.

## Crédits et licences

- Code : © Juliette Béthery, licence GPL v2 ou ultérieure (comme WordPress).
- Point de départ : mon thème du projet 11 OpenClassrooms (Nathalie Mota),
  entièrement réécrit ; la mise en page de Mota n'est pas reprise.
- Polices : Roboto (Apache 2.0), Maven Pro (SIL Open Font License) —
  licences dans `assets/fonts/`.
