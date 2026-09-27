</main>
<!-- Fermeture du <main id="contenu"> ouvert dans header.php -->

<?php
// Écoscore : valeurs saisies dans Apparence › Personnaliser › Écoscore
// (includes/ecoscore.php). Vides tant que rien n'est mesuré : rien ne s'affiche.
$ecoscore_poids = portfolio_juliette_ecoscore('poids');
$ecoscore_note  = portfolio_juliette_ecoscore('note');
$ecoscore_date  = portfolio_juliette_ecoscore('date');

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
        <?php if ($ecoscore_date) : ?>
          (mesure : <?php echo esc_html($ecoscore_date); ?>)
        <?php endif; ?>
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