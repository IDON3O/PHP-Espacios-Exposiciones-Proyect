import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const withVar = (name) => `rgb(var(--${name}) / <alpha-value>)`;

export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                // Material tokens (panel admin — sin cambios)
                'primary': withVar('color-primary'),
                'on-primary': withVar('color-on-primary'),
                'primary-container': withVar('color-primary-container'),
                'on-primary-container': withVar('color-on-primary-container'),
                'primary-fixed': withVar('color-primary-fixed'),
                'primary-fixed-dim': withVar('color-primary-fixed-dim'),
                'secondary': withVar('color-secondary'),
                'on-secondary': withVar('color-on-secondary'),
                'secondary-container': withVar('color-secondary-container'),
                'on-secondary-container': withVar('color-on-secondary-container'),
                'secondary-fixed': withVar('color-secondary-fixed'),
                'secondary-fixed-dim': withVar('color-secondary-fixed-dim'),
                'tertiary': withVar('color-tertiary'),
                'on-tertiary': withVar('color-on-tertiary'),
                'tertiary-container': withVar('color-tertiary-container'),
                'on-tertiary-container': withVar('color-on-tertiary-container'),
                'tertiary-fixed': withVar('color-tertiary-fixed'),
                'tertiary-fixed-dim': withVar('color-tertiary-fixed-dim'),
                'error': withVar('color-error'),
                'on-error': withVar('color-on-error'),
                'error-container': withVar('color-error-container'),
                'on-error-container': withVar('color-on-error-container'),
                'background': withVar('color-background'),
                'on-background': withVar('color-on-background'),
                'surface': withVar('color-surface'),
                'on-surface': withVar('color-on-surface'),
                'on-surface-variant': withVar('color-on-surface-variant'),
                'surface-dim': withVar('color-surface-dim'),
                'surface-bright': withVar('color-surface-bright'),
                'surface-container': withVar('color-surface-container'),
                'surface-container-low': withVar('color-surface-container-low'),
                'surface-container-lowest': withVar('color-surface-container-lowest'),
                'surface-container-high': withVar('color-surface-container-high'),
                'surface-container-highest': withVar('color-surface-container-highest'),
                'surface-tint': withVar('color-surface-tint'),
                'inverse-surface': withVar('color-inverse-surface'),
                'inverse-on-surface': withVar('color-inverse-on-surface'),
                'inverse-primary': withVar('color-inverse-primary'),
                'outline': withVar('color-outline'),
                'outline-variant': withVar('color-outline-variant'),

                // Aura Narrative tokens (módulo público)
                'aura-ivory':   '#fbf9f9',
                'aura-carbon':  '#1a1a1a',
                'aura-grey':    '#dbdad9',
                'aura-muted':   '#6b6b6b',
                'aura-gold':    '#b8965a',
                'aura-border':  'rgba(26,26,26,0.08)',
            },
            fontFamily: {
                sans:      ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                headline:  ['Manrope', 'Inter', ...defaultTheme.fontFamily.sans],
                body:      ['Inter', ...defaultTheme.fontFamily.sans],
                display:   ['"Playfair Display"', 'Georgia', 'serif'],
            },
            letterSpacing: {
                gallery: '0.18em',
            },
            transitionDuration: {
                600: '600ms',
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                sm:  '0.125rem',
                lg:  '0.25rem',
                xl:  '0.5rem',
                full:'9999px',
            },
        },
    },

    plugins: [forms, typography],
};
