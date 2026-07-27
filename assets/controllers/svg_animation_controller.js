import { Controller } from '@hotwired/stimulus';
import { gsap } from 'gsap';

/*
 * Animates SVG elements with GSAP.
 *
 * Wire it up in a template like this:
 *
 *   <div data-controller="svg-animation">
 *       <svg viewBox="0 0 100 100">
 *           <circle data-svg-animation-target="shape" cx="50" cy="50" r="20" />
 *       </svg>
 *   </div>
 *
 * Any element marked data-svg-animation-target="shape" gets animated on connect.
 */
export default class extends Controller {
    static targets = ['shape'];

    connect() {
        gsap.from(this.shapeTargets, {
            duration: 1,
            scale: 0,
            opacity: 0,
            transformOrigin: 'center center',
            ease: 'back.out(1.7)',
            stagger: 0.15,
        });
    }
}
