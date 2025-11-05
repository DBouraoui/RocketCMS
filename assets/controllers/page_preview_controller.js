import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['iframe', 'loading', 'title'];

    connect() {
        this.currentUrl = null;
        console.log('PagePreview controller connected');
    }

    /**
     * Met à jour l'aperçu avec une nouvelle URL
     * @param {Event} event - L'événement de clic
     */
    updatePreview(event) {
        // Empêcher le comportement par défaut si c'est un lien
        if (event.target.tagName === 'A' || event.target.closest('a')) {
            return;
        }

        const url = event.params.url;
        const title = event.params.title;

        // Éviter le rechargement si c'est la même URL
        if (url === this.currentUrl) {
            return;
        }

        this.showLoading();
        this.updateTitle(title);
        this.loadPage(url);
    }

    /**
     * Rafraîchit la page en cours dans l'iframe
     */
    refresh() {
        if (!this.currentUrl) {
            return;
        }

        this.showLoading();
        this.iframeTarget.src = this.currentUrl;
    }

    /**
     * Charge une nouvelle page dans l'iframe
     * @param {string} url - L'URL à charger
     */
    loadPage(url) {
        this.currentUrl = url;
        this.iframeTarget.src = url;
    }

    /**
     * Affiche le loader
     */
    showLoading() {
        this.loadingTarget.classList.remove('hidden');
    }

    /**
     * Cache le loader (appelé automatiquement quand l'iframe est chargée)
     */
    hideLoading() {
        setTimeout(() => {
            this.loadingTarget.classList.add('hidden');
        }, 300);
    }

    /**
     * Met à jour le titre de l'aperçu
     * @param {string} title - Le nouveau titre
     */
    updateTitle(title) {
        this.titleTarget.textContent = `Aperçu : ${title}`;
    }
}
