module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: 'airbnb-base',
  overrides: [],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  ignorePatterns: ['**/*.mjs', '**/vendor/*.js'],
  rules: {
    'import/no-unresolved': 0,
    'no-console': 0,
  },
};