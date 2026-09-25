<?php
/**
 * Carte d'un projet, utilisée dans la liste des réalisations
 * et sur la page d'accueil (3 derniers projets).
 * À placer dans template-parts/projet-block.php.
 *
 * @package portfolio-juliette
 */

$annee = get_post_meta( get_the_ID(), '_jul_annee', true );
$types = get_the_terms( get_the_ID(), 'type_projet' );
?>

<article class="projet-block">

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="projet-block__image">
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="projet-block__texte">

		<?php if ( $types && ! is_wp_error( $types ) ) : ?>
			<p class="projet-block__types">
				<?php echo esc_html( implode( ' · ', wp_list_pluck( $types, 'name' ) ) ); ?>
				<?php if ( $annee ) : ?>
					· <?php echo esc_html( $annee ); ?>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<h2 class="projet-block__titre">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<?php if ( has_excerpt() ) : ?>
			<p class="projet-block__resume"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

	</div>

</article>
