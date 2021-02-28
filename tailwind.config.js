module.exports = {
  purge: {
    content: [
      './{app|resources}/**/*.php',
      './resources/**/*.{js,css,scss,ts,tsx,vue}',
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
