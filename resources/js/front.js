import './bootstrap';

import flatpickr from "flatpickr"
import { Portuguese } from "flatpickr/dist/l10n/pt.js"

window.flatpickr = flatpickr
window.FlatpickrPortuguese = Portuguese

import Swal from 'sweetalert2'
window.Swal = Swal

import IMask from 'imask';
window.IMask = IMask;

const toIsoDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
};

window.dateRangePicker = () => ({
    init() {
        const disabledDates = JSON.parse(this.$el.dataset.disabled || '[]')
            .map((date) => {
                const [year, month, day] = date.split('-').map(Number);

                return new Date(year, month - 1, day);
            });

        const maxDate = this.$el.dataset.maxDate || null;
        const method = this.$el.dataset.method || 'calculate';
        const months = Number(this.$el.dataset.months || 1);
        const defaultDates = JSON.parse(this.$el.dataset.default || '[]').filter(Boolean);
        const wireId = this.$el.closest('[wire\\:id]')?.getAttribute('wire:id');
        const component = this.$wire ?? (wireId ? window.Livewire.find(wireId) : null);

        flatpickr(this.$refs.picker, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            showMonths: months,
            minDate: 'today',
            maxDate: maxDate || undefined,
            defaultDate: defaultDates.length ? defaultDates : undefined,
            locale: FlatpickrPortuguese,
            rangeSeparator: ' até ',
            disable: disabledDates,

            onReady: (selectedDates, dateStr, instance) => {
                instance.set('locale', {
                    ...instance.l10n,
                    firstDayOfWeek: 1,
                });
            },

            onChange: (selectedDates) => {
                if (selectedDates.length !== 2 || !component) {
                    return;
                }

                component.set('check_in', toIsoDate(selectedDates[0]));
                component.set('check_out', toIsoDate(selectedDates[1]));
                component.call(method);
            },
        });
    },
});

window.searchCalendar = (options = {}) => ({
    guests: Math.max(1, Number(options.guests) || 1),
    checkInPicker: null,
    checkOutPicker: null,

    init() {
        const checkInInput = this.$refs.checkin;
        const checkOutInput = this.$refs.checkout;

        if (!checkInInput || !checkOutInput) {
            return;
        }

        const component = options.livewire ? this.$wire : null;

        const pickerOptions = (input) => ({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            altInputClass: `${input.className} cursor-pointer appearance-none`,
            locale: FlatpickrPortuguese,
            minDate: 'today',
            disableMobile: true,

            onReady: (selectedDates, dateStr, instance) => {
                if (instance.altInput && input.placeholder) {
                    instance.altInput.placeholder = input.placeholder;
                }
            },
        });

        this.checkInPicker = flatpickr(checkInInput, {
            ...pickerOptions(checkInInput),
            defaultDate: checkInInput.value || undefined,

            onChange: (selectedDates) => {
                const date = selectedDates[0] || null;

                if (this.checkOutPicker) {
                    this.checkOutPicker.set('minDate', date || 'today');

                    if (date && this.checkOutPicker.selectedDates[0] < date) {
                        this.checkOutPicker.clear();
                    }
                }

                if (component) {
                    component.set('check_in', date ? toIsoDate(date) : null);
                }
            },
        });

        this.checkOutPicker = flatpickr(checkOutInput, {
            ...pickerOptions(checkOutInput),
            minDate: this.checkInPicker.selectedDates[0] || 'today',
            defaultDate: checkOutInput.value || undefined,

            onChange: (selectedDates) => {
                if (component) {
                    component.set('check_out', selectedDates[0] ? toIsoDate(selectedDates[0]) : null);
                }
            },
        });
    },

    decrementGuests() {
        this.guests = Math.max(1, this.guests - 1);
    },

    incrementGuests() {
        this.guests += 1;
    },
});

document.addEventListener('alpine:init', () => {
    Alpine.data('cookieConsent', () => ({
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

        openModal() { this.open = true },
        closeModal() { this.open = false },

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
})

// Aguarda o DOM carregar para verificar se o Livewire subiu
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Alpine) {
        import('alpinejs').then(({ default: Alpine }) => {
            window.Alpine = Alpine
            Alpine.start()
        })
    }
})



