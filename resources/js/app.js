import './bootstrap';

import Swal from 'sweetalert2'
window.Swal = Swal

import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";

flatpickr.localize(Portuguese);
window.flatpickr = flatpickr;

import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import ptBrLocale from '@fullcalendar/core/locales/pt-br'

window.Calendar = Calendar
window.dayGridPlugin = dayGridPlugin
window.interactionPlugin = interactionPlugin
window.ptBrLocale = ptBrLocale

document.addEventListener('livewire:init', () => {

    window.Alpine.data('cookieConsent', () => ({
        open: false,
        accepted: false,
        stats: false,
        marketing: false,

        init() {
            const saved = localStorage.getItem('cookie_consent');
            if (saved) {
                const prefs = JSON.parse(saved);
                this.stats = prefs.stats ?? false;
                this.marketing = prefs.marketing ?? false;
                this.accepted = true;
            }
        },

        openModal() { this.open = true; },
        closeModal() { this.open = false; },

        acceptAll() {
            this.stats = true;
            this.marketing = true;
            this.save();
        },

        save() {
            const preferences = {
                stats: this.stats,
                marketing: this.marketing
            };
            localStorage.setItem('cookie_consent', JSON.stringify(preferences));
            this.accepted = true;
            this.open = false;
        }
    }))

})