import './bootstrap';

import Alpine from 'alpinejs'

Alpine.data('cookieConsent', () => ({
    open: false,
    accepted: localStorage.getItem('cookie_consent') !== null,
    stats: false,
    marketing: false,

    init() {
        const saved = localStorage.getItem('cookie_consent');
        if (saved) {
            const prefs = JSON.parse(saved);
            this.stats = prefs.stats ?? false;
            this.marketing = prefs.marketing ?? false;
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
        localStorage.setItem('cookie_consent', JSON.stringify({
            stats: this.stats,
            marketing: this.marketing
        }));
        this.accepted = true;
        this.open = false;
    }
}))

Alpine.start()
window.Alpine = Alpine


