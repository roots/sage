module.exports = function (extensions) {
  extensions = extensions||[];
  if (require.extensions) {
    return extensions.concat(Object.keys(require.extensions));
  } else {
    return extensions;
  }
};
