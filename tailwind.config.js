/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    safelist: [
        // Dynamic status badge colors  (e.g. bg-{{ $order->status_color }}-100)
        {
            pattern: /^(bg|text|border|ring)-(yellow|blue|indigo|green|gray|red|orange|purple|pink|rose|emerald|amber|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/,
        },
        // Sidebar gradient per role
        'from-rose-900', 'to-pink-800',
        'from-orange-700', 'to-orange-600',
        'from-pink-700', 'to-rose-500',
        'from-slate-800', 'to-slate-700',
        // File input utilities
        'file:bg-pink-600', 'hover:file:bg-pink-700',
        'file:bg-blue-600', 'hover:file:bg-blue-700',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                brand: {
                    50:  '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899',
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                },
            },
        },
    },
    plugins: [],
};
