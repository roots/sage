module.exports = {
  purge: {
    content: ['./{app,resources}/**/*.php', './resources/**/*.{js,css}'],
  },
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
