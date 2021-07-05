module.exports = {
  purge: {
    content: [
      './{app,resources}/**/*.{html,php}',
      './resources/**/*.{js,ts,css}',
    ],
  },
  jit: true,
  darkMode: false,
  theme: {
    extend: {
      colors: {},
    },
  },
  variants: {
    extend: {},
  },
  plugins: [require('@tailwindcss/typography')],
};
