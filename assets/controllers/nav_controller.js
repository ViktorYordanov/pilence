import { Controller } from '@hotwired/stimulus';

/*
 * Toggles the primary navigation on small screens.
 * Wired up in templates/home/index.html.twig via data-controller="nav".
 */
export default class extends Controller {
    static targets = ['menu', 'button'];

    toggle() {
        const isOpen = this.element.classList.toggle('is-open');
        this.#syncAria(isOpen);
    }

    close() {
        this.element.classList.remove('is-open');
        this.#syncAria(false);
    }

    // Close the menu after a nav link is tapped (mobile).
    menuTargetConnected(menu) {
        menu.addEventListener('click', (event) => {
            if (event.target.closest('a')) {
                this.close();
            }
        });
    }

    #syncAria(isOpen) {
        if (this.hasButtonTarget) {
            this.buttonTarget.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
    }
}
