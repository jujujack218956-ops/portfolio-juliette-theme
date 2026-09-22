<?php
$reference = get_post_meta(get_the_ID(), 'reference', true);
$terms = get_the_terms(get_the_ID(), 'categorie');
$categorie_nom = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
?>
<div class="photo-block">

  <a href="<?php the_permalink(); ?>" class="photo-block__link">
    <?php the_post_thumbnail('medium_large', ['class' => 'photo-block__image']); ?>
  </a>

  <div class="photo-block__overlay">
    <a class="photo-block__eye" href="<?php the_permalink(); ?>" aria-label="Voir la photo">
      <svg viewBox="0 0 46 32" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <path d="M45.9081 15.1504C41.9937 5.94703 33.0015 0 23 0C12.9985 0 4.00649 5.94685 0.0919102 15.1504C-0.0306367 15.4385 -0.0306367 15.7638 0.0919102 16.0518C4.00622 25.2563 12.9983 31.2038 23 31.2038C33.0019 31.2038 41.994 25.2563 45.9081 16.0518C46.0306 15.7638 46.0306 15.4385 45.9081 15.1504ZM23 28.9008C14.088 28.9008 6.05933 23.6968 2.40862 15.6013C6.05942 7.50654 14.0883 2.30314 23 2.30314C31.9119 2.30314 39.9407 7.50654 43.5914 15.6011C39.9407 23.6967 31.912 28.9008 23 28.9008Z" />
        <path d="M23 6.78149C18.1364 6.78149 14.1797 10.7383 14.1797 15.6018C14.1797 20.4653 18.1365 24.4221 23 24.4221C27.8635 24.4221 31.8203 20.4654 31.8203 15.6018C31.8203 10.7382 27.8635 6.78149 23 6.78149ZM23 22.1193C19.4064 22.1193 16.4827 19.1956 16.4827 15.602C16.4827 12.0084 19.4064 9.08473 23 9.08473C26.5936 9.08473 29.5173 12.0084 29.5173 15.602C29.5173 19.1956 26.5936 22.1193 23 22.1193Z" />
        <path d="M22.9999 10.9192C20.4179 10.9192 18.317 13.0199 18.317 15.6021C18.317 16.238 18.8325 16.7536 19.4685 16.7536C20.1046 16.7536 20.6201 16.238 20.6201 15.6021C20.6201 14.2899 21.6876 13.2222 22.9999 13.2222C23.636 13.2222 24.1515 12.7066 24.1515 12.0707C24.1515 11.4346 23.6359 10.9192 22.9999 10.9192Z" />
      </svg>
    </a>
    <button class="photo-block__fullscreen" type="button" data-full="<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>" data-title="<?php echo esc_attr(get_the_title()); ?>" data-reference="<?php echo esc_attr($reference); ?>" data-category="<?php echo esc_attr($categorie_nom); ?>" aria-label="Afficher en plein écran">
      <svg viewBox="0 0 34 34" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <circle cx="17" cy="17" r="17" fill="black" stroke="none" />
        <g stroke="white" stroke-width="1.5">
          <line x1="15" y1="10.5" x2="10" y2="10.5" />
          <line y1="-0.5" x2="5" y2="-0.5" transform="matrix(-1 8.74227e-08 8.74227e-08 1 15 24)" />
          <line x1="9.5" y1="16" x2="9.5" y2="10" />
          <line y1="-0.5" x2="6" y2="-0.5" transform="matrix(4.37114e-08 1 1 -4.37114e-08 10 18)" />
          <line y1="-0.5" x2="5" y2="-0.5" transform="matrix(1 -8.74227e-08 -8.74227e-08 -1 19 10)" />
          <line y1="-0.5" x2="6" y2="-0.5" transform="matrix(-4.37114e-08 -1 -1 4.37114e-08 24 16)" />
          <line x1="19" y1="23.5" x2="24" y2="23.5" />
          <line x1="24.5" y1="18" x2="24.5" y2="24" />
        </g>
      </svg>
    </button>
    <?php if ($reference) : ?>
      <p class="photo-block__reference"><?php echo esc_html($reference); ?></p>
    <?php endif; ?>
    <?php if ($categorie_nom) : ?>
      <p class="photo-block__category"><?php echo esc_html($categorie_nom); ?></p>
    <?php endif; ?>
  </div>
</div>
