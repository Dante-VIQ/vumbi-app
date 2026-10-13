import './bootstrap';

import Alpine from 'alpinejs';

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';
import 'flatpickr/dist/plugins/monthSelect/style.css';

// Brand skin — imported last so it wins the cascade over Tailwind Preflight.
import '../css/flatpickr-theme.css';

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.monthSelectPlugin = monthSelectPlugin;

/* ============================================================
 *  Register every Alpine component inside `alpine:init`.
 *  Do NOT call Alpine.start() in here — Alpine.start() is what
 *  fires this event, so calling it again recurses infinitely.
 * ============================================================ */
document.addEventListener('alpine:init', () => {

    Alpine.data('travelMonthPicker', () => ({
        picker: null,

        init() {
            if (!this.$refs.input) return;

            this.picker = window.flatpickr(this.$refs.input, {
                plugins: [
                    new window.monthSelectPlugin({
                        shorthand: true,
                        dateFormat: 'Y-m',
                        altFormat: 'F Y',
                    }),
                ],
                minDate: 'today',
                maxDate: new Date(new Date().setFullYear(new Date().getFullYear() + 2)),
                disableMobile: true,
                allowInput: false,
                defaultDate: this.$refs.input.value || null,
                static: true,
                positionElement: this.$refs.input,
                onChange: (selectedDates, dateStr) => {
                    this.$refs.input.value = dateStr;
                },
            });
        },

        destroy() {
            if (this.picker) {
                this.picker.destroy();
                this.picker = null;
            }
        },

        reset() {
            if (this.picker) {
                this.picker.clear();
                this.$refs.input.value = '';
            }
        },
    }));

    Alpine.data('travelDateRange', () => ({
        picker: null,

        init() {
            if (!this.$refs.start) return;

            this.picker = window.flatpickr(this.$refs.start, {
                mode: 'range',
                minDate: 'today',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'D, d M Y',
                disableMobile: true,
                static: true,
                onChange: (dates) => {
                    this.$refs.start.value = dates[0]
                        ? this.picker.formatDate(dates[0], 'Y-m-d')
                        : '';
                    this.$refs.end.value = dates[1]
                        ? this.picker.formatDate(dates[1], 'Y-m-d')
                        : '';
                },
            });
        },

        destroy() {
            if (this.picker) {
                this.picker.destroy();
                this.picker = null;
            }
        },
    }));

});

/* ============================================================
 *  Start Alpine exactly ONCE, at the bottom, outside listeners.
 * ============================================================ */
Alpine.start();