/* eslint-disable */

const cssnanoConfig = {
  preset: ['default', { discardComments: { removeAll: true } }]
};

module.exports = ({ file, options, env }) => ({
  parser: options.enabled.optimize ? 'postcss-safe-parser' : undefined,
  plugins: {
    autoprefixer: true,
    cssnano: options.enabled.optimize ? cssnanoConfig : false,
  },
})
