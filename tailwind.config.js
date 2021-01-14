module.exports = {
  purge: {
    content: [
      './**/*.php',
      './resources/**/*.vue',
      './resources/**/*.js',
      './resources/**/*.json',
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
