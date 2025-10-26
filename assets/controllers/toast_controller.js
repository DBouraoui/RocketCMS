import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['toast'];
    static values = {
        duration: { type: Number, default: 5000 } // 5 secondes par défaut
    }

    connect() {
        // Animation d'entrée
        this.element.style.opacity = '0';
        this.element.style.transform = 'translateX(100%)';

        requestAnimationFrame(() => {
            this.element.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            this.element.style.opacity = '1';
            this.element.style.transform = 'translateX(0)';
        });

        // Auto-dismiss après X secondes
        this.timeout = setTimeout(() => {
            this.close();
        }, this.durationValue);
    }

    disconnect() {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
    }

    close() {
        // Animation de sortie
        this.element.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        this.element.style.opacity = '0';
        this.element.style.transform = 'translateX(100%)';

        // Supprime l'élément après l'animation
        setTimeout(() => {
            this.element.remove();
        }, 500);
    }
}
