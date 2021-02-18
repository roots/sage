module.exports = {
  purge: {
    content: [
      './{app,resources}/**/*.php',
      './resources/**/*.{vue,(t|j)?s(x)?}',
    ],
  },
  darkMode: false, // or 'media' or 'class'
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
