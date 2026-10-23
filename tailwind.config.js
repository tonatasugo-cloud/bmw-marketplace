/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                'display': ['Montserrat', 'ui-sans-serif', 'system-ui'],
                'mono': ['ui-monospace', 'SFMono-Regular'],
            },
        },
    },
    plugins: [],
}