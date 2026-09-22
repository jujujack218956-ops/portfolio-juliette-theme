</main>
<!-- Fermeture de la balise <main> ouverte dans header.php -->

<footer class="footer" role="contentinfo">
  <!-- role="contentinfo" : Attribut ARIA pour identifier le pied de page principal -->

  <div class="footer-container">

    <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>" class="footer-link">
      Mentions légales
    </a>
    <a href="<?php echo esc_url(home_url('/vie-privee')); ?>" class="footer-link">
      Vie privée </a>
    <p class="footer-text">
      Tous droits réservés
    </p>
  </div>

</footer>

<?php get_template_part('template-parts/modal-contact'); ?>
<!-- Inclusion du template de la modale de contact -->

<?php wp_footer(); ?>
<!-- wp_footer() : Hook WordPress essentiel avant la fermeture du </body>
     pour charger les scripts JavaScript et permettre aux plugins d'injecter du contenu
     OBLIGATOIRE pour le bon fonctionnement de WordPress et des plugins -->

</body>

</html>