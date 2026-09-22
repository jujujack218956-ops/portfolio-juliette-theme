/**
 * Lightbox — affichage plein écran des photos.
 * Déclenchée par les boutons .photo-block__fullscreen (data-full = image native).
 * La galerie et la navigation se basent sur toutes les photos présentes dans la page,
 * y compris celles ajoutées en Ajax (délégation d'événement).
 */
class Lightbox {
  /**
   * Écoute globale : un seul listener couvre aussi les photos chargées plus tard.
   */
  static init() {
    document.addEventListener('click', (e) => {
      const bouton = e.target.closest('.photo-block__fullscreen');
      if (!bouton) return;
      e.preventDefault();
      new Lightbox(bouton.dataset.full, Lightbox.galerie());
    });
  }

  /**
   * Construit la liste des photos de la page à l'instant du clic.
   * @return {{url: string, title: string}[]}
   */
  static galerie() {
    return Array.from(document.querySelectorAll('.photo-block__fullscreen'))
      .map((b) => ({
        url: b.dataset.full,
        title: b.dataset.title || '',
        reference: b.dataset.reference || '',
        category: b.dataset.category || '',
      }));
  }

  /**
   * @param {string} url    URL de l'image à afficher
   * @param {{url: string, title: string}[]} images  Galerie complète
   */
  constructor(url, images) {
    this.images = images;
    this.onKeyUp = this.onKeyUp.bind(this);
    this.element = this.buildDOM();
    document.body.appendChild(this.element);
    document.addEventListener('keyup', this.onKeyUp);
    this.loadImage(url);
  }

  /**
   * Charge une image et met à jour la référence et la catégorie.
   * @param {string} url
   */
  loadImage(url) {
    this.url = null;
    const container = this.element.querySelector('.lightbox__container');
    const image = new Image();

    const loader = document.createElement('div');
    loader.classList.add('lightbox__loader');
    container.innerHTML = '';
    container.appendChild(loader);

    image.onload = () => {
      container.removeChild(loader);
      container.appendChild(image);
      this.url = url;
    };
    image.src = url;

    const photo = this.images.find((i) => i.url === url);
    this.element.querySelector('.lightbox__reference').textContent = photo ? photo.reference : '';
    this.element.querySelector('.lightbox__category').textContent = photo ? photo.category : '';
    image.alt = photo ? photo.title : '';
  }

  /**
   * Photo suivante (boucle en fin de galerie).
   * @param {MouseEvent|KeyboardEvent} e
   */
  next(e) {
    e.preventDefault();
    let i = this.images.findIndex((image) => image.url === this.url);
    if (i === this.images.length - 1) i = -1;
    this.loadImage(this.images[i + 1].url);
  }

  /**
   * Photo précédente (boucle en début de galerie).
   * @param {MouseEvent|KeyboardEvent} e
   */
  prev(e) {
    e.preventDefault();
    let i = this.images.findIndex((image) => image.url === this.url);
    if (i === 0) i = this.images.length;
    this.loadImage(this.images[i - 1].url);
  }

  /**
   * Ferme et nettoie la lightbox.
   * @param {MouseEvent|KeyboardEvent} e
   */
  close(e) {
    if (e) e.preventDefault();
    this.element.remove();
    document.removeEventListener('keyup', this.onKeyUp);
  }

  /**
   * Raccourcis clavier : Échap ferme, flèches naviguent (accessibilité).
   * @param {KeyboardEvent} e
   */
  onKeyUp(e) {
    if (e.key === 'Escape') this.close(e);
    else if (e.key === 'ArrowRight') this.next(e);
    else if (e.key === 'ArrowLeft') this.prev(e);
  }

  /**
   * Crée la structure de la lightbox et branche les comportements.
   * @return {HTMLElement}
   */
  buildDOM() {
    const dom = document.createElement('div');
    dom.classList.add('lightbox');
    dom.setAttribute('role', 'dialog');
    dom.setAttribute('aria-label', 'Photo en plein écran');
    dom.innerHTML = `
      <div class="lightbox__inner">
      <div class="lightbox__top">
        <button class="lightbox__close" type="button" aria-label="Fermer">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <line x1="5" y1="5" x2="19" y2="19" />
            <line x1="19" y1="5" x2="5" y2="19" />
          </svg>
        </button>
      </div>
      <div class="lightbox__body">
        <button class="lightbox__prev" type="button">
          <svg class="lightbox__arrow" viewBox="0 0 44 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <line x1="44" y1="8" x2="2" y2="8" />
            <polyline points="10,1 2,8 10,15" />
          </svg>
          <span>Précédente</span>
        </button>
        <figure class="lightbox__figure">
          <div class="lightbox__container"></div>
          <p class="lightbox__reference"></p>
          <p class="lightbox__category"></p>
        </figure>
        <button class="lightbox__next" type="button">
          <span>Suivante</span>
          <svg class="lightbox__arrow" viewBox="0 0 44 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <line x1="0" y1="8" x2="42" y2="8" />
            <polyline points="34,1 42,8 34,15" />
          </svg>
        </button>
      </div>
      </div>`;

    dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
    dom.querySelector('.lightbox__next').addEventListener('click', this.next.bind(this));
    dom.querySelector('.lightbox__prev').addEventListener('click', this.prev.bind(this));
    // Clic sur le fond (hors photo et boutons) → fermeture
    dom.addEventListener('click', (e) => {
      if (!e.target.closest('.lightbox__figure, .lightbox__prev, .lightbox__next, .lightbox__close')) {
        this.close(e);
      }
    });
    return dom;
  }
}

Lightbox.init();
