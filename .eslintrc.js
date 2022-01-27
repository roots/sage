module.exports = {
  root: true,
  extends: [
    require.resolve('@roots/sage/eslint-config'),
    require.resolve('@roots/bud-typescript/eslint-config'),
  ],
  rules: {
    '@typescript-eslint/ban-ts-comment': 'off',
  },
};
