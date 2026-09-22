<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <!-- Header principal — visible par défaut -->
  <header class="site-header">
    <div class="site-header__inner">
      <a href="<?php echo esc_url(home_url()); ?>">
        <img src="<?php echo esc_attr(get_theme_file_uri('assets/images/logo.svg')); ?>" alt="Nathalie Mota">
      </a>
      <nav>
        <?php wp_nav_menu([
          'theme_location' => 'main-menu',
          'container' => false,
          'menu_class' => 'main-menu',
        ]); ?>
      </nav>
      <!-- Bouton burger — visible uniquement sur mobile -->
      <button class="menu-toggle" aria-controls="main-menu" aria-expanded="false">
        <span class="sr-only">Ouvrir le menu mobile</span>
        <span class="hamburger"><svg width="28" height="19" viewBox="0 0 28 19" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.856708 1.71342H26.5586C27.0315 1.71342 27.4153 1.32957 27.4153 0.856708C27.4153 0.383774 27.0314 0 26.5586 0H0.856708C0.383845 0 0 0.383774 0 0.856708C0 1.32964 0.383845 1.71342 0.856708 1.71342Z" fill="black" />
            <path d="M26.5586 8.56738H0.856708C0.383774 8.56738 0 8.95123 0 9.42409C0 9.89695 0.383845 10.2808 0.856708 10.2808H26.5586C27.0315 10.2808 27.4153 9.89695 27.4153 9.42409C27.4153 8.95123 27.0315 8.56738 26.5586 8.56738Z" fill="black" />
            <path d="M26.5586 17.1345H0.856708C0.383774 17.1345 0 17.5184 0 17.9912C0 18.4642 0.383845 18.8479 0.856708 18.8479H26.5586C27.0315 18.8479 27.4153 18.4641 27.4153 17.9912C27.4154 17.5183 27.0315 17.1345 26.5586 17.1345Z" fill="black" />
          </svg>
        </span>
        <span class="close-burger"><svg width="28" height="19" viewBox="0 0 28 19" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23.1905 0.767303L3.86027 16.9673C3.50458 17.2654 3.45818 17.7957 3.75659 18.1509C4.05506 18.5061 4.58594 18.5524 4.94157 18.2544L24.2718 2.05437C24.6275 1.75627 24.6739 1.22605 24.3755 0.870847C24.0771 0.515549 23.5462 0.46921 23.1905 0.767303Z" fill="black" />
            <path d="M3.64474 1.97729L22.9778 18.1797C23.3336 18.4779 23.8642 18.4318 24.1622 18.0771C24.4602 17.7223 24.4134 17.1924 24.0577 16.8943L4.72462 0.691923C4.36893 0.393831 3.83833 0.439755 3.54026 0.794541C3.24219 1.14933 3.28905 1.6792 3.64474 1.97729Z" fill="black" />
          </svg></span>
      </button>
    </div>
  </header>


  <!-- Panel mobile — slide depuis la droite au clic sur le burger -->
  <div class="mobile-panel">
    <!-- Header du panel mobile avec logo + croix -->
    <div class="mobile-panel-header">
      <a href="<?php echo esc_url(home_url()); ?>">
        <img src="<?php echo esc_attr(get_theme_file_uri('assets/images/logo.svg')); ?>" alt="Nathalie Mota">
      </a>
      <button class="menu-toggle-close" aria-label="Fermer le menu">
        <svg width="28" height="19" viewBox="0 0 28 19" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M23.1905 0.767303L3.86027 16.9673C3.50458 17.2654 3.45818 17.7957 3.75659 18.1509C4.05506 18.5061 4.58594 18.5524 4.94157 18.2544L24.2718 2.05437C24.6275 1.75627 24.6739 1.22605 24.3755 0.870847C24.0771 0.515549 23.5462 0.46921 23.1905 0.767303Z" fill="black" />
          <path d="M3.64474 1.97729L22.9778 18.1797C23.3336 18.4779 23.8642 18.4318 24.1622 18.0771C24.4602 17.7223 24.4134 17.1924 24.0577 16.8943L4.72462 0.691923C4.36893 0.393831 3.83833 0.439755 3.54026 0.794541C3.24219 1.14933 3.28905 1.6792 3.64474 1.97729Z" fill="black" />
        </svg>
      </button>
    </div>

    <div class="nav-mobile">
      <?php wp_nav_menu([
        'theme_location' => 'main-menu',
        'container' => false,
        'menu_class' => 'main-menu-mobile',
      ]); ?>
    </div>
  </div>

  <main class="site-main">