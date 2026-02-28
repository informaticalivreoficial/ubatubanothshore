/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                  DEFAULT: '#1a2e1a',
                  dark: '#1558a0',
                  light: '#e8f2fc',
                },
                navy: {
                  DEFAULT: '#0f2744',
                  light: '#1a3a60',
                },
                accent: '#c9a96e',
                'bg-light': '#f5f7fa',
                'text-main': '#1a2332',
                'text-muted': '#6b7280',
                cream:     '#f8f5f0',
                sand:      '#e8e0d0',
            },
            borderRadius: {
              card: '18px',
              pill: '999px',
            },
            boxShadow: {
              card:  '0 4px 20px rgba(0,0,0,0.08)',
              hover: '0 12px 40px rgba(0,0,0,0.15)',
            },
            fontFamily: {
              sans:    ['Inter', 'sans-serif'],
              heading: ['Montserrat', 'sans-serif'],
            },
            maxWidth: {
              '8xl': '1280px',
            },
        },
    },
    plugins: [],
}
