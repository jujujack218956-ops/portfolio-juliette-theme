</main>
<!-- Fermeture du <main id="contenu"> ouvert dans header.php -->

<?php
// Écoscore : à renseigner UNIQUEMENT avec des valeurs mesurées sur le site en ligne
// (page d'accueil), et à re-mesurer après chaque ajout de page.
// Tant que ces deux valeurs sont vides, la ligne ne s'affiche pas.
$ecoscore_poids = ''; // ex. '312 Ko'
$ecoscore_note  = ''; // ex. 'A'

// Page « Politique de confidentialité » désignée dans Réglages → Confidentialité.
$lien_confidentialite = get_privacy_policy_url();
?>

<footer class="site-footer">
  <div class="site-footer__inner">

    <p class="site-footer__identite">
      Juliette Béthery — Agence Bethauv · Meuilley, Côte-d'Or
    </p>

    <nav class="site-footer__nav" aria-label="Informations légales">
      <ul>
        <li><a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>">Mentions légales</a></li>
        <?php if ($lien_confidentialite) : ?>
          <li><a href="<?php echo esc_url($lien_confidentialite); ?>">Politique de confidentialité</a></li>
        <?php endif; ?>
      </ul>
    </nav>

    <?php if ($ecoscore_poids && $ecoscore_note) : ?>
      <p class="site-footer__ecoscore">
        Site éco-conçu — page d'accueil chargée en <?php echo esc_html($ecoscore_poids); ?>
        · EcoIndex <?php echo esc_html($ecoscore_note); ?>
      </p>
    <?php endif; ?>

    <p class="site-footer__copyright">
      © <?php echo esc_html(wp_date('Y')); ?> Juliette Béthery — Tous droits réservés
    </p>

  </div>
</footer>

<?php wp_footer(); ?>
<!-- wp_footer() : obligatoire avant </body>. WordPress y charge les scripts
     déclarés avec $in_footer = true (dont scripts.js) et la barre d'administration. -->

</body>

</html>