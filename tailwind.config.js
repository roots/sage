module.exports = {
  purge: {
    content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
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
  plugins: [],
};
