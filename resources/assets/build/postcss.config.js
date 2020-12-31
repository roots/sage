/* eslint-disable */

module.exports = (api) => {

  const cssnanoConfig = {
    preset: ['default', { discardComments: { removeAll: true } }]
  };

  return {
    parser: api.options.ctx.enabled.optimize ? 'postcss-safe-parser' : undefined,
    plugins: {
      autoprefixer: true,
      cssnano: api.options.ctx.enabled.optimize ? cssnanoConfig : false,
    },
  };

};
