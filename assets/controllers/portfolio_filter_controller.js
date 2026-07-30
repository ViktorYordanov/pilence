import { Controller } from '@hotwired/stimulus';

/*
 * Filters the portfolio grid by tag.
 *
 * Wired up in templates/portfolio/index.html.twig via data-controller="portfolio-filter".
 *
 * Behaviour:
 *   - "All" is exclusive: selecting it clears every tag selection.
 *   - Selecting any tag deselects "All".
 *   - Selecting no tags falls back to "All" (everything shown), and vice-versa.
 *   - Multiple tags use OR logic: a card shows if it matches ANY selected tag.
 */
export default class extends Controller {
    static targets = ['button', 'card', 'empty'];

    connect() {
        // Empty set means "All" is active.
        this.selected = new Set();
        this.render();
    }

    toggle(event) {
        const tag = event.currentTarget.dataset.tag;

        if (tag === 'all') {
            this.selected.clear();
        } else if (this.selected.has(tag)) {
            this.selected.delete(tag);
        } else {
            this.selected.add(tag);
        }

        this.render();
    }

    render() {
        const showAll = this.selected.size === 0;

        // Sync the buttons' active/pressed state.
        this.buttonTargets.forEach((button) => {
            const tag = button.dataset.tag;
            const active = tag === 'all' ? showAll : this.selected.has(tag);
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        // Show/hide cards (OR match against the selected tags).
        let visible = 0;
        this.cardTargets.forEach((card) => {
            const tags = (card.dataset.tags || '').split(' ').filter(Boolean);
            const show = showAll || tags.some((tag) => this.selected.has(tag));
            card.classList.toggle('is-hidden', !show);
            card.setAttribute('aria-hidden', show ? 'false' : 'true');
            if (show) {
                visible += 1;
            }
        });

        if (this.hasEmptyTarget) {
            this.emptyTarget.hidden = visible !== 0;
        }
    }
}
