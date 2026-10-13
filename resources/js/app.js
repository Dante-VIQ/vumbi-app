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
    backdrop: null,

    init() {
        if (!this.$refs.input) return;

        // Create the backdrop once, before Flatpickr opens.
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'flatpickr-backdrop';
        this.backdrop.setAttribute('aria-hidden', 'true');
        this.backdrop.addEventListener('click', () => this.picker && this.picker.close());
        document.body.appendChild(this.backdrop);

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

            // Send the calendar to <body> so we can center it independently
            // of the modal's stacking context.
            static: false,
            appendTo: document.body,

            onOpen: (selectedDates, dateStr, instance) => {
                const cal = instance.calendarContainer;

                // Prevent the initial layout jump while Flatpickr mounts the popup.
                cal.style.visibility = 'hidden';
                cal.style.opacity = '0';

                requestAnimationFrame(() => {
                    cal.style.position = 'fixed';
                    cal.style.top = '50%';
                    cal.style.left = '50%';
                    cal.style.transform = 'translate(-50%, -50%)';
                    cal.style.margin = '0';
                    cal.style.opacity = '1';
                    cal.style.visibility = 'visible';

                    cal.classList.remove('flatpickr-calendar--centered');
                    void cal.offsetWidth;
                    cal.classList.add('flatpickr-calendar--centered');
                });

                // Lock scroll + show backdrop.
                document.body.style.overflow = 'hidden';
                this.backdrop.classList.add('is-open');
            },

            onClose: () => {
                // Unlock scroll + hide backdrop.
                document.body.style.overflow = '';
                this.backdrop.classList.remove('is-open');
            },

            onChange: (selectedDates, dateStr) => {
                this.$refs.input.value = dateStr;
                // Auto-close on selection — standard modal behaviour.
                this.picker.close();
            },
        });
    },

    destroy() {
        if (this.picker) {
            this.picker.destroy();
            this.picker = null;
        }
        if (this.backdrop && this.backdrop.parentNode) {
            this.backdrop.parentNode.removeChild(this.backdrop);
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